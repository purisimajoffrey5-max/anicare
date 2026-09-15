<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RiceProduct;
use App\Services\OrderInvoiceService;
use App\Services\VatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Make sure the logged-in user is a resident.
     */
    private function requireResident(): void
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'resident') {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Resident My Orders page.
     */
    public function index()
    {
        $this->requireResident();

        $user = Auth::user();

        $orders = Order::with([
                'product:id,user_id,name,type,price_per_kg,kilos_available,photo_path,is_active',
                'farmer:id,fullname,username',
            ])
            ->where('resident_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('resident.orders', compact('user', 'orders'));
    }

    /**
     * Show one resident order / tracking page.
     *
     * IMPORTANT:
     * "approved" does not automatically mean OUT FOR DELIVERY.
     *
     * We use:
     * - order.status
     * - delivery_status
     * - expected_delivery_at
     */
    public function show(int $id)
    {
        $this->requireResident();

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

        /*
        |--------------------------------------------------------------------------
        | EXPECTED DELIVERY
        |--------------------------------------------------------------------------
        |
        | Stored as UTC, displayed as Philippine time.
        |
        */
        $expectedDelivery = null;

        if (!empty($order->expected_delivery_at)) {
            $expectedDelivery = \Carbon\Carbon::parse(
                $order->expected_delivery_at,
                'UTC'
            )->timezone('Asia/Manila');
        }

        /*
        |--------------------------------------------------------------------------
        | MAP LOCATIONS
        |--------------------------------------------------------------------------
        |
        | This project currently stores seller/buyer coordinates.
        | It does NOT yet have a true live rider GPS stream.
        |
        */
        $farmerLocation = null;
        $buyerLocation = null;
        $currentLocation = null;
        $currentLocationText = 'Delivery location';

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
         * Prefer the exact coordinates saved during checkout.
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

        if (
            $deliveryStatus === 'delivered' ||
            $orderStatus === 'completed'
        ) {
            $currentLocation =
                $buyerLocation
                ?? $farmerLocation;

            $currentLocationText =
                'Delivery destination';

        } elseif ($farmerLocation) {

            $currentLocation = $farmerLocation;

            $currentLocationText =
                $deliveryStatus === 'out_for_delivery'
                    ? 'Seller dispatch point'
                    : 'Seller location';

        } elseif ($buyerLocation) {

            $currentLocation = $buyerLocation;
            $currentLocationText = 'Delivery destination';
        }

        $mapCenterLat =
            $currentLocation['lat']
            ?? ($buyerLocation['lat'] ?? 18.2760);

        $mapCenterLng =
            $currentLocation['lng']
            ?? ($buyerLocation['lng'] ?? 121.6440);

        /*
        |--------------------------------------------------------------------------
        | DISPLAY DELIVERY STAGE
        |--------------------------------------------------------------------------
        */
        if ($orderStatus === 'cancelled') {

            $deliveryStage = 'CANCELLED';

        } elseif ($orderStatus === 'completed') {

            $deliveryStage = 'COMPLETED';

        } elseif ($orderStatus === 'pending') {

            $deliveryStage = 'PENDING';

        } elseif ($deliveryStatus === 'delivered') {

            $deliveryStage = 'DELIVERED';

        } elseif ($deliveryStatus === 'out_for_delivery') {

            $deliveryStage = 'OUT FOR DELIVERY';

        } elseif ($orderStatus === 'approved' && $expectedDelivery) {

            $deliveryStage = 'SCHEDULED';

        } elseif ($orderStatus === 'approved') {

            $deliveryStage = 'PREPARING';

        } else {

            $deliveryStage = strtoupper($orderStatus);
        }

        /*
        |--------------------------------------------------------------------------
        | ACCURATE TRACKING MESSAGE
        |--------------------------------------------------------------------------
        */
        if ($orderStatus === 'pending') {

            $deliveryNote =
                'Your order is waiting for the farmer to approve it.';

        } elseif ($orderStatus === 'cancelled') {

            $deliveryNote =
                'This order was cancelled.';

        } elseif ($orderStatus === 'completed') {

            $deliveryNote =
                'Transaction completed. You confirmed that the order was received.';

        } elseif ($deliveryStatus === 'delivered') {

            $deliveryNote =
                'The seller marked this order as delivered. Confirm receipt only after you actually receive the order.';

        } elseif ($deliveryStatus === 'out_for_delivery') {

            $deliveryNote =
                'Your seller has dispatched this order. It is now OUT FOR DELIVERY.';

        } elseif ($orderStatus === 'approved' && $expectedDelivery) {

            $deliveryNote =
                'Your order is approved and is still being prepared. Expected delivery: ' .
                $expectedDelivery->format('F d, Y h:i A') .
                '.';

        } elseif ($orderStatus === 'approved') {

            $deliveryNote =
                'Your order is approved and is being prepared. The seller has not set an expected delivery schedule yet.';

        } else {

            $deliveryNote =
                'Track the current status of your order below.';
        }

        return view('resident.order-show', compact(
            'order',
            'orderStatus',
            'deliveryStatus',
            'deliveryStage',
            'expectedDelivery',
            'farmerLocation',
            'buyerLocation',
            'currentLocation',
            'currentLocationText',
            'mapCenterLat',
            'mapCenterLng',
            'deliveryNote'
        ));
    }

    /**
     * Show checkout page.
     */
    public function showCheckout(int $id)
    {
        $this->requireResident();

        $user = Auth::user();

        /*
         * IMPORTANT:
         * Do not explicitly select municipality/province from users because
         * those columns do not exist in your current users table.
         *
         * Load the whole related user instead so Laravel only selects the
         * real columns that exist in the users table.
         */
        $product = RiceProduct::with('user')
            ->where('id', $id)
            ->where('is_active', 1)
            ->firstOrFail();

        $vatEnabled = VatService::enabled();
        $vatRate = VatService::rate();

        return view('resident.checkout', compact('user', 'product', 'vatEnabled', 'vatRate'));
    }

    /**
     * Place an order.
     *
     * Supports:
     * - Per Sack / Cavan
     * - Per Kilo
     * - Delivery
     * - Pickup
     * - Shipping / distance snapshot
     * - Invoice snapshot
     * - Stock deduction
     * - Redirect to Order Success page
     */
    public function placeOrder(
        Request $request,
        int $id,
        OrderInvoiceService $invoiceService
    ) {
        $this->requireResident();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | VALIDATE CHECKOUT DATA
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

            /*
            | sack = Sack / Cavan
            | kilo = Per Kilo
            */
            'purchase_unit' => [
                'required',
                'in:sack,kilo',
            ],

            /*
            | Visible dynamic quantity field from checkout.
            | Examples:
            | sack -> 1, 2, 3...
            | kilo -> 1, 10, 25...
            */
            'order_quantity' => [
                'required',
                'numeric',
                'min:1',
            ],

            /*
            | Hidden checkout values.
            | The controller recalculates these server-side.
            */
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

            /*
            | Checkout sends this hidden value.
            | We still recompute Cash/Check below using the final grand total.
            */
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
        | DELIVERY / PICKUP ADDRESS VALIDATION
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
                        'Pickup address is required for pickup orders.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | WHOLE NUMBER ONLY FOR SACK / CAVAN
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
            /*
            |--------------------------------------------------------------------------
            | CREATE ORDER + DEDUCT STOCK IN ONE TRANSACTION
            |--------------------------------------------------------------------------
            */
            $order = DB::transaction(function () use (
                $data,
                $user,
                $id,
                $request,
                $invoiceService
            ) {
                /*
                |--------------------------------------------------------------------------
                | LOCK PRODUCT ROW
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
                | PRICING / CONVERSION
                |--------------------------------------------------------------------------
                |
                | 1 Sack = 1 Cavan = 60 KG
                |
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

                /*
                | Whole Sack / Cavan price:
                | If the product has its own price_per_sack or price_per_cavan,
                | use it. Otherwise round 60kg x price/kg.
                |
                | Example:
                | 41.67 x 60 = 2500.20
                | Rounded whole-cavan price = 2500
                */
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

                $purchaseUnit =
                    $data['purchase_unit'];

                $orderQuantity =
                    (float) $data['order_quantity'];

                $quantitySacks = 0.0;
                $quantityKilos = 0.0;
                $subtotal = 0.0;

                /*
                |--------------------------------------------------------------------------
                | BUY BY SACK / CAVAN
                |--------------------------------------------------------------------------
                */
                if ($purchaseUnit === 'sack') {
                    $quantitySacks =
                        (float) ((int) $orderQuantity);

                    $quantityKilos =
                        $quantitySacks * $kgPerCavan;

                    $subtotal =
                        $quantitySacks * $pricePerSack;
                }

                /*
                |--------------------------------------------------------------------------
                | BUY PER KILO
                |--------------------------------------------------------------------------
                */
                else {
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
                |
                | Pickup = FREE SHIPPING
                |
                | Delivery uses the distance/shipping computed by the checkout.
                | The invoice service records these values for transparency.
                |
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
                | TOTAL + PAYMENT METHOD
                |--------------------------------------------------------------------------
                */
                $grandTotal =
                    $subtotal + $shippingFee;

                /*
                | VAT / RECEIPT SETTING
                |
                | The Admin VAT toggle is authoritative. VAT is treated as
                | inclusive, so enabling VAT does not unexpectedly add another
                | 12% on top of the customer's existing amount.
                |
                | The saved Central VAT rate determines the VAT breakdown.
                */
                $vat = VatService::breakdown($grandTotal);

                $paymentMethod =
                    $grandTotal >= 100000
                        ? 'check'
                        : 'cash';

                /*
                |--------------------------------------------------------------------------
                | CREATE EXISTING ORDER RECORD
                |--------------------------------------------------------------------------
                |
                | unit_price stays as PRICE PER KG for compatibility
                | with your existing order pages.
                |
                | total_price stores the PRODUCT SUBTOTAL.
                | The invoice snapshot stores the grand total separately.
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
                | PASS SERVER-COMPUTED VALUES TO INVOICE SERVICE
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

                /*
                |--------------------------------------------------------------------------
                | SAVE INVOICE / TRANSPARENCY SNAPSHOT
                |--------------------------------------------------------------------------
                */
                $order = $invoiceService->capture(
                    $order,
                    $request,
                    $product
                );

                /*
                |--------------------------------------------------------------------------
                | DEDUCT PRODUCT STOCK
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
        | SUCCESS PAGE
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route(
                'resident.orders.success',
                [
                    'order' => $order->id,
                ]
            )
            ->with(
                'success',
                'Order placed successfully!'
            );
    }

    /**
     * Buyer confirms the order was actually received.
     *
     * Allowed only when:
     * - order.status = approved
     * - delivery_status = delivered
     * - proof_of_delivery_path exists
     *
     * Then:
     * - status = completed
     * - buyer_confirmed_at = now()
     */
    public function confirmReceived(int $id)
    {
        $this->requireResident();

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

                if (\Illuminate\Support\Facades\Schema::hasColumn(
                    'orders',
                    'buyer_confirmed_at'
                )) {
                    $order->buyer_confirmed_at = now();
                }

                $order->save();
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors($e->errors());
        }

        return redirect()
            ->route('resident.orders.index')
            ->with(
                'success',
                'Order confirmed as received. Transaction completed.'
            );
    }

    /**
     * Prevent direct generic order creation.
     */
    public function store(Request $request)
    {
        $this->requireResident();

        return back()->withErrors([
            'order' =>
                'Please use Buy Now to continue to checkout.',
        ]);
    }
}