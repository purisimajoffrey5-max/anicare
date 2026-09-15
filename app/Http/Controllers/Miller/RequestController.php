<?php

namespace App\Http\Controllers\Miller;

use App\Http\Controllers\Controller;
use App\Models\MillingRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\VatService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class RequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REQUIRE MILLER
    |--------------------------------------------------------------------------
    */

    private function requireMiller(): void
    {
        $user = Auth::user();

        if (
            !$user ||
            strtolower((string) ($user->role ?? '')) !== 'miller'
        ) {
            abort(403, 'Unauthorized');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | REQUEST LIST
    |--------------------------------------------------------------------------
    |
    | NEW REQUESTS:
    | Only requests selected for the logged-in Miller are shown.
    |
    | LEGACY:
    | Old unassigned PENDING requests are still visible so your existing
    | records do not disappear while we finish upgrading the Farmer side.
    |
    */

    public function index(Request $request)
    {
        $this->requireMiller();

        $millerId =
            Auth::id();

        $statusParam =
            strtolower(
                trim(
                    (string) $request->get(
                        'status',
                        'all'
                    )
                )
            );

        $q = MillingRequest::with([
                'requester',
                'farmer',
                'miller',
            ])
            ->where(function ($query) use ($millerId) {

                /*
                 * Current transaction:
                 * requester explicitly selected this Miller.
                 */
                $query->where(
                    'miller_id',
                    $millerId
                );

                /*
                 * Temporary compatibility for old records which were created
                 * without a selected Miller.
                 */
                $query->orWhere(function ($legacy) {

                    $legacy
                        ->whereNull('miller_id')
                        ->whereIn(
                            'status',
                            [
                                'pending',
                                'assigned',
                            ]
                        );
                });
            })
            ->orderByDesc('created_at');


        if (
            $statusParam !== '' &&
            $statusParam !== 'all'
        ) {

            if (
                str_contains(
                    $statusParam,
                    ','
                )
            ) {

                $statuses =
                    array_values(
                        array_filter(
                            array_map(
                                'trim',
                                explode(
                                    ',',
                                    $statusParam
                                )
                            )
                        )
                    );

                if (!empty($statuses)) {
                    $q->whereIn(
                        'status',
                        $statuses
                    );
                }

            } else {

                $q->where(
                    'status',
                    $statusParam
                );
            }
        }


        $requests =
            $q->paginate(10)
                ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | REQUESTER PROFILE MAP
        |--------------------------------------------------------------------------
        |
        | Resolve the REAL requester from:
        |
        | requester_id -> farmer_id -> user_id
        |
        | This fixes legacy records that previously appeared as User #ID / Requester
        | even though the account already has fullname, role, contact and address.
        |
        */

        $requesterIds =
            $requests
                ->getCollection()
                ->map(function ($millingRequest) {
                    return (int) (
                        $millingRequest->requester_id
                        ?? $millingRequest->farmer_id
                        ?? $millingRequest->user_id
                        ?? 0
                    );
                })
                ->filter()
                ->unique()
                ->values();


        $profileColumns = ['id'];

        foreach (
            [
                'fullname',
                'username',
                'email',
                'role',
                'mobile_number',
                'contact_number',
                'phone',
                'address',
                'barangay',
                'municipality',
                'province',
            ] as $column
        ) {
            if (Schema::hasColumn('users', $column)) {
                $profileColumns[] = $column;
            }
        }


        $requesterProfiles =
            User::query()
                ->whereIn('id', $requesterIds)
                ->get($profileColumns)
                ->mapWithKeys(function (User $user) {

                    $name = trim((string) (
                        $user->fullname
                        ?? $user->username
                        ?? $user->email
                        ?? 'User #'.$user->id
                    ));

                    $contact = trim((string) (
                        $user->mobile_number
                        ?? $user->contact_number
                        ?? $user->phone
                        ?? ''
                    ));

                    $address = trim((string) ($user->address ?? ''));

                    if ($address === '') {
                        $parts = array_values(array_filter([
                            trim((string) ($user->barangay ?? '')),
                            trim((string) ($user->municipality ?? 'Allacapan')),
                            trim((string) ($user->province ?? 'Cagayan')),
                        ]));

                        $address = implode(', ', array_unique($parts));
                    }

                    return [
                        (int) $user->id => [
                            'id' => (int) $user->id,
                            'name' => $name,
                            'username' => trim((string) ($user->username ?? '')),
                            'role' => strtolower(trim((string) ($user->role ?? ''))),
                            'contact' => $contact,
                            'address' => $address,
                        ],
                    ];
                })
                ->all();


        $requesterContacts = [];

        foreach ($requesterProfiles as $id => $profile) {
            $requesterContacts[(int) $id] = $profile['contact'] ?? '';
        }


        $status =
            $statusParam ?: 'all';


        return view(
            'miller.requests',
            compact(
                'requests',
                'status',
                'requesterProfiles',
                'requesterContacts'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCEPT
    |--------------------------------------------------------------------------
    |
    | PENDING / ASSIGNED
    |      ↓
    | ACCEPTED
    |
    */

    public function accept(int $id)
    {
        $this->requireMiller();

        try {

            DB::transaction(function () use ($id) {

                $mr =
                    MillingRequest::lockForUpdate()
                        ->findOrFail($id);

                $currentMillerId =
                    Auth::id();

                $status =
                    strtolower(
                        (string) (
                            $mr->status
                            ?? 'pending'
                        )
                    );


                if (
                    !in_array(
                        $status,
                        [
                            'pending',
                            'assigned',
                        ],
                        true
                    )
                ) {
                    throw ValidationException::withMessages([
                        'request' =>
                            'Only PENDING or ASSIGNED requests can be accepted.',
                    ]);
                }


                /*
                 * If another Miller is already selected, this Miller cannot
                 * take the request.
                 */
                if (
                    !empty($mr->miller_id) &&
                    (int) $mr->miller_id !==
                        (int) $currentMillerId
                ) {
                    throw ValidationException::withMessages([
                        'request' =>
                            'This milling request belongs to another Miller.',
                    ]);
                }


                $mr->miller_id =
                    $currentMillerId;

                $mr->status =
                    'accepted';

                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'payment_status'
                    ) &&
                    empty($mr->payment_status)
                ) {
                    $mr->payment_status =
                        'unpaid';
                }

                $mr->save();
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                );
        }


        return back()
            ->with(
                'success',
                'Milling request accepted. Set the milling fee and schedule next.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LEGACY APPROVE BUTTON
    |--------------------------------------------------------------------------
    |
    | Old Blade files may still call:
    | miller.requests.approve
    |
    | Keep it working by routing it to the new ACCEPT workflow.
    |
    */

    public function approve(int $id)
    {
        return $this->accept($id);
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(int $id)
    {
        $this->requireMiller();

        try {

            DB::transaction(function () use ($id) {

                $mr =
                    MillingRequest::lockForUpdate()
                        ->findOrFail($id);

                $currentMillerId =
                    Auth::id();

                $status =
                    strtolower(
                        (string) (
                            $mr->status
                            ?? 'pending'
                        )
                    );


                if (
                    !in_array(
                        $status,
                        [
                            'pending',
                            'assigned',
                        ],
                        true
                    )
                ) {
                    throw ValidationException::withMessages([
                        'request' =>
                            'Only PENDING or ASSIGNED requests can be rejected.',
                    ]);
                }


                if (
                    !empty($mr->miller_id) &&
                    (int) $mr->miller_id !==
                        (int) $currentMillerId
                ) {
                    throw ValidationException::withMessages([
                        'request' =>
                            'This milling request belongs to another Miller.',
                    ]);
                }


                /*
                 * Assign this legacy request to the current Miller before
                 * rejecting so the transaction history identifies who acted.
                 */
                if (empty($mr->miller_id)) {
                    $mr->miller_id =
                        $currentMillerId;
                }

                $mr->status =
                    'rejected';

                $mr->save();
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                );
        }


        return back()
            ->with(
                'success',
                'Milling request rejected.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SET FEE + SCHEDULE
    |--------------------------------------------------------------------------
    |
    | ACCEPTED / SCHEDULED
    |      ↓
    | Miller sets:
    | - fee per kg
    | - date and time
    |      ↓
    | SCHEDULED
    |
    */

    public function setSchedule(
        Request $request,
        int $id
    ) {
        $this->requireMiller();

        $data =
            $request->validate([
                'scheduled_at' => [
                    'required',
                    'date',
                ],

                'milling_fee_per_kg' => [
                    'required',
                    'numeric',
                    'min:0',
                ],
            ]);


        $scheduleLocal =
            Carbon::parse(
                $data['scheduled_at'],
                'Asia/Manila'
            );

        if (
            $scheduleLocal->lessThanOrEqualTo(
                now('Asia/Manila')
            )
        ) {
            return back()
                ->withErrors([
                    'scheduled_at' =>
                        'The milling schedule must be a future date and time.',
                ])
                ->withInput();
        }


        try {

            DB::transaction(function () use (
                $id,
                $data,
                $scheduleLocal
            ) {

                $mr =
                    $this->findMyRequestForUpdate(
                        $id
                    );

                $status =
                    strtolower(
                        (string) (
                            $mr->status
                            ?? ''
                        )
                    );


                if (
                    !in_array(
                        $status,
                        [
                            'accepted',
                            'scheduled',
                        ],
                        true
                    )
                ) {
                    throw ValidationException::withMessages([
                        'request' =>
                            'Accept the request before setting its milling schedule.',
                    ]);
                }


                $quantityKilos =
                    $this->quantityKilos(
                        $mr
                    );

                $feePerKg =
                    round(
                        (float) $data['milling_fee_per_kg'],
                        2
                    );

                $totalAmount =
                    round(
                        $quantityKilos *
                        $feePerKg,
                        2
                    );

                /*
                 * Keep the invoice grand total synchronized when the Miller
                 * changes the final milling rate.
                 */
                $shippingFee =
                    (float) (
                        $mr->shipping_fee
                        ?? 0
                    );

                $grandTotal =
                    round(
                        $totalAmount +
                        $shippingFee,
                        2
                    );


                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'scheduled_at'
                    )
                ) {
                    /*
                     * Browser date/time is Philippine local time.
                     * Store UTC in the database.
                     */
                    $mr->scheduled_at =
                        $scheduleLocal
                            ->copy()
                            ->utc();
                }


                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'milling_fee_per_kg'
                    )
                ) {
                    $mr->milling_fee_per_kg =
                        $feePerKg;
                }


                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'total_amount'
                    )
                ) {
                    $mr->total_amount =
                        $totalAmount;
                }

                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'grand_total'
                    )
                ) {
                    $mr->grand_total =
                        $grandTotal;
                }

                if (Schema::hasColumn('milling_requests', 'vat_enabled')) {
                    $mr->vat_enabled = $vat['enabled'];
                    $mr->vat_rate = $vat['rate'];
                    $mr->vatable_sales = $vat['vatable_sales'];
                    $mr->vat_amount = $vat['vat'];
                    $mr->total_sales = $vat['total_sales'];
                }


                $mr->status =
                    'scheduled';

                $mr->save();
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                )
                ->withInput();
        }


        return back()
            ->with(
                'success',
                'Milling schedule and fee saved successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MARK PAID - ONLY AFTER MILLING IS FINISHED
    |--------------------------------------------------------------------------
    |
    | NEW ANI-CARE WORKFLOW:
    |
    | SCHEDULED
    |    ↓
    | START MILLING
    |    ↓
    | IN PROGRESS
    |    ↓
    | FINISH MILLING + PROOF
    |    ↓
    | FINISHED
    |    ↓
    | MARK PAID
    |
    | COMPLETED is also accepted for compatibility in case the requester
    | confirms completion before the Miller records the payment.
    |
    */

    public function markPaid(int $id)
    {
        $this->requireMiller();

        try {

            DB::transaction(function () use ($id) {

                $mr =
                    $this->findMyRequestForUpdate(
                        $id
                    );

                $status =
                    strtolower(
                        (string) (
                            $mr->status
                            ?? ''
                        )
                    );


                /*
                 * Payment can be recorded only after the physical milling job
                 * has already been finished.
                 *
                 * FINISHED:
                 *   normal new workflow
                 *
                 * COMPLETED:
                 *   compatibility fallback if the requester confirms first
                 *   before the Miller records payment.
                 */
                if (
                    !in_array(
                        $status,
                        [
                            'finished',
                            'completed',
                        ],
                        true
                    )
                ) {
                    throw ValidationException::withMessages([
                        'request' =>
                            'Mark Paid becomes available only after the milling job is FINISHED.',
                    ]);
                }


                if (
                    !Schema::hasColumn(
                        'milling_requests',
                        'payment_status'
                    )
                ) {
                    throw ValidationException::withMessages([
                        'payment' =>
                            'payment_status column is missing. Run the milling workflow migration.',
                    ]);
                }


                if (
                    strtolower(
                        (string) (
                            $mr->payment_status
                            ?? 'unpaid'
                        )
                    ) === 'paid'
                ) {
                    throw ValidationException::withMessages([
                        'payment' =>
                            'Payment is already marked as PAID.',
                    ]);
                }


                $mr->payment_status =
                    'paid';


                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'paid_at'
                    )
                ) {
                    $mr->paid_at =
                        now();
                }


                $mr->save();
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                );
        }


        return back()
            ->with(
                'success',
                'Payment marked as received. The milling service is now PAID.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | START MILLING
    |--------------------------------------------------------------------------
    |
    | SCHEDULED
    |    ↓
    | IN PROGRESS
    |
    */

    public function startMilling(int $id)
    {
        $this->requireMiller();

        try {

            DB::transaction(function () use ($id) {

                $mr =
                    $this->findMyRequestForUpdate(
                        $id
                    );

                $status =
                    strtolower(
                        (string) (
                            $mr->status
                            ?? ''
                        )
                    );


                if ($status !== 'scheduled') {
                    throw ValidationException::withMessages([
                        'request' =>
                            'The request must be SCHEDULED before milling can start.',
                    ]);
                }


                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'scheduled_at'
                    ) &&
                    empty($mr->scheduled_at)
                ) {
                    throw ValidationException::withMessages([
                        'request' =>
                            'Set the milling schedule first.',
                    ]);
                }


                $mr->status =
                    'in_progress';


                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'started_at'
                    )
                ) {
                    $mr->started_at =
                        now();
                }


                $mr->save();
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                );
        }


        return back()
            ->with(
                'success',
                'Milling started. The requester will see the IN PROGRESS status.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FINISH MILLING + PROOF PHOTO
    |--------------------------------------------------------------------------
    |
    | IN PROGRESS
    |      ↓
    | Upload proof
    |      ↓
    | FINISHED
    |
    | IMPORTANT:
    | Miller DOES NOT set COMPLETED.
    | Farmer/Admin requester must confirm completion.
    |
    */

    public function finishMilling(
        Request $request,
        int $id
    ) {
        $this->requireMiller();

        $request->validate([
            'proof_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);


        try {

            DB::transaction(function () use (
                $request,
                $id
            ) {

                $mr =
                    $this->findMyRequestForUpdate(
                        $id
                    );

                $status =
                    strtolower(
                        (string) (
                            $mr->status
                            ?? ''
                        )
                    );


                if ($status !== 'in_progress') {
                    throw ValidationException::withMessages([
                        'request' =>
                            'The milling job must be IN PROGRESS before it can be marked FINISHED.',
                    ]);
                }


                if (
                    !Schema::hasColumn(
                        'milling_requests',
                        'proof_of_milling_path'
                    )
                ) {
                    throw ValidationException::withMessages([
                        'proof_photo' =>
                            'proof_of_milling_path column is missing. Run the milling workflow migration.',
                    ]);
                }


                /*
                 * Remove old proof if the request is being retried.
                 */
                if (
                    !empty(
                        $mr->proof_of_milling_path
                    ) &&
                    Storage::disk('public')
                        ->exists(
                            $mr->proof_of_milling_path
                        )
                ) {
                    Storage::disk('public')
                        ->delete(
                            $mr->proof_of_milling_path
                        );
                }


                $path =
                    $request
                        ->file('proof_photo')
                        ->store(
                            'milling-proofs',
                            'public'
                        );


                $mr->proof_of_milling_path =
                    $path;

                $mr->status =
                    'finished';


                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'finished_at'
                    )
                ) {
                    $mr->finished_at =
                        now();
                }


                $mr->save();
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                );
        }


        return back()
            ->with(
                'success',
                'Milling marked as FINISHED and proof uploaded. The requester was notified. You may now record payment using Mark Paid.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LEGACY COMPLETE BUTTON
    |--------------------------------------------------------------------------
    |
    | Old code allowed the Miller to immediately set COMPLETED and create
    | inventory. That no longer matches the transaction workflow.
    |
    | Completion now belongs to the requester:
    |
    | Farmer/Admin -> I Confirm Milling Completed -> COMPLETED
    |
    */

    public function complete(int $id)
    {
        $this->requireMiller();

        $mr =
            MillingRequest::findOrFail(
                $id
            );


        if (
            (int) ($mr->miller_id ?? 0) !==
            (int) Auth::id()
        ) {
            abort(
                403,
                'This milling request belongs to another Miller.'
            );
        }


        return back()
            ->withErrors([
                'request' =>
                    'The Miller can no longer finalize a request as COMPLETED. Finish the milling job and upload proof first; the Farmer/Admin requester must confirm completion.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FIND CURRENT MILLER REQUEST + LOCK
    |--------------------------------------------------------------------------
    */

    private function findMyRequestForUpdate(
        int $id
    ): MillingRequest {
        return MillingRequest::where(
                'id',
                $id
            )
            ->where(
                'miller_id',
                Auth::id()
            )
            ->lockForUpdate()
            ->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | GET REQUEST QUANTITY IN KG
    |--------------------------------------------------------------------------
    |
    | Supports both the new field and the old ANI-CARE field names.
    |
    */

    private function quantityKilos(
        MillingRequest $mr
    ): float {
        return (float) (
            $mr->quantity_kilos
            ?? $mr->quantity_kg
            ?? $mr->kilos
            ?? $mr->quantity
            ?? $mr->weight_kg
            ?? 0
        );
    }
}