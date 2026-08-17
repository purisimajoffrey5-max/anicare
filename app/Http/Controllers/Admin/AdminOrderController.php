<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RiceProduct;
use App\Services\OrderInvoiceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class AdminOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REQUIRE ADMIN
    |--------------------------------------------------------------------------
    */

    private function requireAdmin(): void
    {
        $user = Auth::user();

        if (!$user || strtolower((string) $user->role) !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }



    /*
    |--------------------------------------------------------------------------
    | ADMIN - MY ORDERS
    |--------------------------------------------------------------------------
    |
    | The current orders table uses resident_id as the buyer-user ID.
    | For Admin purchases, the logged-in Admin ID is stored in that column.
    |
    */

    public function index()
    {
        $this->requireAdmin();

        $user = Auth::user();

        $orders = Order::with([
                'product',
                'farmer',
            ])
            ->where('resident_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view(
            'admin.orders',
            compact('user', 'orders')
        );
    }



    /*
    |--------------------------------------------------------------------------
    | ADMIN - VIEW / TRACK ONE ORDER
    |--------------------------------------------------------------------------
    */

    public function show(int $id)
    {
        $this->requireAdmin();

        $user = Auth::user();

        $order = Order::with([
                'product',
                'farmer',
                'resident',
            ])
            ->where('id', $id)
            ->where('resident_id', $user->id)
            ->firstOrFail();

        $orderStatus = strtolower(
            (string) ($order->status ?? 'pending')
        );

        $deliveryStatus = strtolower(
            (string) ($order->delivery_status ?? 'pending')
        );

        $expectedDelivery = null;

        if (!empty($order->expected_delivery_at)) {
            try {
                $expectedDelivery = \Carbon\Carbon::parse(
                    $order->expected_delivery_at,
                    'UTC'
                )->timezone('Asia/Manila');
            } catch (\Throwable $e) {
                $expectedDelivery = null;
            }
        }

        $farmerLocation = null;
        $buyerLocation = null;

        if (
            $order->farmer &&
            $order->farmer->latitude !== null &&
            $order->farmer->longitude !== null
        ) {
            $farmerLocation = [
                'lat' => (float) $order->farmer->latitude,
                'lng' => (float) $order->farmer->longitude,
            ];
        }

        /*
         * Prefer the exact delivery coordinates saved during Admin checkout.
         */
        if (
            $order->delivery_latitude !== null &&
            $order->delivery_longitude !== null
        ) {
            $buyerLocation = [
                'lat' => (float) $order->delivery_latitude,
                'lng' => (float) $order->delivery_longitude,
            ];
        } elseif (
            $order->resident &&
            $order->resident->latitude !== null &&
            $order->resident->longitude !== null
        ) {
            $buyerLocation = [
                'lat' => (float) $order->resident->latitude,
                'lng' => (float) $order->resident->longitude,
            ];
        }

        $mapCenterLat =
            $farmerLocation['lat']
            ?? $buyerLocation['lat']
            ?? 18.2760;

        $mapCenterLng =
            $farmerLocation['lng']
            ?? $buyerLocation['lng']
            ?? 121.6440;

        $deliveryStage = match (true) {
            $orderStatus === 'cancelled' =>
                'CANCELLED',

            $orderStatus === 'completed' =>
                'COMPLETED',

            $deliveryStatus === 'delivered' =>
                'DELIVERED',

            $deliveryStatus === 'out_for_delivery' =>
                'OUT FOR DELIVERY',

            $orderStatus === 'approved' && $expectedDelivery !== null =>
                'SCHEDULED',

            $orderStatus === 'approved' =>
                'PREPARING',

            default =>
                strtoupper($orderStatus),
        };

        $deliveryNote = match (true) {
            $orderStatus === 'pending' =>
                'Your order is waiting for the farmer to approve it.',

            $orderStatus === 'cancelled' =>
                'This order was cancelled.',

            $orderStatus === 'completed' =>
                'Transaction completed. You confirmed that the order was received.',

            $deliveryStatus === 'delivered' =>
                'The seller marked this order as delivered. Confirm receipt only after you actually receive it.',

            $deliveryStatus === 'out_for_delivery' =>
                'The seller has dispatched your order. It is now OUT FOR DELIVERY.',

            $orderStatus === 'approved' && $expectedDelivery !== null =>
                'Your order is approved and being prepared. Expected delivery: ' .
                $expectedDelivery->format('F d, Y h:i A') .
                '.',

            $orderStatus === 'approved' =>
                'Your order is approved and is being prepared. The seller has not set an expected delivery schedule yet.',

            default =>
                'Track the current status of your order below.',
        };

        return view(
            'admin.order-show',
            compact(
                'order',
                'orderStatus',
                'deliveryStatus',
                'expectedDelivery',
                'farmerLocation',
                'buyerLocation',
                'mapCenterLat',
                'mapCenterLng',
                'deliveryStage',
                'deliveryNote'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN BUYER - CONFIRM RECEIVED
    |--------------------------------------------------------------------------
    */

    public function confirmReceived(int $id)
    {
        $this->requireAdmin();

        $user = Auth::user();

        try {

            DB::transaction(function () use ($id, $user) {

                $order = Order::where('id', $id)
                    ->where('resident_id', $user->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($order->status === 'completed') {
                    throw ValidationException::withMessages([
                        'order' =>
                            'This order has already been marked as received.',
                    ]);
                }

                if ($order->status === 'cancelled') {
                    throw ValidationException::withMessages([
                        'order' =>
                            'A cancelled order cannot be marked as received.',
                    ]);
                }

                if ($order->status !== 'approved') {
                    throw ValidationException::withMessages([
                        'order' =>
                            'This order is not ready for buyer confirmation.',
                    ]);
                }

                if (
                    strtolower(
                        (string) ($order->delivery_status ?? 'pending')
                    ) !== 'delivered'
                ) {
                    throw ValidationException::withMessages([
                        'order' =>
                            'You can confirm receipt only after the seller marks the order as DELIVERED.',
                    ]);
                }

                if (empty($order->proof_of_delivery_path)) {
                    throw ValidationException::withMessages([
                        'order' =>
                            'Proof of delivery is required before you can confirm receipt.',
                    ]);
                }

                $order->status = 'completed';

                if (
                    Schema::hasColumn(
                        'orders',
                        'buyer_confirmed_at'
                    )
                ) {
                    $order->buyer_confirmed_at = now();
                }

                $order->save();
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors($e->errors());
        }

        return redirect()
            ->route('admin.orders.index')
            ->with(
                'success',
                'Order confirmed as received. Transaction completed.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - VIEW INVOICE
    |--------------------------------------------------------------------------
    */

    public function invoiceShow(int $order)
    {
        $orderModel =
            $this->getAdminOrder($order);

        return view(
            'admin.orders.invoice',
            [
                'invoice' =>
                    $this->invoiceData($orderModel),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - DOWNLOAD INVOICE PDF
    |--------------------------------------------------------------------------
    */

    public function invoiceDownload(int $order)
    {
        $orderModel =
            $this->getAdminOrder($order);

        $invoice =
            $this->invoiceData($orderModel);

        $pdf = Pdf::loadView(
            'admin.orders.invoice_pdf',
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
    | GET ADMIN'S OWN ORDER
    |--------------------------------------------------------------------------
    */

    private function getAdminOrder(int $id): Order
    {
        $this->requireAdmin();

        $user = Auth::user();

        return Order::with([
                'product',
                'farmer',
                'resident',
            ])
            ->where('id', $id)
            ->where('resident_id', $user->id)
            ->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | INVOICE DATA
    |--------------------------------------------------------------------------
    |
    | Same invoice calculations used by the Resident order invoice.
    |
    */

    private function invoiceData(Order $order): array
    {
        $product =
            $order->product;

        $farmer =
            $order->farmer;

        $buyer =
            $order->resident;

        $createdAt =
            $order->created_at
            ?? now();

        $invoiceNumber =
            'ANI-' .
            $createdAt->format('Ymd') .
            '-' .
            str_pad(
                (string) $order->id,
                6,
                '0',
                STR_PAD_LEFT
            );

        $purchaseUnit =
            in_array(
                $order->purchase_unit,
                ['sack', 'kilo'],
                true
            )
                ? $order->purchase_unit
                : 'kilo';

        $quantityKilos =
            (float) (
                $order->quantity_kilos
                ?? $order->total_kilos
                ?? 0
            );

        $quantitySacks =
            (float) (
                $order->quantity_sacks
                ?? (
                    $quantityKilos > 0
                        ? $quantityKilos / 60
                        : 0
                )
            );

        $pricePerKg =
            (float) (
                $order->price_per_kg
                ?? $order->unit_price
                ?? $product?->price_per_kg
                ?? 0
            );

        $pricePerSack =
            (float) (
                $order->price_per_sack
                ?? $product?->price_per_sack
                ?? $product?->price_per_cavan
                ?? round($pricePerKg * 60)
            );

        if ($purchaseUnit === 'sack') {

            $quantityDisplay =
                number_format(
                    $quantitySacks,
                    0
                ) .
                (
                    $quantitySacks == 1
                        ? ' sack / cavan'
                        : ' sacks / cavan'
                );

            $unitPrice =
                $pricePerSack;

            $unitPriceLabel =
                'per sack / cavan';

        } else {

            $quantityDisplay =
                number_format(
                    $quantityKilos,
                    2
                ) .
                ' kg';

            $unitPrice =
                $pricePerKg;

            $unitPriceLabel =
                'per kg';
        }

        $subtotal =
            (float) (
                $order->subtotal
                ?? $order->total_price
                ?? 0
            );

        if ($subtotal <= 0) {
            $subtotal =
                $purchaseUnit === 'sack'
                    ? $quantitySacks * $pricePerSack
                    : $quantityKilos * $pricePerKg;
        }

        $shippingFee =
            (float) (
                $order->shipping_fee
                ?? 0
            );

        $grandTotal =
            (float) (
                $order->grand_total
                ?? ($subtotal + $shippingFee)
            );

        $buyerName =
            $order->buyer_name_snapshot
            ?? $order->buyer_name
            ?? $buyer?->fullname
            ?? $buyer?->username
            ?? 'Admin';

        $buyerContact =
            $order->buyer_contact_snapshot
            ?? $order->contact_number
            ?? $buyer?->mobile_number
            ?? $buyer?->contact_number
            ?? $buyer?->phone
            ?? '';

        $buyerAddress =
            $order->buyer_address_snapshot
            ?? $order->delivery_address
            ?? $buyer?->address
            ?? '';

        $farmerName =
            $order->farmer_name_snapshot
            ?? $farmer?->fullname
            ?? $farmer?->username
            ?? 'Unknown Farmer';

        $farmerAddress =
            $order->farmer_address_snapshot
            ?? $order->pickup_address
            ?? $farmer?->address
            ?? 'Farmer address not available';

        $fulfillmentType =
            strtolower(
                (string) (
                    $order->fulfillment_type
                    ?? 'delivery'
                )
            );

        $paymentMethod =
            strtolower(
                (string) (
                    $order->payment_method
                    ?? 'cash'
                )
            );

        $paymentStatus =
            strtolower(
                (string) (
                    $order->payment_status
                    ?? 'unpaid'
                )
            );

        $orderStatus =
            strtolower(
                (string) (
                    $order->status
                    ?? 'pending'
                )
            );

        return [
            'order_id' =>
                $order->id,

            'invoice_number' =>
                $invoiceNumber,

            'order_number' =>
                $order->order_number
                ?? $order->reference_number
                ?? $invoiceNumber,

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

            'buyer_name' =>
                $buyerName,

            'buyer_contact' =>
                $buyerContact,

            'buyer_address' =>
                $buyerAddress,

            'farmer_name' =>
                $farmerName,

            'farmer_address' =>
                $farmerAddress,

            'product_name' =>
                $order->product_name_snapshot
                ?? $product?->name
                ?? 'Rice / Palay Product',

            'purchase_unit' =>
                $purchaseUnit,

            'quantity_display' =>
                $quantityDisplay,

            'quantity_sacks' =>
                $quantitySacks,

            'quantity_kilos' =>
                $quantityKilos,

            'unit_price' =>
                $unitPrice,

            'unit_price_label' =>
                $unitPriceLabel,

            'price_per_kg' =>
                $pricePerKg,

            'price_per_sack' =>
                $pricePerSack,

            'subtotal' =>
                $subtotal,

            'shipping_fee' =>
                $shippingFee,

            'grand_total' =>
                $grandTotal,

            'distance_km' =>
                (float) (
                    $order->distance_km
                    ?? 0
                ),

            'fulfillment_type' =>
                $fulfillmentType,

            'delivery_address' =>
                $order->delivery_address
                ?? $buyerAddress,

            'pickup_address' =>
                $order->pickup_address
                ?? $farmerAddress,

            'payment_method' =>
                $paymentMethod,

            'payment_status' =>
                $paymentStatus,

            'order_status' =>
                $orderStatus,

            'notes' =>
                $order->notes_snapshot
                ?? $order->notes
                ?? null,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW ADMIN CHECKOUT
    |--------------------------------------------------------------------------
    |
    | Uses the same product/user data as the Resident checkout.
    |
    */

    public function showCheckout(int $id)
    {
        $this->requireAdmin();

        $user = Auth::user();

        $product = RiceProduct::with('user')
            ->where('id', $id)
            ->where('is_active', 1)
            ->firstOrFail();

        return view(
            'admin.checkout',
            compact('user', 'product')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PLACE ADMIN ORDER
    |--------------------------------------------------------------------------
    |
    | Same workflow as Resident:
    |
    | - Buy by Sack / Cavan or Kilo
    | - 1 Sack / Cavan = 60 KG
    | - Delivery or Pickup
    | - Shipping + road distance snapshot
    | - Pickup = free shipping
    | - Automatic Cash / Check rule
    | - Invoice snapshot
    | - Stock deduction
    | - Farmer notification through TransactionObserver
    |
    | IMPORTANT CURRENT-SCHEMA NOTE:
    | orders.resident_id is currently the application's buyer-user column.
    | We store the Admin user's ID there too so the existing Order relation,
    | invoice service, farmer order page and participant notification system
    | can all resolve the actual buyer correctly.
    |
    */

    public function placeOrder(
        Request $request,
        int $id,
        OrderInvoiceService $invoiceService
    ) {
        $this->requireAdmin();

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $data = $request->validate([
            'buyer_name' => [
                'required',
                'string',
                'max:150',
            ],

            'contact_number' => [
                'required',
                'string',
                'max:30',
            ],

            'purchase_unit' => [
                'required',
                'in:sack,kilo',
            ],

            'order_quantity' => [
                'required',
                'numeric',
                'min:1',
            ],

            'quantity_sacks' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'quantity_kilos' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'total_kilos' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fulfillment_type' => [
                'required',
                'in:delivery,pickup',
            ],

            'delivery_address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'pickup_address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shipping_fee' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'distance_km' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'delivery_latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'delivery_longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'payment_method' => [
                'nullable',
                'in:cash,check,gcash,bank_transfer,cash_on_delivery,cash_on_pickup',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ADDRESS VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            $data['fulfillment_type'] === 'delivery' &&
            empty($data['delivery_address'])
        ) {
            return back()
                ->withErrors([
                    'delivery_address' =>
                        'Delivery address is required for delivery orders.',
                ])
                ->withInput();
        }

        if (
            $data['fulfillment_type'] === 'pickup' &&
            empty($data['pickup_address'])
        ) {
            return back()
                ->withErrors([
                    'pickup_address' =>
                        'Farmer pickup address is required for pickup orders.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | SACK MUST BE WHOLE NUMBER
        |--------------------------------------------------------------------------
        */

        if (
            $data['purchase_unit'] === 'sack' &&
            floor((float) $data['order_quantity']) !==
                (float) $data['order_quantity']
        ) {
            return back()
                ->withErrors([
                    'order_quantity' =>
                        'Sack / Cavan quantity must be a whole number.',
                ])
                ->withInput();
        }


        try {

            $order = DB::transaction(function () use (
                $data,
                $user,
                $id,
                $request,
                $invoiceService
            ) {

                /*
                |--------------------------------------------------------------------------
                | LOCK PRODUCT
                |--------------------------------------------------------------------------
                */

                $product = RiceProduct::with('user')
                    ->where('id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$product->is_active) {
                    throw ValidationException::withMessages([
                        'order' =>
                            'This product is not available right now.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | PRICE / CAVAN CONVERSION
                |--------------------------------------------------------------------------
                */

                $kgPerCavan = 60.0;

                $pricePerKg =
                    (float) ($product->price_per_kg ?? 0);

                if ($pricePerKg <= 0) {
                    throw ValidationException::withMessages([
                        'order' =>
                            'The product price is invalid. Please contact the farmer.',
                    ]);
                }

                $pricePerSack = (float) (
                    $product->price_per_sack
                    ?? $product->price_per_cavan
                    ?? round($pricePerKg * $kgPerCavan)
                );

                $stock =
                    (float) ($product->kilos_available ?? 0);

                if ($stock <= 0) {
                    throw ValidationException::withMessages([
                        'order' =>
                            'This product is currently out of stock.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | QUANTITY / SUBTOTAL
                |--------------------------------------------------------------------------
                */

                $purchaseUnit =
                    $data['purchase_unit'];

                $orderQuantity =
                    (float) $data['order_quantity'];

                $quantitySacks = 0.0;
                $quantityKilos = 0.0;
                $subtotal = 0.0;

                if ($purchaseUnit === 'sack') {

                    $quantitySacks =
                        (float) ((int) $orderQuantity);

                    $quantityKilos =
                        $quantitySacks * $kgPerCavan;

                    $subtotal =
                        $quantitySacks * $pricePerSack;

                } else {

                    $quantityKilos =
                        $orderQuantity;

                    $quantitySacks =
                        $quantityKilos / $kgPerCavan;

                    $subtotal =
                        $quantityKilos * $pricePerKg;
                }


                /*
                |--------------------------------------------------------------------------
                | STOCK CHECK
                |--------------------------------------------------------------------------
                */

                if ($quantityKilos > $stock) {
                    throw ValidationException::withMessages([
                        'order' =>
                            'Not enough stock available. Maximum available stock is ' .
                            number_format($stock, 2) .
                            ' kg.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | SHIPPING
                |--------------------------------------------------------------------------
                */

                $shippingFee =
                    $data['fulfillment_type'] === 'pickup'
                        ? 0.0
                        : max(
                            0,
                            (float) ($data['shipping_fee'] ?? 0)
                        );

                $distanceKm =
                    $data['fulfillment_type'] === 'pickup'
                        ? 0.0
                        : max(
                            0,
                            (float) ($data['distance_km'] ?? 0)
                        );


                /*
                |--------------------------------------------------------------------------
                | TOTAL / AUTOMATIC PAYMENT
                |--------------------------------------------------------------------------
                */

                $grandTotal =
                    $subtotal + $shippingFee;

                $paymentMethod =
                    $grandTotal >= 100000
                        ? 'check'
                        : 'cash';


                /*
                |--------------------------------------------------------------------------
                | CREATE ORDER
                |--------------------------------------------------------------------------
                |
                | resident_id = ACTUAL BUYER USER ID in the current schema.
                | That buyer can be Resident OR Admin.
                |
                */

                $order = Order::create([
                    'resident_id' =>
                        $user->id,

                    'rice_product_id' =>
                        $product->id,

                    'farmer_id' =>
                        $product->user_id,

                    'buyer_name' =>
                        $data['buyer_name'],

                    'contact_number' =>
                        $data['contact_number'],

                    'quantity_kilos' =>
                        round($quantityKilos, 2),

                    'unit_price' =>
                        round($pricePerKg, 2),

                    'total_price' =>
                        round($subtotal, 2),

                    'status' =>
                        'pending',

                    'fulfillment_type' =>
                        $data['fulfillment_type'],

                    'delivery_address' =>
                        $data['fulfillment_type'] === 'delivery'
                            ? ($data['delivery_address'] ?? null)
                            : null,

                    'pickup_address' =>
                        $data['fulfillment_type'] === 'pickup'
                            ? ($data['pickup_address'] ?? null)
                            : null,

                    'payment_method' =>
                        $paymentMethod,

                    'delivery_latitude' =>
                        $data['fulfillment_type'] === 'delivery'
                            ? ($data['delivery_latitude'] ?? null)
                            : null,

                    'delivery_longitude' =>
                        $data['fulfillment_type'] === 'delivery'
                            ? ($data['delivery_longitude'] ?? null)
                            : null,

                    'notes' =>
                        $data['notes'] ?? null,
                ]);


                /*
                |--------------------------------------------------------------------------
                | INITIAL WORKFLOW VALUES
                |--------------------------------------------------------------------------
                */

                $workflow = [];

                if (Schema::hasColumn('orders', 'payment_status')) {
                    $workflow['payment_status'] = 'unpaid';
                }

                if (Schema::hasColumn('orders', 'delivery_status')) {
                    $workflow['delivery_status'] = 'pending';
                }

                if (!empty($workflow)) {
                    $order->forceFill($workflow);
                    $order->save();
                }


                /*
                |--------------------------------------------------------------------------
                | INVOICE SNAPSHOT
                |--------------------------------------------------------------------------
                */

                $request->merge([
                    'purchase_unit' =>
                        $purchaseUnit,

                    'order_quantity' =>
                        $orderQuantity,

                    'quantity_sacks' =>
                        $quantitySacks,

                    'quantity_kilos' =>
                        $quantityKilos,

                    'total_kilos' =>
                        $quantityKilos,

                    'shipping_fee' =>
                        $shippingFee,

                    'distance_km' =>
                        $distanceKm,

                    'payment_method' =>
                        $paymentMethod,
                ]);

                $order = $invoiceService->capture(
                    $order,
                    $request,
                    $product
                );


                /*
                |--------------------------------------------------------------------------
                | DEDUCT STOCK
                |--------------------------------------------------------------------------
                */

                $remainingStock =
                    $stock - $quantityKilos;

                $product->kilos_available =
                    max(0, $remainingStock);

                if (
                    (float) $product->kilos_available <= 0
                ) {
                    $product->kilos_available = 0;
                    $product->is_active = 0;
                }

                $product->save();

                return $order;
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors($e->errors())
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        |
        | The TransactionObserver will notify the Farmer because the Admin is
        | now the actual buyer in this transaction.
        |
        */

        return redirect()
            ->route('admin.market')
            ->with(
                'success',
                "Order #{$order->id} placed successfully. The farmer has been notified."
            );
    }
}