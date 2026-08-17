<?php

namespace App\Observers;

use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Auth;

class TransactionObserver
{
    /*
    |--------------------------------------------------------------------------
    | ANI-CARE NOTIFICATION RULE
    |--------------------------------------------------------------------------
    |
    | Only the users DIRECTLY involved in a transaction receive transaction
    | notifications.
    |
    | MARKETPLACE
    | - Resident <-> Farmer
    | - Admin    <-> Farmer   (when Admin is actually the buyer)
    |
    | MILLING
    | - Farmer <-> Miller
    |
    | ACCOUNT REGISTRATION / APPROVAL
    | - User <-> Admin
    |
    | ANNOUNCEMENTS
    | - Admin -> All other users
    |
    | IMPORTANT:
    | Admin does NOT receive Farmer/Resident marketplace notifications just
    | because the user is an administrator. Admin receives marketplace
    | notifications only when that Admin account is actually the buyer.
    |
    */

    public function created(EloquentModel $model): void
    {
        $service = app(NotificationService::class);

        if ($model instanceof \App\Models\User) {
            $this->userCreated($model, $service);
            return;
        }

        if ($model instanceof \App\Models\Order) {
            $this->orderCreated($model, $service);
            return;
        }

        if (
            class_exists(\App\Models\MillingRequest::class) &&
            $model instanceof \App\Models\MillingRequest
        ) {
            $this->millingCreated($model, $service);
            return;
        }

        if (
            class_exists(\App\Models\Announcement::class) &&
            $model instanceof \App\Models\Announcement
        ) {
            $this->announcementCreated($model, $service);
            return;
        }
    }


    public function updated(EloquentModel $model): void
    {
        $service = app(NotificationService::class);

        if ($model instanceof \App\Models\User) {
            $this->userUpdated($model, $service);
            return;
        }

        if ($model instanceof \App\Models\Order) {
            $this->orderUpdated($model, $service);
            return;
        }

        if (
            class_exists(\App\Models\MillingRequest::class) &&
            $model instanceof \App\Models\MillingRequest
        ) {
            $this->millingUpdated($model, $service);
            return;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ACCOUNT REGISTRATION / APPROVAL
    |--------------------------------------------------------------------------
    |
    | New account:
    | User -> Admin
    |
    | Approval / account status:
    | Admin -> User
    |
    */

    private function userCreated(
        \App\Models\User $user,
        NotificationService $service
    ): void {
        $role = strtolower((string) ($user->role ?? ''));

        if ($role === 'admin') {
            return;
        }

        $name =
            $user->fullname
            ?? $user->username
            ?? $user->email
            ?? 'New User';

        $service->sendToAdmins(
            'New Account Registration',
            "{$name} registered as " . ucfirst($role) . '. Review the account application.',
            'account',
            '/admin/approvals',
            'bi-person-plus-fill',
            [
                'user_id' => $user->id,
                'role' => $role,
            ],
            $user->id
        );
    }


    private function userUpdated(
        \App\Models\User $user,
        NotificationService $service
    ): void {
        if (!$user->wasChanged('status')) {
            return;
        }

        $status = strtoupper((string) $user->status);

        $service->send(
            $user->id,
            'Account Status Updated',
            "Your ANI-CARE account status is now {$status}.",
            'account',
            '/dashboard',
            $status === 'ACTIVE'
                ? 'bi-person-check-fill'
                : 'bi-person-exclamation',
            [
                'status' => $user->status,
            ],
            Auth::id()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MARKETPLACE
    |--------------------------------------------------------------------------
    |
    | Buyer can be:
    | - Resident
    | - Admin
    |
    | The buyer ID is resolved from:
    | buyer_id -> resident_id -> user_id -> current authenticated user.
    |
    | That means an Admin receives order notifications ONLY when the Admin
    | is really the buyer for that order.
    |
    */

    private function orderCreated(
        \App\Models\Order $order,
        NotificationService $service
    ): void {
        $farmerId = $this->orderFarmerId($order);
        $buyerId  = $this->orderBuyerId($order);

        if (!$farmerId || !$buyerId) {
            return;
        }

        /*
         * Avoid sending a user a notification to themselves.
         */
        if ($farmerId === $buyerId) {
            return;
        }

        $buyerName =
            $order->buyer_name
            ?? $this->userDisplayName($buyerId)
            ?? 'A buyer';

        /*
         * BUYER -> FARMER
         */
        $service->send(
            $farmerId,
            "New Order #{$order->id}",
            "{$buyerName} placed a new order. Please review the order.",
            'order',
            '/farmer/orders',
            'bi-cart-check-fill',
            [
                'order_id' => $order->id,
                'buyer_id' => $buyerId,
            ],
            $buyerId
        );

        /*
         * Confirmation for the actual buyer.
         * Works for Resident OR Admin.
         */
        $service->send(
            $buyerId,
            "Order #{$order->id} Placed",
            'Your order was submitted successfully and is waiting for the farmer.',
            'order',
            $this->buyerOrdersUrl($buyerId, $order->id),
            'bi-bag-check-fill',
            [
                'order_id' => $order->id,
                'farmer_id' => $farmerId,
            ],
            $buyerId
        );
    }


    private function orderUpdated(
        \App\Models\Order $order,
        NotificationService $service
    ): void {
        $farmerId = $this->orderFarmerId($order);
        $buyerId  = $this->orderBuyerId($order);
        $actorId  = Auth::id();

        if (!$farmerId || !$buyerId || $farmerId === $buyerId) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | ORDER STATUS
        |--------------------------------------------------------------------------
        */

        if ($order->wasChanged('status')) {
            $status = strtolower((string) $order->status);

            /*
             * APPROVED
             * Farmer -> Buyer
             */
            if ($status === 'approved') {
                $service->send(
                    $buyerId,
                    "Order #{$order->id} Approved",
                    'The farmer approved your order and is preparing it.',
                    'order',
                    $this->buyerOrdersUrl($buyerId, $order->id),
                    'bi-check-circle-fill',
                    [
                        'order_id' => $order->id,
                        'status' => 'approved',
                    ],
                    $farmerId
                );
            }


            /*
             * CANCELLED
             *
             * If Farmer cancelled:
             * Farmer -> Buyer
             *
             * If Buyer cancelled:
             * Buyer -> Farmer
             */
            if ($status === 'cancelled') {

                if ($actorId && (int) $actorId === $buyerId) {

                    $service->send(
                        $farmerId,
                        "Order #{$order->id} Cancelled",
                        'The buyer cancelled this order.',
                        'order',
                        '/farmer/orders',
                        'bi-x-circle-fill',
                        [
                            'order_id' => $order->id,
                            'status' => 'cancelled',
                        ],
                        $buyerId
                    );

                } else {

                    $service->send(
                        $buyerId,
                        "Order #{$order->id} Cancelled",
                        'The farmer cancelled your order.',
                        'order',
                        $this->buyerOrdersUrl($buyerId, $order->id),
                        'bi-x-circle-fill',
                        [
                            'order_id' => $order->id,
                            'status' => 'cancelled',
                        ],
                        $farmerId
                    );
                }
            }


            /*
             * COMPLETED
             *
             * In your current workflow, COMPLETED happens after the
             * buyer clicks "I Received My Order".
             *
             * Buyer -> Farmer
             */
            if ($status === 'completed') {
                $service->send(
                    $farmerId,
                    "Buyer Confirmed Order #{$order->id}",
                    'The buyer confirmed that the order was received. Transaction completed.',
                    'success',
                    '/farmer/orders',
                    'bi-check2-circle',
                    [
                        'order_id' => $order->id,
                        'status' => 'completed',
                    ],
                    $buyerId
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT
        |--------------------------------------------------------------------------
        |
        | Farmer -> Buyer
        |
        */

        if (
            $order->wasChanged('payment_status') &&
            strtolower((string) $order->payment_status) === 'paid'
        ) {
            $service->send(
                $buyerId,
                "Payment Confirmed - Order #{$order->id}",
                'The farmer confirmed that your payment was received.',
                'payment',
                $this->buyerOrdersUrl($buyerId, $order->id),
                'bi-cash-coin',
                [
                    'order_id' => $order->id,
                    'payment_status' => 'paid',
                ],
                $farmerId
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DELIVERY
        |--------------------------------------------------------------------------
        |
        | Farmer -> Buyer
        |
        */

        if ($order->wasChanged('delivery_status')) {
            $deliveryStatus =
                strtolower((string) $order->delivery_status);

            if ($deliveryStatus === 'out_for_delivery') {
                $service->send(
                    $buyerId,
                    "Order #{$order->id} Out for Delivery",
                    'Your order is now on the way.',
                    'delivery',
                    $this->buyerOrdersUrl($buyerId, $order->id),
                    'bi-truck',
                    [
                        'order_id' => $order->id,
                        'delivery_status' => 'out_for_delivery',
                    ],
                    $farmerId
                );
            }

            if ($deliveryStatus === 'delivered') {
                $service->send(
                    $buyerId,
                    "Order #{$order->id} Delivered",
                    'The farmer marked your order as delivered and uploaded proof of delivery. Please confirm after you receive it.',
                    'delivery',
                    $this->buyerOrdersIndexUrl($buyerId),
                    'bi-box-seam-fill',
                    [
                        'order_id' => $order->id,
                        'delivery_status' => 'delivered',
                    ],
                    $farmerId
                );
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MILLING TRANSACTIONS
    |--------------------------------------------------------------------------
    |
    | Requester <-> Miller only.
    |
    | Supported requesters:
    | - Farmer <-> Miller
    | - Admin  <-> Miller
    |
    | IMPORTANT:
    | Never hardcode Farmer URLs for every milling requester.
    | Admin milling notifications must open Admin milling pages.
    |
    */

    private function millingCreated(
        \App\Models\MillingRequest $request,
        NotificationService $service
    ): void {

        $requesterId =
            $this->millingRequesterId(
                $request
            );

        $millerId =
            $this->intAttr(
                $request,
                ['miller_id']
            );


        if (
            !$requesterId ||
            !$millerId ||
            $requesterId === $millerId
        ) {
            return;
        }


        $requesterRole =
            $this->millingRequesterRole(
                $request,
                $requesterId
            );

        $requesterLabel =
            $requesterRole === 'admin'
                ? 'Admin'
                : 'Farmer';

        $requesterUrl =
            $this->millingRequesterUrl(
                $requesterId,
                $request->id
            );


        /*
         * REQUESTER -> MILLER
         */
        $service->send(
            $millerId,
            "New Milling Request #{$request->id}",
            "{$requesterLabel} submitted a new milling request. Please review it.",
            'milling',
            '/miller/requests',
            'bi-gear-wide-connected',
            [
                'milling_request_id' =>
                    $request->id,

                'requester_id' =>
                    $requesterId,

                'requester_role' =>
                    $requesterRole,
            ],
            $requesterId
        );


        /*
         * Confirmation to the actual requester.
         */
        $service->send(
            $requesterId,
            "Milling Request #{$request->id} Submitted",
            'Your milling request was submitted successfully.',
            'milling',
            $requesterUrl,
            'bi-clipboard-check',
            [
                'milling_request_id' =>
                    $request->id,

                'miller_id' =>
                    $millerId,

                'requester_role' =>
                    $requesterRole,
            ],
            $requesterId
        );
    }


    private function millingUpdated(
        \App\Models\MillingRequest $request,
        NotificationService $service
    ): void {

        $requesterId =
            $this->millingRequesterId(
                $request
            );

        $millerId =
            $this->intAttr(
                $request,
                ['miller_id']
            );


        if (
            !$requesterId ||
            !$millerId ||
            $requesterId === $millerId
        ) {
            return;
        }


        $requesterRole =
            $this->millingRequesterRole(
                $request,
                $requesterId
            );

        $requesterUrl =
            $this->millingRequesterUrl(
                $requesterId,
                $request->id
            );


        /*
         * MILLER -> ACTUAL REQUESTER
         *
         * Admin request  -> Admin notification page
         * Farmer request -> Farmer notification page
         */
        if ($request->wasChanged('status')) {

            $status =
                strtoupper(
                    str_replace(
                        '_',
                        ' ',
                        (string) $request->status
                    )
                );

            $service->send(
                $requesterId,
                "Milling Request #{$request->id}: {$status}",
                "Your milling request status is now {$status}.",
                'milling',
                $requesterUrl,
                'bi-gear-wide-connected',
                [
                    'milling_request_id' =>
                        $request->id,

                    'status' =>
                        $request->status,

                    'requester_role' =>
                        $requesterRole,
                ],
                $millerId
            );
        }


        /*
         * MILLER -> ACTUAL REQUESTER
         * Schedule update.
         */
        $scheduleFields = [
            'scheduled_at',
            'scheduled_date',
            'schedule_date',
            'schedule_time',
            'scheduled_time',
        ];


        foreach ($scheduleFields as $field) {

            if ($request->wasChanged($field)) {

                $service->send(
                    $requesterId,
                    "Milling Schedule Updated #{$request->id}",
                    'Your milling schedule has been assigned or updated. Open My Requests to view the schedule.',
                    'milling',
                    $requesterUrl,
                    'bi-calendar-check-fill',
                    [
                        'milling_request_id' =>
                            $request->id,

                        'requester_role' =>
                            $requesterRole,
                    ],
                    $millerId
                );

                break;
            }
        }


        /*
         * MILLER -> ACTUAL REQUESTER
         * Payment update.
         */
        if (
            $request->wasChanged('payment_status')
        ) {

            $paymentStatus =
                strtoupper(
                    str_replace(
                        '_',
                        ' ',
                        (string) $request->payment_status
                    )
                );

            $service->send(
                $requesterId,
                "Milling Payment #{$request->id}: {$paymentStatus}",
                "Your milling payment status is now {$paymentStatus}.",
                'milling',
                $requesterUrl,
                'bi-cash-coin',
                [
                    'milling_request_id' =>
                        $request->id,

                    'payment_status' =>
                        $request->payment_status,

                    'requester_role' =>
                        $requesterRole,
                ],
                $millerId
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ANNOUNCEMENTS
    |--------------------------------------------------------------------------
    |
    | Admin -> All users.
    |
    | We exclude the Admin who posted it so the sender does not receive
    | their own announcement notification.
    |
    */

    private function announcementCreated(
        \App\Models\Announcement $announcement,
        NotificationService $service
    ): void {
        $title =
            $announcement->title
            ?? 'New ANI-CARE Announcement';

        $message =
            $announcement->message
            ?? 'A new announcement was posted.';

        $excludeUserIds = [];

        if (Auth::id()) {
            $excludeUserIds[] = (int) Auth::id();
        }

        $service->sendToAllUsers(
            "Announcement: {$title}",
            $message,
            'announcement',
            '/dashboard',
            'bi-megaphone-fill',
            [
                'announcement_id' => $announcement->id,
            ],
            Auth::id(),
            $excludeUserIds
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MILLING HELPERS
    |--------------------------------------------------------------------------
    */

    private function millingRequesterId(
        \App\Models\MillingRequest $request
    ): ?int {

        /*
         * New/current preferred column:
         * requester_id
         *
         * Backward-compatible columns:
         * farmer_id
         * user_id
         */
        $id =
            $this->intAttr(
                $request,
                [
                    'requester_id',
                    'farmer_id',
                    'user_id',
                ]
            );

        if ($id) {
            return $id;
        }

        return Auth::id()
            ? (int) Auth::id()
            : null;
    }


    private function millingRequesterRole(
        \App\Models\MillingRequest $request,
        int $requesterId
    ): string {

        $savedRole =
            strtolower(
                trim(
                    (string) (
                        $request->getAttribute(
                            'requester_role'
                        )
                        ?? ''
                    )
                )
            );

        if (
            in_array(
                $savedRole,
                [
                    'admin',
                    'farmer',
                ],
                true
            )
        ) {
            return $savedRole;
        }


        $actualRole =
            $this->userRole(
                $requesterId
            );

        if (
            in_array(
                $actualRole,
                [
                    'admin',
                    'farmer',
                ],
                true
            )
        ) {
            return $actualRole;
        }


        /*
         * Legacy milling requests were Farmer requests.
         */
        return 'farmer';
    }


    private function millingRequesterUrl(
        int $requesterId,
        int $requestId
    ): string {

        $role =
            $this->userRole(
                $requesterId
            );


        if ($role === 'admin') {

            /*
             * Admin has a dedicated show/details route.
             */
            return
                "/admin/milling/requests/{$requestId}";
        }


        /*
         * Farmer currently uses the requests index page.
         */
        return '/farmer/milling/requests';
    }


    /*
    |--------------------------------------------------------------------------
    | ORDER HELPERS
    |--------------------------------------------------------------------------
    */

    private function orderFarmerId(
        \App\Models\Order $order
    ): ?int {
        return $this->intAttr(
            $order,
            ['farmer_id', 'seller_id']
        );
    }


    private function orderBuyerId(
        \App\Models\Order $order
    ): ?int {
        /*
         * Preferred future column:
         * buyer_id
         *
         * Current ANI-CARE column:
         * resident_id
         *
         * We also fall back to user_id / authenticated user so the observer
         * can support Admin buyers if your checkout stores the Admin there.
         */
        $id = $this->intAttr(
            $order,
            [
                'buyer_id',
                'resident_id',
                'user_id',
            ]
        );

        if ($id) {
            return $id;
        }

        return Auth::id()
            ? (int) Auth::id()
            : null;
    }


    private function buyerOrdersUrl(
        int $buyerId,
        int $orderId
    ): string {
        $role = $this->userRole($buyerId);

        /*
         * If you later create dedicated Admin buyer order routes,
         * change this URL to that route/path.
         *
         * For now, admin buyers are taken to the Admin marketplace.
         */
        if ($role === 'admin') {
            return '/admin/market';
        }

        return "/resident/orders/{$orderId}";
    }


    private function buyerOrdersIndexUrl(
        int $buyerId
    ): string {
        $role = $this->userRole($buyerId);

        if ($role === 'admin') {
            return '/admin/market';
        }

        return '/resident/orders';
    }


    private function userRole(
        int $userId
    ): ?string {
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return null;
        }

        return strtolower(
            (string) $user->role
        );
    }


    private function userDisplayName(
        int $userId
    ): ?string {
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return null;
        }

        return
            $user->fullname
            ?? $user->username
            ?? $user->email;
    }


    /*
    |--------------------------------------------------------------------------
    | GENERIC ATTRIBUTE HELPER
    |--------------------------------------------------------------------------
    */

    private function intAttr(
        EloquentModel $model,
        array $candidates
    ): ?int {
        foreach ($candidates as $name) {

            $value =
                $model->getAttribute($name);

            if (
                $value !== null &&
                $value !== ''
            ) {
                return (int) $value;
            }
        }

        return null;
    }
}