<?php

namespace App\Http\Controllers;

use App\Models\InAppNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(
        Request $request
    ): View {

        $user =
            $request->user();

        $notifications =
            InAppNotification::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->latest('created_at')
                ->paginate(20);

        $unreadCount =
            InAppNotification::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->where(
                    'is_read',
                    false
                )
                ->count();


        return view(
            'notifications.index',
            compact(
                'notifications',
                'unreadCount'
            )
        );
    }


    public function open(
        Request $request,
        InAppNotification $notification
    ): RedirectResponse {

        $user =
            $request->user();


        /*
        |--------------------------------------------------------------------------
        | SECURITY
        |--------------------------------------------------------------------------
        |
        | A notification can only be opened by its owner.
        |
        */

        abort_unless(
            (int) $notification->user_id ===
                (int) $user->id,
            403,
            'Unauthorized notification.'
        );


        $notification->markAsRead();


        /*
        |--------------------------------------------------------------------------
        | FIX LEGACY MILLING NOTIFICATION LINKS
        |--------------------------------------------------------------------------
        |
        | Older ANI-CARE notifications stored:
        |
        | /farmer/milling/requests
        |
        | even when the actual requester was Admin.
        |
        | That caused:
        |
        | Admin clicks notification
        |      ↓
        | /farmer/milling/requests
        |      ↓
        | Farmer role check
        |      ↓
        | 403 FORBIDDEN
        |
        | We resolve the destination from the CURRENT USER ROLE so existing
        | notifications already stored in the database also start working.
        |
        */

        $type =
            strtolower(
                trim(
                    (string) (
                        $notification->type
                        ?? ''
                    )
                )
            );

        $data =
            $this->notificationData(
                $notification
            );

        $millingRequestId =
            isset(
                $data['milling_request_id']
            )
                ? (int) $data['milling_request_id']
                : null;


        if (
            $type === 'milling' ||
            $millingRequestId
        ) {
            return redirect(
                $this->millingUrl(
                    $user->role,
                    $millingRequestId
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NORMAL SAVED URL
        |--------------------------------------------------------------------------
        */

        $url =
            trim(
                (string) (
                    $notification->url
                    ?? ''
                )
            );


        /*
         * Only permit internal relative URLs.
         */
        if (
            $url !== '' &&
            str_starts_with(
                $url,
                '/'
            ) &&
            !str_starts_with(
                $url,
                '//'
            )
        ) {
            return redirect(
                $url
            );
        }


        return redirect(
            $this->dashboardUrl(
                $user->role
            )
        );
    }


    public function readAll(
        Request $request
    ): RedirectResponse {

        InAppNotification::query()
            ->where(
                'user_id',
                $request->user()->id
            )
            ->where(
                'is_read',
                false
            )
            ->update([
                'is_read' =>
                    true,

                'read_at' =>
                    now(),
            ]);


        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MILLING DESTINATION
    |--------------------------------------------------------------------------
    */

    private function millingUrl(
        ?string $role,
        ?int $requestId
    ): string {

        $role =
            strtolower(
                trim(
                    (string) $role
                )
            );


        return match ($role) {

            /*
             * Admin requested the milling service.
             *
             * If we know the request ID, open the exact transaction.
             */
            'admin' =>
                $requestId
                    ? "/admin/milling/requests/{$requestId}"
                    : '/admin/milling/requests',

            /*
             * Farmer milling page currently uses the request list.
             */
            'farmer' =>
                '/farmer/milling/requests',

            /*
             * Miller sees assigned/received milling requests here.
             */
            'miller' =>
                '/miller/requests',

            default =>
                '/dashboard',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | NORMAL DASHBOARD DESTINATION
    |--------------------------------------------------------------------------
    */

    private function dashboardUrl(
        ?string $role
    ): string {

        return match (
            strtolower(
                (string) $role
            )
        ) {
            'admin' =>
                '/admin/dashboard',

            'farmer' =>
                '/farmer/dashboard',

            'miller' =>
                '/miller/dashboard',

            'resident' =>
                '/resident/dashboard',

            default =>
                '/dashboard',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION DATA
    |--------------------------------------------------------------------------
    */

    private function notificationData(
        InAppNotification $notification
    ): array {

        $data =
            $notification->data
            ?? [];


        if (is_array($data)) {
            return $data;
        }


        if (is_object($data)) {
            return (array) $data;
        }


        if (
            is_string($data) &&
            trim($data) !== ''
        ) {

            $decoded =
                json_decode(
                    $data,
                    true
                );

            if (
                is_array($decoded)
            ) {
                return $decoded;
            }
        }


        return [];
    }
}