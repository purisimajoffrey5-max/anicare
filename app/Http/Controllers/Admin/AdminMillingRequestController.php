<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MillingRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Services\VatService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class AdminMillingRequestController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MILLING + TRANSPORT PRICING
    |--------------------------------------------------------------------------
    |
    | Edit these constants anytime if the LGU/Miller changes the rates.
    |
    | Shipping is charged ONLY when the Miller picks up the Palay.
    | If Admin delivers it to the Miller, system shipping fee is PHP 0.
    |
    */

    private const SACK_KILOS = 60.0;

    // System-generated INITIAL milling estimate.
    // Miller can still update the fee when setting the final schedule.
    private const DEFAULT_MILLING_FEE_PER_KG = 2.50;

    // Pickup shipping formula:
    // PHP 50 base + PHP 10 per straight-line kilometer.
    private const PICKUP_BASE_FEE = 50.00;
    private const PICKUP_RATE_PER_KM = 10.00;

    /*
    |--------------------------------------------------------------------------
    | REQUIRE ADMIN
    |--------------------------------------------------------------------------
    */

    private function requireAdmin(): User
    {
        $user = Auth::user();

        if (
            !$user ||
            strtolower((string) ($user->role ?? '')) !== 'admin'
        ) {
            abort(403, 'Unauthorized');
        }

        return $user;
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - REQUEST MILLING FORM
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $user = $this->requireAdmin();

        /*
         * Only OPEN millers are shown for new requests.
         */
        $millers = User::query()
            ->whereRaw('LOWER(role) = ?', ['miller'])
            ->where('is_open', 1)
            ->orderBy('fullname')
            ->orderBy('username')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ADMIN ADDRESS -> COORDINATES
        |--------------------------------------------------------------------------
        |
        | Admin does NOT need latitude/longitude saved in the users table.
        | We geocode the registered address instead and cache the result.
        |
        */
        $adminAddress = $this->userAddress($user);

        $adminCoordinates =
            $this->geocodeAddress(
                $adminAddress
            );

        $vatEnabled = VatService::enabled();
        $vatRate = VatService::rate();

        return view(
            'admin.milling.create',
            compact(
                'user',
                'millers',
                'adminAddress',
                'adminCoordinates',
                'vatEnabled',
                'vatRate'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AJAX - RESOLVE SELECTED MILLER BARANGAY
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Shipping uses the Miller's BARANGAY, not users.latitude/longitude and
    | not the Miller's generic address field.
    |
    | Example:
    | Dagupan -> "Dagupan, Allacapan, Cagayan, Philippines"
    |
    | Only the selected Miller is geocoded. This avoids geocoding every
    | Miller when the page loads.
    |
    */

    public function millerLocation(int $id)
    {
        $this->requireAdmin();

        $miller = User::query()
            ->where('id', $id)
            ->whereRaw('LOWER(role) = ?', ['miller'])
            ->firstOrFail();

        $locationText =
            $this->millerBarangayLocation(
                $miller
            );

        if ($locationText === '') {
            return response()->json([
                'ok' => false,
                'message' =>
                    'The selected Miller has no Barangay saved.',
            ], 422);
        }

        $coordinates =
            $this->geocodeAddress(
                $locationText
            );

        if (!$coordinates) {
            return response()->json([
                'ok' => false,
                'message' =>
                    'Could not locate the selected Miller Barangay on the map: ' .
                    $locationText,
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'miller_id' => $miller->id,
            'barangay' => $miller->barangay,
            'location_text' => $locationText,
            'latitude' => $coordinates['lat'],
            'longitude' => $coordinates['lng'],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - SUBMIT MILLING REQUEST
    |--------------------------------------------------------------------------
    |
    | This is intentionally backward-compatible with the existing
    | milling_requests table.
    |
    | New identity fields:
    | requester_id   = logged-in Admin ID
    | requester_role = admin
    |
    | For older tables that still require farmer_id/user_id, the Admin ID is
    | also written there only as a compatibility value. The NEW workflow must
    | always use requester_id/requester_role to identify the real requester.
    |
    */

    public function store(Request $request)
    {
        $user = $this->requireAdmin();

        $data = $request->validate(
            [
                'miller_id' => [
                    'required',
                    'integer',
                    'exists:users,id',
                ],

                'quantity_kilos' => [
                    'required',
                    'numeric',
                    'min:1',
                ],

                'quantity_unit' => [
                    'required',
                    'in:kg,sack',
                ],

                'quantity_value' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'transport_type' => [
                    'required',
                    'in:delivery,pickup',
                ],

                'preferred_date' => [
                    'nullable',
                    'date',
                    'after_or_equal:today',
                ],

                'notes' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'miller_id.required' =>
                    'Please select an OPEN Miller.',

                'quantity_kilos.required' =>
                    'Please enter the Palay quantity.',

                'quantity_kilos.min' =>
                    'Quantity must be at least 1 kilogram.',

                'transport_type.required' =>
                    'Please choose whether Admin will deliver or the Miller will pick up the Palay.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | VERIFY SELECTED MILLER
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
                        'The selected Miller is currently CLOSED.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | PALAY ONLY
        |--------------------------------------------------------------------------
        */

        $productType = 'Palay';

        $quantityKilos =
            round(
                (float) $data['quantity_kilos'],
                2
            );

        $quantityUnit =
            $data['quantity_unit'];

        $quantityValue =
            round(
                (float) $data['quantity_value'],
                2
            );


        /*
        |--------------------------------------------------------------------------
        | ADMIN REGISTERED ADDRESS
        |--------------------------------------------------------------------------
        */

        $adminAddress =
            $this->userAddress(
                $user
            );


        /*
        |--------------------------------------------------------------------------
        | TRANSPORT + HIDDEN LEAFLET SHIPPING
        |--------------------------------------------------------------------------
        |
        | Browser uses hidden Leaflet for LIVE preview.
        | Server calculates the distance again so hidden fields cannot be
        | manipulated by the browser.
        |
        | delivery = Admin brings Palay to Miller -> PHP 0 system shipping.
        | pickup   = Miller picks up from Admin -> shipping fee applies.
        |
        */

        $transportType =
            $data['transport_type'];

        /*
         * ADMIN: use saved coordinates if available; otherwise geocode the
         * registered address. This is the main fix for Admin accounts that
         * have an address but NULL latitude/longitude.
         */
        $adminCoordinates =
            (
                is_numeric($user->latitude ?? null) &&
                is_numeric($user->longitude ?? null)
            )
                ? [
                    'lat' => (float) $user->latitude,
                    'lng' => (float) $user->longitude,
                  ]
                : $this->geocodeAddress($adminAddress);

        $requesterLat =
            $adminCoordinates['lat']
            ?? null;

        $requesterLng =
            $adminCoordinates['lng']
            ?? null;

        /*
         * MILLER SHIPPING LOCATION:
         *
         * USE THE MILLER'S BARANGAY.
         *
         * We intentionally DO NOT depend on users.latitude/users.longitude.
         * We also do not use the Miller's generic address as the primary
         * shipping point.
         *
         * Example:
         * barangay = Dagupan
         * query    = Dagupan, Allacapan, Cagayan, Philippines
         */
        $millerAddress =
            $this->millerBarangayLocation(
                $miller
            );

        $millerCoordinates =
            $this->geocodeAddress(
                $millerAddress
            );

        $millerLat =
            $millerCoordinates['lat']
            ?? null;

        $millerLng =
            $millerCoordinates['lng']
            ?? null;

        $distanceKm = null;
        $shippingFee = 0.00;


        if (
            $requesterLat !== null &&
            $requesterLng !== null &&
            $millerLat !== null &&
            $millerLng !== null
        ) {
            $distanceKm =
                $this->distanceKm(
                    $requesterLat,
                    $requesterLng,
                    $millerLat,
                    $millerLng
                );
        }


        if ($transportType === 'pickup') {

            if ($adminAddress === '') {
                return back()
                    ->withErrors([
                        'transport_type' =>
                            'Pickup requires the Admin registered address.',
                    ])
                    ->withInput();
            }

            if ($distanceKm === null) {
                return back()
                    ->withErrors([
                        'transport_type' =>
                            'Pickup shipping cannot be calculated because the Admin or selected Miller registered address could not be located on the map.',
                    ])
                    ->withInput();
            }

            $shippingFee =
                round(
                    self::PICKUP_BASE_FEE +
                    (
                        $distanceKm *
                        self::PICKUP_RATE_PER_KM
                    ),
                    2
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SYSTEM-GENERATED INITIAL MILLING FEE
        |--------------------------------------------------------------------------
        |
        | This creates an immediate estimate + invoice.
        | Miller may update milling_fee_per_kg later when setting schedule.
        |
        */

        $millingFeePerKg =
            self::DEFAULT_MILLING_FEE_PER_KG;

        $millingTotal =
            round(
                $quantityKilos *
                $millingFeePerKg,
                2
            );

        $grandTotal =
            round(
                $millingTotal +
                $shippingFee,
                2
            );

        $vat = VatService::breakdown($grandTotal);


        try {

            $millingRequest = DB::transaction(
                function () use (
                    $data,
                    $user,
                    $miller,
                    $productType,
                    $quantityKilos,
                    $quantityUnit,
                    $quantityValue,
                    $transportType,
                    $adminAddress,
                    $requesterLat,
                    $requesterLng,
                    $millerLat,
                    $millerLng,
                    $distanceKm,
                    $shippingFee,
                    $millingFeePerKg,
                    $millingTotal,
                    $grandTotal,
                    $vat
                ) {

                    $payload = [];


                    /*
                    |--------------------------------------------------------------------------
                    | REQUESTER
                    |--------------------------------------------------------------------------
                    */

                    $this->putIfColumnExists(
                        $payload,
                        'requester_id',
                        $user->id
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_role',
                        'admin'
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_name_snapshot',
                        trim((string) ($user->fullname ?? $user->username ?? 'Admin #'.$user->id))
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_username_snapshot',
                        trim((string) ($user->username ?? ''))
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_contact_snapshot',
                        trim((string) (
                            $user->mobile_number
                            ?? $user->contact_number
                            ?? $user->phone
                            ?? ''
                        ))
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_address_snapshot',
                        $adminAddress
                    );

                    // Backward compatibility.
                    $this->putIfColumnExists(
                        $payload,
                        'farmer_id',
                        $user->id
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'user_id',
                        $user->id
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'miller_id',
                        $miller->id
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | PALAY + QUANTITY
                    |--------------------------------------------------------------------------
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
                        $productType
                    );

                    $this->putFirstExistingColumn(
                        $payload,
                        [
                            'quantity_kilos',
                            'quantity_kg',
                            'kilos',
                            'quantity',
                            'weight_kg',
                        ],
                        $quantityKilos
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'quantity_unit',
                        $quantityUnit
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'quantity_value',
                        $quantityValue
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | TRANSPORT
                    |--------------------------------------------------------------------------
                    */

                    $this->putIfColumnExists(
                        $payload,
                        'transport_type',
                        $transportType
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'pickup_address',
                        $transportType === 'pickup'
                            ? $adminAddress
                            : null
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_latitude',
                        $requesterLat
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'requester_longitude',
                        $requesterLng
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'miller_latitude',
                        $millerLat
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'miller_longitude',
                        $millerLng
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'shipping_distance_km',
                        $distanceKm
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'shipping_fee',
                        $shippingFee
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | COST
                    |--------------------------------------------------------------------------
                    */

                    $this->putIfColumnExists(
                        $payload,
                        'milling_fee_per_kg',
                        $millingFeePerKg
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'total_amount',
                        $millingTotal
                    );

                    $this->putIfColumnExists(
                        $payload,
                        'grand_total',
                        $grandTotal
                    );

                    $this->putIfColumnExists($payload, 'vat_enabled', $vat['enabled']);
                    $this->putIfColumnExists($payload, 'vat_rate', $vat['rate']);
                    $this->putIfColumnExists($payload, 'vatable_sales', $vat['vatable_sales']);
                    $this->putIfColumnExists($payload, 'vat_amount', $vat['vat']);
                    $this->putIfColumnExists($payload, 'total_sales', $vat['total_sales']);


                    /*
                    |--------------------------------------------------------------------------
                    | OPTIONAL REQUEST DETAILS
                    |--------------------------------------------------------------------------
                    */

                    if (!empty($data['preferred_date'])) {
                        $this->putFirstExistingColumn(
                            $payload,
                            [
                                'preferred_date',
                                'requested_date',
                                'request_date',
                            ],
                            $data['preferred_date']
                        );
                    }

                    if (!empty($data['notes'])) {
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
                    | INITIAL STATE
                    |--------------------------------------------------------------------------
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


                    if (!array_key_exists('miller_id', $payload)) {
                        throw ValidationException::withMessages([
                            'miller_id' =>
                                'The milling_requests table is missing miller_id.',
                        ]);
                    }

                    if (!array_key_exists('requester_id', $payload)) {
                        throw ValidationException::withMessages([
                            'requester_id' =>
                                'The requester_id column is missing. Run the milling workflow migration first.',
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
        |--------------------------------------------------------------------------
        | OPEN INVOICE AFTER REQUEST
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.milling.invoice.show',
                $millingRequest->id
            )
            ->with(
                'success',
                "Milling Request #{$millingRequest->id} submitted. Review the generated invoice below."
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - MY MILLING REQUESTS
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = $this->requireAdmin();

        $requests = MillingRequest::with([
                'miller',
                'requester',
            ])
            ->where(
                'requester_id',
                $user->id
            )
            ->where(
                'requester_role',
                'admin'
            )
            ->latest()
            ->paginate(10);

        return view(
            'admin.milling.index',
            compact(
                'user',
                'requests'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - VIEW ONE MILLING REQUEST
    |--------------------------------------------------------------------------
    */

    public function show(int $id)
    {
        $user = $this->requireAdmin();

        $millingRequest = MillingRequest::with([
                'miller',
                'requester',
            ])
            ->where('id', $id)
            ->where(
                'requester_id',
                $user->id
            )
            ->where(
                'requester_role',
                'admin'
            )
            ->firstOrFail();

        return view(
            'admin.milling.show',
            [
                'user' =>
                    $user,

                'millingRequest' =>
                    $millingRequest,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - CANCEL REQUEST
    |--------------------------------------------------------------------------
    |
    | The requester may cancel only before Miller processing begins.
    |
    */

    public function cancel(int $id)
    {
        $user = $this->requireAdmin();

        try {

            DB::transaction(function () use (
                $id,
                $user
            ) {

                $millingRequest =
                    MillingRequest::where(
                        'id',
                        $id
                    )
                    ->where(
                        'requester_id',
                        $user->id
                    )
                    ->where(
                        'requester_role',
                        'admin'
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                $status = strtolower(
                    (string) (
                        $millingRequest->status
                        ?? 'pending'
                    )
                );

                if ($status === 'cancelled') {
                    throw ValidationException::withMessages([
                        'request' =>
                            'This milling request is already cancelled.',
                    ]);
                }

                if ($status === 'completed') {
                    throw ValidationException::withMessages([
                        'request' =>
                            'A completed milling transaction cannot be cancelled.',
                    ]);
                }

                if (
                    in_array(
                        $status,
                        [
                            'in_progress',
                            'finished',
                        ],
                        true
                    )
                ) {
                    throw ValidationException::withMessages([
                        'request' =>
                            'This milling request can no longer be cancelled because processing has already started.',
                    ]);
                }

                $millingRequest->status =
                    'cancelled';

                $millingRequest->save();
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                );
        }

        return redirect()
            ->route('admin.milling.index')
            ->with(
                'success',
                'Milling request cancelled successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - CONFIRM MILLING COMPLETED
    |--------------------------------------------------------------------------
    |
    | Equivalent to the buyer's "I Received My Order".
    |
    | Miller:
    | finished + proof
    |
    | Admin requester:
    | confirms
    |
    | Final:
    | completed
    |
    */

    public function confirmCompleted(int $id)
    {
        $user = $this->requireAdmin();

        try {

            DB::transaction(function () use (
                $id,
                $user
            ) {

                $millingRequest =
                    MillingRequest::where(
                        'id',
                        $id
                    )
                    ->where(
                        'requester_id',
                        $user->id
                    )
                    ->where(
                        'requester_role',
                        'admin'
                    )
                    ->lockForUpdate()
                    ->firstOrFail();

                $status = strtolower(
                    (string) (
                        $millingRequest->status
                        ?? 'pending'
                    )
                );

                if ($status === 'completed') {
                    throw ValidationException::withMessages([
                        'request' =>
                            'This milling transaction is already completed.',
                    ]);
                }

                if ($status !== 'finished') {
                    throw ValidationException::withMessages([
                        'request' =>
                            'You can confirm completion only after the Miller marks the milling job as FINISHED.',
                    ]);
                }

                if (
                    empty(
                        $millingRequest->proof_of_milling_path
                    )
                ) {
                    throw ValidationException::withMessages([
                        'request' =>
                            'Proof of milling is required before confirming completion.',
                    ]);
                }


                /*
                 * PAYMENT GATE:
                 * The requester cannot finalize the transaction while the
                 * Miller still reports UNPAID.
                 */
                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'payment_status'
                    ) &&
                    strtolower(
                        (string) (
                            $millingRequest->payment_status
                            ?? 'unpaid'
                        )
                    ) !== 'paid'
                ) {
                    throw ValidationException::withMessages([
                        'payment' =>
                            'The Miller has not confirmed the payment yet. The transaction cannot be completed while payment status is UNPAID.',
                    ]);
                }


                $millingRequest->status =
                    'completed';

                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'requester_confirmed_at'
                    )
                ) {
                    $millingRequest
                        ->requester_confirmed_at =
                        now();
                }

                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'completed_at'
                    )
                ) {
                    $millingRequest
                        ->completed_at =
                        now();
                }

                $millingRequest->save();
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                );
        }

        return redirect()
            ->route('admin.milling.index')
            ->with(
                'success',
                'Milling transaction confirmed as completed.'
            );
    }



    /*
    |--------------------------------------------------------------------------
    | ADMIN - VIEW MILLING INVOICE
    |--------------------------------------------------------------------------
    */

    public function invoiceShow(int $id)
    {
        $user = $this->requireAdmin();

        $millingRequest = MillingRequest::with([
                'miller',
                'requester',
            ])
            ->where('id', $id)
            ->where('requester_id', $user->id)
            ->where('requester_role', 'admin')
            ->firstOrFail();

        return view(
            'admin.milling.invoice',
            [
                'invoice' =>
                    $this->buildInvoiceData(
                        $millingRequest
                    ),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - DOWNLOAD MILLING INVOICE PDF
    |--------------------------------------------------------------------------
    */

    public function invoiceDownload(int $id)
    {
        $user = $this->requireAdmin();

        $millingRequest = MillingRequest::with([
                'miller',
                'requester',
            ])
            ->where('id', $id)
            ->where('requester_id', $user->id)
            ->where('requester_role', 'admin')
            ->firstOrFail();

        $invoice =
            $this->buildInvoiceData(
                $millingRequest
            );

        $pdf = Pdf::loadView(
            'admin.milling.invoice_pdf',
            compact('invoice')
        )->setPaper(
            'a4',
            'portrait'
        );

        return $pdf->download(
            $invoice['invoice_number'] .
            '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD MILLING INVOICE DATA
    |--------------------------------------------------------------------------
    */

    private function buildInvoiceData(
        MillingRequest $millingRequest
    ): array {

        $requester =
            $millingRequest->requester;

        $miller =
            $millingRequest->miller;

        $createdAt =
            \Carbon\Carbon::parse(
                $millingRequest->created_at
                ?? now()
            );

        $quantity =
            (float) (
                $millingRequest->quantity_kilos
                ?? $millingRequest->quantity_kg
                ?? $millingRequest->kilos
                ?? $millingRequest->quantity
                ?? $millingRequest->weight_kg
                ?? 0
            );

        $quantityUnit =
            strtolower(
                (string) (
                    $millingRequest->quantity_unit
                    ?? 'kg'
                )
            );

        $quantityValue =
            (float) (
                $millingRequest->quantity_value
                ?? (
                    $quantityUnit === 'sack'
                        ? $quantity / self::SACK_KILOS
                        : $quantity
                )
            );

        $feePerKg =
            (float) (
                $millingRequest->milling_fee_per_kg
                ?? self::DEFAULT_MILLING_FEE_PER_KG
            );

        $millingTotal =
            (float) (
                $millingRequest->total_amount
                ?? (
                    $quantity *
                    $feePerKg
                )
            );

        $shippingFee =
            (float) (
                $millingRequest->shipping_fee
                ?? 0
            );

        $grandTotal =
            (float) (
                $millingRequest->grand_total
                ?? (
                    $millingTotal +
                    $shippingFee
                )
            );

        $transportType =
            strtolower(
                (string) (
                    $millingRequest->transport_type
                    ?? 'delivery'
                )
            );

        $notes =
            $millingRequest->notes
            ?? $millingRequest->remarks
            ?? $millingRequest->message
            ?? $millingRequest->details
            ?? null;

        return [
            'request_id' =>
                $millingRequest->id,

            'invoice_number' =>
                'MILL-' .
                $createdAt->format('Ymd') .
                '-' .
                str_pad(
                    (string) $millingRequest->id,
                    6,
                    '0',
                    STR_PAD_LEFT
                ),

            'date' =>
                $createdAt
                    ->copy()
                    ->timezone('Asia/Manila')
                    ->format('F d, Y'),

            'time' =>
                $createdAt
                    ->copy()
                    ->timezone('Asia/Manila')
                    ->format('h:i A'),

            'requester_name' =>
                $requester?->fullname
                ?? $requester?->username
                ?? 'Admin',

            'requester_address' =>
                $this->userAddress(
                    $requester
                ),

            'miller_name' =>
                $miller?->fullname
                ?? $miller?->username
                ?? 'Miller',

            'miller_address' =>
                $this->millerBarangayLocation(
                    $miller
                ),

            'product_type' =>
                'Palay',

            'quantity_kilos' =>
                $quantity,

            'quantity_unit' =>
                $quantityUnit,

            'quantity_value' =>
                $quantityValue,

            'quantity_display' =>
                $quantityUnit === 'sack'
                    ? number_format($quantityValue, 2) .
                        ' sack(s) / cavan'
                    : number_format($quantityValue, 2) .
                        ' kg',

            'milling_fee_per_kg' =>
                $feePerKg,

            'milling_total' =>
                $millingTotal,

            // Backward-compatible invoice key.
            'total_amount' =>
                $millingTotal,

            'transport_type' =>
                $transportType,

            'pickup_address' =>
                $millingRequest->pickup_address
                ?? null,

            'shipping_distance_km' =>
                $millingRequest->shipping_distance_km !== null
                    ? (float) $millingRequest->shipping_distance_km
                    : null,

            'shipping_fee' =>
                $shippingFee,

            'grand_total' =>
                $grandTotal,

            'preferred_date' =>
                !empty($millingRequest->preferred_date)
                    ? \Carbon\Carbon::parse(
                        $millingRequest->preferred_date
                    )->format('F d, Y')
                    : null,

            'scheduled_at' =>
                !empty($millingRequest->scheduled_at)
                    ? \Carbon\Carbon::parse(
                        $millingRequest->scheduled_at
                    )
                        ->timezone('Asia/Manila')
                        ->format('F d, Y h:i A')
                    : null,

            'payment_status' =>
                strtolower(
                    (string) (
                        $millingRequest->payment_status
                        ?? 'unpaid'
                    )
                ),

            'status' =>
                strtolower(
                    (string) (
                        $millingRequest->status
                        ?? 'pending'
                    )
                ),

            'fee_is_estimate' =>
                in_array(
                    strtolower(
                        (string) (
                            $millingRequest->status
                            ?? 'pending'
                        )
                    ),
                    [
                        'pending',
                        'assigned',
                        'accepted',
                    ],
                    true
                ),

            'notes' =>
                $notes,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER: USER ADDRESS
    |--------------------------------------------------------------------------
    */

    private function userAddress(?User $user): string
    {
        if (!$user) {
            return '';
        }

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


    /*
    |--------------------------------------------------------------------------
    | HELPER: MILLER BARANGAY -> SHIPPING LOCATION
    |--------------------------------------------------------------------------
    |
    | The Miller shipping point is based on users.barangay.
    |
    | Example:
    | users.barangay = "Dagupan"
    | result         = "Dagupan, Allacapan, Cagayan, Philippines"
    |
    */

    private function millerBarangayLocation(?User $miller): string
    {
        if (!$miller) {
            return '';
        }

        $barangay =
            trim(
                (string) (
                    $miller->barangay
                    ?? ''
                )
            );

        if ($barangay === '') {
            return '';
        }

        $municipality =
            trim(
                (string) (
                    $miller->municipality
                    ?? 'Allacapan'
                )
            );

        if ($municipality === '') {
            $municipality = 'Allacapan';
        }

        $province =
            trim(
                (string) (
                    $miller->province
                    ?? 'Cagayan'
                )
            );

        if ($province === '') {
            $province = 'Cagayan';
        }

        return implode(
            ', ',
            array_values(
                array_unique([
                    $barangay,
                    $municipality,
                    $province,
                    'Philippines',
                ])
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER: ADDRESS -> COORDINATES
    |--------------------------------------------------------------------------
    |
    | Uses the public OpenStreetMap Nominatim Search API only for light,
    | user-triggered geocoding. Results are cached for 30 days so the same
    | address is not repeatedly sent to the service.
    |
    */

    private function geocodeAddress(string $address): ?array
    {
        $address =
            trim(
                preg_replace(
                    '/\\s+/',
                    ' ',
                    $address
                )
            );

        if ($address === '') {
            return null;
        }

        $cacheKey =
            'anicare:nominatim:' .
            sha1(
                mb_strtolower(
                    $address
                )
            );

        return Cache::remember(
            $cacheKey,
            now()->addDays(30),
            function () use ($address) {

                /*
                 * Keep uncached requests serialized and at least ~1 second
                 * apart to respect the public Nominatim usage limit.
                 */
                return Cache::lock(
                    'anicare:nominatim-request-lock',
                    10
                )->block(
                    8,
                    function () use ($address) {

                        $lastRequestAt =
                            (float) Cache::get(
                                'anicare:nominatim-last-request-at',
                                0
                            );

                        $elapsed =
                            microtime(true) -
                            $lastRequestAt;

                        if ($elapsed < 1.05) {
                            usleep(
                                (int) (
                                    (1.05 - $elapsed) *
                                    1000000
                                )
                            );
                        }

                        try {

                            $response =
                                Http::timeout(8)
                                    ->acceptJson()
                                    ->withHeaders([
                                        'User-Agent' =>
                                            'ANI-CARE-Allacapan/1.0',
                                    ])
                                    ->get(
                                        'https://nominatim.openstreetmap.org/search',
                                        [
                                            'q' => $address,
                                            'format' => 'jsonv2',
                                            'limit' => 1,
                                            'countrycodes' => 'ph',
                                        ]
                                    );

                            Cache::put(
                                'anicare:nominatim-last-request-at',
                                microtime(true),
                                now()->addHour()
                            );

                            if (!$response->successful()) {
                                return null;
                            }

                            $first =
                                $response->json(0);

                            if (
                                !is_array($first) ||
                                !isset(
                                    $first['lat'],
                                    $first['lon']
                                )
                            ) {
                                return null;
                            }

                            return [
                                'lat' => (float) $first['lat'],
                                'lng' => (float) $first['lon'],
                                'display_name' =>
                                    $first['display_name']
                                    ?? $address,
                            ];

                        } catch (\Throwable $e) {
                            report($e);
                            return null;
                        }
                    }
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER: SERVER-SIDE DISTANCE
    |--------------------------------------------------------------------------
    |
    | Same great-circle concept used by Leaflet's LatLng.distanceTo().
    |
    */

    private function distanceKm(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {

        $earthRadiusKm = 6371.0088;

        $dLat =
            deg2rad(
                $lat2 -
                $lat1
            );

        $dLng =
            deg2rad(
                $lng2 -
                $lng1
            );

        $a =
            sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLng / 2) ** 2;

        $c =
            2 *
            atan2(
                sqrt($a),
                sqrt(1 - $a)
            );

        return round(
            $earthRadiusKm *
            $c,
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER: PUT IF COLUMN EXISTS
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | HELPER: FIRST EXISTING COLUMN
    |--------------------------------------------------------------------------
    */

    private function putFirstExistingColumn(
        array &$payload,
        array $columns,
        mixed $value
    ): ?string {
        foreach ($columns as $column) {

            if (
                Schema::hasColumn(
                    'milling_requests',
                    $column
                )
            ) {
                $payload[$column] =
                    $value;

                return $column;
            }
        }

        return null;
    }
}