<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\MillingRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MillingRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REQUIRE FARMER
    |--------------------------------------------------------------------------
    */

    private function requireFarmer(): User
    {
        $user = Auth::user();

        if (
            !$user ||
            strtolower((string) $user->role) !== 'farmer'
        ) {
            abort(403, 'Unauthorized');
        }

        return $user;
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE MILLING REQUEST FORM
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        $this->requireFarmer();

        /*
         * Show all registered Millers.
         * The Blade shows OPEN/CLOSED status and disables CLOSED Millers.
         */
        $millers = User::query()
            ->whereRaw('LOWER(role) = ?', ['miller'])
            ->orderByDesc('is_open')
            ->orderBy('fullname')
            ->orderBy('username')
            ->get([
                'id',
                'fullname',
                'username',
                'business_name',
                'barangay',
                'address',
                'latitude',
                'longitude',
                'is_open',
            ]);

        return view(
            'farmer.milling.create',
            compact('millers')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE FARMER -> MILLER REQUEST
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        $farmer = $this->requireFarmer();

        $data = $request->validate([
            'miller_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'kilos' => [
                'required',
                'numeric',
                'min:1',
                'max:1000000',
            ],

            'transport_type' => [
                'required',
                'in:delivery,pickup',
            ],

            'pickup_address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDATE SELECTED MILLER
        |--------------------------------------------------------------------------
        */

        $miller = User::query()
            ->where('id', $data['miller_id'])
            ->whereRaw('LOWER(role) = ?', ['miller'])
            ->first();

        if (!$miller) {
            return back()
                ->withErrors([
                    'miller_id' =>
                        'The selected account is not a valid Miller.',
                ])
                ->withInput();
        }

        if (!(bool) ($miller->is_open ?? false)) {
            return back()
                ->withErrors([
                    'miller_id' =>
                        'The selected Miller is currently CLOSED. Please select an OPEN Miller.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | FARMER REGISTERED ADDRESS
        |--------------------------------------------------------------------------
        |
        | Pickup uses the Farmer's own registered USER address, not the
        | farm-map latitude/longitude.
        |
        */

        $farmerAddress =
            $this->userAddress(
                $farmer
            );

        if (
            $data['transport_type'] === 'pickup' &&
            $farmerAddress === ''
        ) {
            return back()
                ->withErrors([
                    'pickup_address' =>
                        'Pickup requires your registered address. Please update your profile address first.',
                ])
                ->withInput();
        }


        try {

            $millingRequest = DB::transaction(
                function () use (
                    $data,
                    $farmer,
                    $miller,
                    $farmerAddress
                ) {

                    $payload = [];


                    /*
                    |--------------------------------------------------------------------------
                    | REQUESTER
                    |--------------------------------------------------------------------------
                    |
                    | New workflow:
                    | requester_id   = Farmer ID
                    | requester_role = farmer
                    |
                    | Legacy fields are also filled when they exist.
                    |
                    */

                    $this->putIfColumnExists(
                        $payload,
                        'requester_id',
                        $farmer->id
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_role',
                        'farmer'
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_name_snapshot',
                        trim((string) ($farmer->fullname ?? $farmer->username ?? 'Farmer #'.$farmer->id))
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_username_snapshot',
                        trim((string) ($farmer->username ?? ''))
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_contact_snapshot',
                        trim((string) (
                            $farmer->mobile_number
                            ?? $farmer->contact_number
                            ?? $farmer->phone
                            ?? ''
                        ))
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_address_snapshot',
                        $farmerAddress
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'farmer_id',
                        $farmer->id
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'user_id',
                        $farmer->id
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'miller_id',
                        $miller->id
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | PRODUCT
                    |--------------------------------------------------------------------------
                    |
                    | Milling requests are for PALAY.
                    |
                    */

                    $this->putFirstExistingColumn(
                        $payload,
                        [
                            'product_type',
                            'product',
                            'rice_type',
                            'crop_type',
                            'palay_type',
                        ],
                        'palay'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | QUANTITY
                    |--------------------------------------------------------------------------
                    */

                    $this->putFirstExistingColumn(
                        $payload,
                        [
                            'quantity_kilos',
                            'quantity_kg',
                            'kilos',
                            'quantity',
                            'weight_kg',
                        ],
                        round(
                            (float) $data['kilos'],
                            2
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | TRANSPORT
                    |--------------------------------------------------------------------------
                    */

                    $this->putIfColumnExists(
                        $payload,
                        'transport_type',
                        $data['transport_type']
                    );


                    if (
                        $data['transport_type'] === 'pickup'
                    ) {

                        $this->putIfColumnExists(
                            $payload,
                            'pickup_address',
                            $farmerAddress
                        );

                        /*
                         * If your schema has a generic requester/pickup snapshot
                         * address, keep it too.
                         */
                        $this->putIfColumnExists(
                            $payload,
                            'requester_address',
                            $farmerAddress
                        );

                        $this->putIfColumnExists(
                            $payload,
                            'requester_address_snapshot',
                            $farmerAddress
                        );

                    } else {

                        /*
                         * Farmer personally delivers the Palay to the Miller.
                         * System shipping fee is zero.
                         */
                        $this->putIfColumnExists(
                            $payload,
                            'shipping_fee',
                            0
                        );

                        $this->putIfColumnExists(
                            $payload,
                            'shipping_distance_km',
                            null
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | NOTES
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !empty($data['notes'])
                    ) {
                        $this->putFirstExistingColumn(
                            $payload,
                            [
                                'notes',
                                'remarks',
                                'message',
                                'details',
                            ],
                            $data['notes']
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | INITIAL WORKFLOW
                    |--------------------------------------------------------------------------
                    |
                    | Farmer <-> Miller:
                    |
                    | PENDING
                    | -> ACCEPTED
                    | -> SCHEDULED + UNPAID
                    | -> PAID
                    | -> IN_PROGRESS
                    | -> FINISHED + PROOF
                    | -> Farmer confirms
                    | -> COMPLETED
                    |
                    */

                    $this->putIfColumnExists(
                        $payload,
                        'status',
                        'pending'
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'payment_status',
                        'unpaid'
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'milling_fee_per_kg',
                        0
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'total_amount',
                        0
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'grand_total',
                        0
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | SAFETY
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !array_key_exists(
                            'miller_id',
                            $payload
                        )
                    ) {
                        throw ValidationException::withMessages([
                            'miller_id' =>
                                'The milling_requests table is missing the miller_id column.',
                        ]);
                    }

                    if (
                        !array_key_exists(
                            'requester_id',
                            $payload
                        ) &&
                        !array_key_exists(
                            'farmer_id',
                            $payload
                        ) &&
                        !array_key_exists(
                            'user_id',
                            $payload
                        )
                    ) {
                        throw ValidationException::withMessages([
                            'requester_id' =>
                                'The milling_requests table has no requester ownership column.',
                        ]);
                    }


                    return MillingRequest::create(
                        $payload
                    );
                }
            );

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                )
                ->withInput();
        }


        /*
         * TransactionObserver handles:
         * Farmer -> Miller notification
         * and Farmer request confirmation notification.
         */

        return redirect()
            ->route('farmer.milling.index')
            ->with(
                'success',
                "Milling Request #{$millingRequest->id} submitted successfully."
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FARMER - MY MILLING REQUESTS
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $farmer =
            $this->requireFarmer();

        $hasRequesterId =
            Schema::hasColumn(
                'milling_requests',
                'requester_id'
            );

        $hasFarmerId =
            Schema::hasColumn(
                'milling_requests',
                'farmer_id'
            );

        $hasUserId =
            Schema::hasColumn(
                'milling_requests',
                'user_id'
            );


        $requests =
            MillingRequest::query()
                ->with('miller')
                ->where(
                    function (Builder $query) use (
                        $farmer,
                        $hasRequesterId,
                        $hasFarmerId,
                        $hasUserId
                    ) {

                        $first =
                            true;


                        if ($hasRequesterId) {

                            $query->where(
                                'requester_id',
                                $farmer->id
                            );

                            $first =
                                false;
                        }


                        if ($hasFarmerId) {

                            if ($first) {
                                $query->where(
                                    'farmer_id',
                                    $farmer->id
                                );

                                $first =
                                    false;
                            } else {
                                $query->orWhere(
                                    'farmer_id',
                                    $farmer->id
                                );
                            }
                        }


                        if ($hasUserId) {

                            if ($first) {
                                $query->where(
                                    'user_id',
                                    $farmer->id
                                );
                            } else {
                                $query->orWhere(
                                    'user_id',
                                    $farmer->id
                                );
                            }
                        }
                    }
                )
                ->latest('created_at')
                ->paginate(10);


        return view(
            'farmer.milling.index',
            compact(
                'farmer',
                'requests'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function userAddress(
        User $user
    ): string {

        $address =
            trim(
                (string) (
                    $user->address
                    ?? $user->complete_address
                    ?? ''
                )
            );


        if ($address !== '') {
            return $address;
        }


        $parts =
            array_values(
                array_filter([
                    trim(
                        (string) (
                            $user->barangay
                            ?? ''
                        )
                    ),

                    trim(
                        (string) (
                            $user->municipality
                            ?? 'Allacapan'
                        )
                    ),

                    trim(
                        (string) (
                            $user->province
                            ?? 'Cagayan'
                        )
                    ),
                ])
            );


        return implode(
            ', ',
            array_unique(
                $parts
            )
        );
    }


    private function putIfColumnExists(
        array &$payload,
        string $column,
        mixed $value
    ): void {

        if (
            Schema::hasColumn(
                'milling_requests',
                $column
            )
        ) {
            $payload[$column] =
                $value;
        }
    }


    private function putFirstExistingColumn(
        array &$payload,
        array $columns,
        mixed $value
    ): void {

        foreach (
            $columns as $column
        ) {

            if (
                Schema::hasColumn(
                    'milling_requests',
                    $column
                )
            ) {
                $payload[$column] =
                    $value;

                return;
            }
        }
    }
}