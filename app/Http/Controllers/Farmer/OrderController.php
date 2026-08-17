<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REQUIRE FARMER
    |--------------------------------------------------------------------------
    */

    private function requireFarmer(): void
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'farmer') {
            abort(403, 'Unauthorized');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FIND FARMER'S OWN ORDER
    |--------------------------------------------------------------------------
    */

    private function findMyOrderOrFail(int $id): Order
    {
        $this->requireFarmer();

        $user = Auth::user();

        return Order::with([
                'product',
                'resident',
            ])
            ->where('id', $id)
            ->where('farmer_id', $user->id)
            ->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | FARMER ORDERS PAGE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $this->requireFarmer();

        $user = Auth::user();

        $orders = Order::with([
                'product:id,name,type,price_per_kg,kilos_available,photo_path,user_id',
                'resident:id,fullname,username,email',
            ])
            ->where('farmer_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view(
            'farmer.orders.index',
            compact('user', 'orders')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE ORDER
    |--------------------------------------------------------------------------
    |
    | PENDING -> APPROVED
    |
    */

    public function approve(int $id)
    {
        $order = $this->findMyOrderOrFail($id);

        if ($order->status !== 'pending') {
            return back()->withErrors([
                'order' => 'Only PENDING orders can be approved.',
            ]);
        }

        $order->status = 'approved';

        /*
         * Initialize workflow values.
         * These columns are added by our delivery workflow migration.
         */
        if (empty($order->payment_status)) {
            $order->payment_status = 'unpaid';
        }

        if (empty($order->delivery_status)) {
            $order->delivery_status = 'pending';
        }

        $order->save();

        return back()->with(
            'success',
            "Order #{$order->id} approved."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MARK PAYMENT AS PAID
    |--------------------------------------------------------------------------
    |
    | This means the farmer ACTUALLY received the payment.
    |
    | Example:
    | APPROVED
    | UNPAID -> PAID
    |
    */

    public function markPaid(int $id)
    {
        $order = $this->findMyOrderOrFail($id);

        if ($order->status === 'cancelled') {
            return back()->withErrors([
                'order' => 'Cancelled orders cannot be marked as paid.',
            ]);
        }

        if ($order->status === 'completed') {
            return back()->withErrors([
                'order' => 'This order has already been completed.',
            ]);
        }

        if ($order->status !== 'approved') {
            return back()->withErrors([
                'order' => 'Approve the order first before marking it as paid.',
            ]);
        }

        if (($order->payment_status ?? 'unpaid') === 'paid') {
            return back()->with(
                'success',
                "Order #{$order->id} is already marked as PAID."
            );
        }

        $order->payment_status = 'paid';
        $order->paid_at = now();

        $order->save();

        return back()->with(
            'success',
            "Payment for Order #{$order->id} marked as PAID."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SET / UPDATE EXPECTED DELIVERY
    |--------------------------------------------------------------------------
    |
    | APPROVED / PREPARING
    |        ↓
    | Farmer sets expected delivery date + time
    |        ↓
    | Buyer receives notification
    |        ↓
    | Farmer may change schedule until actual dispatch starts
    |
    */

    public function setExpectedDelivery(
        Request $request,
        int $id
    ) {
        $order = $this->findMyOrderOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | ORDER MUST BE APPROVED
        |--------------------------------------------------------------------------
        */

        if ($order->status === 'cancelled') {
            return back()->withErrors([
                'order' =>
                    'Cancelled orders cannot have an expected delivery schedule.',
            ]);
        }

        if ($order->status === 'completed') {
            return back()->withErrors([
                'order' =>
                    'This order has already been completed.',
            ]);
        }

        if ($order->status !== 'approved') {
            return back()->withErrors([
                'order' =>
                    'Approve the order first before setting the expected delivery.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | DELIVERY ORDERS ONLY
        |--------------------------------------------------------------------------
        */

        if (($order->fulfillment_type ?? 'delivery') !== 'delivery') {
            return back()->withErrors([
                'order' =>
                    'This order is for PICKUP. Expected delivery date and time only applies to delivery orders.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | DELIVERY MUST NOT HAVE STARTED YET
        |--------------------------------------------------------------------------
        */

        $deliveryStatus =
            strtolower(
                (string) ($order->delivery_status ?? 'pending')
            );

        if (
            in_array(
                $deliveryStatus,
                [
                    'out_for_delivery',
                    'delivered',
                ],
                true
            )
        ) {
            return back()->withErrors([
                'order' =>
                    'Expected delivery can no longer be changed because delivery has already started.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATE DATE + TIME
        |--------------------------------------------------------------------------
        |
        | HTML datetime-local sends:
        | YYYY-MM-DDTHH:MM
        |
        */

        $validated = $request->validate(
            [
                'expected_delivery_at' => [
                    'required',
                    'date_format:Y-m-d\TH:i',
                ],
            ],
            [
                'expected_delivery_at.required' =>
                    'Please select the expected delivery date and time.',

                'expected_delivery_at.date_format' =>
                    'Please select a valid expected delivery date and time.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | PHILIPPINE TIME -> UTC
        |--------------------------------------------------------------------------
        |
        | The seller selects Philippine local time.
        | Store UTC in the database.
        |
        */

        try {

            $expectedManila =
                \Carbon\Carbon::createFromFormat(
                    'Y-m-d\TH:i',
                    $validated['expected_delivery_at'],
                    'Asia/Manila'
                );

        } catch (\Throwable $e) {

            return back()->withErrors([
                'expected_delivery_at' =>
                    'Invalid expected delivery date and time.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | MUST BE A FUTURE DATE / TIME
        |--------------------------------------------------------------------------
        */

        if (
            $expectedManila->lessThanOrEqualTo(
                \Carbon\Carbon::now('Asia/Manila')
            )
        ) {
            return back()->withErrors([
                'expected_delivery_at' =>
                    'Expected delivery must be a future date and time.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SAVE EXPECTED DELIVERY
        |--------------------------------------------------------------------------
        */

        $order->expected_delivery_at =
            $expectedManila
                ->copy()
                ->utc();

        /*
         * Keep the order in PREPARING / pending delivery status.
         * Setting a schedule does NOT mean it is already on the road.
         */
        if (empty($order->delivery_status)) {
            $order->delivery_status = 'pending';
        }

        $order->save();


        /*
        |--------------------------------------------------------------------------
        | NOTIFICATION
        |--------------------------------------------------------------------------
        |
        | TransactionObserver will detect that expected_delivery_at changed
        | and notify the actual buyer (Resident or Admin buyer).
        |
        */

        return back()->with(
            'success',
            "Expected delivery for Order #{$order->id} set to " .
            $expectedManila->format('M d, Y h:i A') .
            '. The buyer has been notified.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | START DELIVERY
    |--------------------------------------------------------------------------
    |
    | APPROVED / PREPARING
    |        ↓
    | OUT FOR DELIVERY
    |
    */

    public function startDelivery(int $id)
    {
        $order = $this->findMyOrderOrFail($id);

        if ($order->status === 'cancelled') {
            return back()->withErrors([
                'order' => 'Cancelled orders cannot be delivered.',
            ]);
        }

        if ($order->status === 'completed') {
            return back()->withErrors([
                'order' => 'This order has already been completed.',
            ]);
        }

        if ($order->status !== 'approved') {
            return back()->withErrors([
                'order' => 'Only APPROVED orders can be sent for delivery.',
            ]);
        }

        /*
         * Start Delivery only applies to DELIVERY orders.
         */
        if (($order->fulfillment_type ?? 'delivery') !== 'delivery') {
            return back()->withErrors([
                'order' => 'This order is for PICKUP and cannot be set to Out for Delivery.',
            ]);
        }

        /*
         * Require an expected delivery schedule before actual dispatch.
         * This prevents direct URL/form bypass of the intended workflow.
         */
        if (empty($order->expected_delivery_at)) {
            return back()->withErrors([
                'order' =>
                    'Set the expected delivery date and time before dispatching this order.',
            ]);
        }

        $currentDeliveryStatus =
            strtolower($order->delivery_status ?? 'pending');

        if ($currentDeliveryStatus === 'out_for_delivery') {
            return back()->with(
                'success',
                "Order #{$order->id} is already OUT FOR DELIVERY."
            );
        }

        if ($currentDeliveryStatus === 'delivered') {
            return back()->withErrors([
                'order' => 'This order has already been marked as delivered.',
            ]);
        }

        $order->delivery_status = 'out_for_delivery';
        $order->out_for_delivery_at = now();

        $order->save();

        return back()->with(
            'success',
            "Order #{$order->id} is now OUT FOR DELIVERY."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPLOAD PROOF OF DELIVERY + MARK DELIVERED
    |--------------------------------------------------------------------------
    |
    | OUT FOR DELIVERY
    |        ↓
    | Upload proof photo
    |        ↓
    | DELIVERED
    |
    | IMPORTANT:
    |
    | We DO NOT change order.status to COMPLETED here.
    |
    | Farmer:
    | delivery_status = delivered
    |
    | Buyer:
    | clicks "I Received My Order"
    |
    | Then:
    | status = completed
    |
    */

    public function markDelivered(
        Request $request,
        int $id
    ) {
        $order = $this->findMyOrderOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | VALIDATE PHOTO
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'proof_photo' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:5120',
                ],
            ],
            [
                'proof_photo.required' =>
                    'Please take or select a proof of delivery photo.',

                'proof_photo.image' =>
                    'The proof of delivery must be an image.',

                'proof_photo.mimes' =>
                    'The proof image must be JPG, JPEG, PNG, or WEBP.',

                'proof_photo.max' =>
                    'The proof image must not exceed 5 MB.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | CHECK ORDER STATUS
        |--------------------------------------------------------------------------
        */

        if ($order->status === 'cancelled') {
            return back()->withErrors([
                'order' =>
                    'Cancelled orders cannot be marked as delivered.',
            ]);
        }

        if ($order->status === 'completed') {
            return back()->withErrors([
                'order' =>
                    'This order has already been completed.',
            ]);
        }

        if ($order->status !== 'approved') {
            return back()->withErrors([
                'order' =>
                    'Only APPROVED orders can be marked as delivered.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | DELIVERY TYPE VALIDATION
        |--------------------------------------------------------------------------
        */

        if (($order->fulfillment_type ?? 'delivery') !== 'delivery') {
            return back()->withErrors([
                'order' =>
                    'This order is for PICKUP. Delivery proof cannot be submitted.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | MUST BE OUT FOR DELIVERY FIRST
        |--------------------------------------------------------------------------
        */

        $currentDeliveryStatus =
            strtolower($order->delivery_status ?? 'pending');

        if ($currentDeliveryStatus !== 'out_for_delivery') {
            return back()->withErrors([
                'order' =>
                    'Set this order to OUT FOR DELIVERY before uploading proof of delivery.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE OLD PROOF IF THERE IS ONE
        |--------------------------------------------------------------------------
        */

        if (
            !empty($order->proof_of_delivery_path) &&
            Storage::disk('public')
                ->exists($order->proof_of_delivery_path)
        ) {
            Storage::disk('public')
                ->delete($order->proof_of_delivery_path);
        }


        /*
        |--------------------------------------------------------------------------
        | SAVE PROOF PHOTO
        |--------------------------------------------------------------------------
        */

        $proofPath = $request
            ->file('proof_photo')
            ->store(
                'delivery-proofs',
                'public'
            );


        /*
        |--------------------------------------------------------------------------
        | UPDATE DELIVERY INFORMATION
        |--------------------------------------------------------------------------
        */

        $order->proof_of_delivery_path =
            $proofPath;

        $order->delivery_status =
            'delivered';

        $order->delivered_at =
            now();

        /*
         * DO NOT:
         *
         * $order->status = 'completed';
         *
         * Buyer must confirm receipt first.
         */

        $order->save();


        /*
        |--------------------------------------------------------------------------
        | BUYER NOTIFICATION
        |--------------------------------------------------------------------------
        |
        | We do not need to manually insert a notification here
        | if your Resident Dashboard notification is querying:
        |
        | status = approved
        | delivery_status = delivered
        |
        | Once this save happens, the notification automatically
        | becomes visible on the buyer dashboard.
        |
        */

        return back()->with(
            'success',
            "Order #{$order->id} marked as DELIVERED. Proof of delivery uploaded successfully. The buyer can now confirm receipt."
        );
    }


    /*
    |--------------------------------------------------------------------------
    | OLD COMPLETE METHOD
    |--------------------------------------------------------------------------
    |
    | Keep this temporarily so an old route will not cause
    | "method does not exist".
    |
    | BUT farmer is no longer allowed to complete an order.
    | Only the buyer should complete it after receiving the item.
    |
    */

    public function complete(int $id)
    {
        $order = $this->findMyOrderOrFail($id);

        return back()->withErrors([
            'order' =>
                "Order #{$order->id} cannot be completed by the farmer. The buyer must confirm \"I Received My Order\" after delivery.",
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL ORDER
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Request $request,
        int $id
    ) {
        $order = $this->findMyOrderOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | ALREADY CANCELLED
        |--------------------------------------------------------------------------
        |
        | Important so stock will not be returned twice.
        |
        */

        if ($order->status === 'cancelled') {
            return back()->withErrors([
                'order' =>
                    'This order has already been cancelled.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | COMPLETED CANNOT BE CANCELLED
        |--------------------------------------------------------------------------
        */

        if ($order->status === 'completed') {
            return back()->withErrors([
                'order' =>
                    'Completed orders cannot be cancelled.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | DELIVERY ALREADY STARTED
        |--------------------------------------------------------------------------
        */

        $deliveryStatus =
            strtolower($order->delivery_status ?? 'pending');

        if (
            in_array(
                $deliveryStatus,
                [
                    'out_for_delivery',
                    'delivered',
                ],
                true
            )
        ) {
            return back()->withErrors([
                'order' =>
                    'This order can no longer be cancelled because delivery has already started.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | RETURN PRODUCT STOCK
        |--------------------------------------------------------------------------
        */

        if ($order->product) {

            $currentStock =
                (float) (
                    $order->product->kilos_available
                    ?? 0
                );

            $orderedKilos =
                (float) (
                    $order->quantity_kilos
                    ?? 0
                );

            $order->product->kilos_available =
                $currentStock + $orderedKilos;

            /*
             * Make the product available again.
             */
            $order->product->is_active = 1;

            $order->product->save();
        }

        


        /*
        |--------------------------------------------------------------------------
        | CANCEL ORDER
        |--------------------------------------------------------------------------
        */

        $order->status = 'cancelled';

        $order->save();

        return back()->with(
            'success',
            "Order #{$order->id} cancelled. Product stock has been returned."
        );
    }
}