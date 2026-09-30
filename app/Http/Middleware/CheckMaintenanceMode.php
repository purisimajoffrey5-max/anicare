<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------------------------------
        | 1. AUTHENTICATION ROUTES MUST ALWAYS BE ACCESSIBLE
        |--------------------------------------------------------------------------
        |
        | Users need to reach the login page.
        | This is especially important because Admin needs to log in
        | so the Admin can disable Maintenance Mode.
        |
        */

        if (
            $request->routeIs(
                'login',
                'login.post',
                'register',
                'register.post',
                'register.otp.form',
                'register.otp.verify',
                'register.otp.resend',
                'forgot.password',
                'forgot.password.send',
                'otp.form',
                'otp.verify',
                'password.reset.form',
                'password.reset'
            )
        ) {
            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | 2. ADMIN USERS CAN ALWAYS ACCESS THE SYSTEM
        |--------------------------------------------------------------------------
        |
        | Admin must be able to open the dashboard and disable
        | Maintenance Mode.
        |
        */

        if (
            Auth::check() &&
            in_array(strtolower((string) Auth::user()->role), ['admin', 'super_admin'], true)
        ) {
            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | 3. CHECK MAINTENANCE SETTING
        |--------------------------------------------------------------------------
        */

        try {
            $enabled = (bool) (
                DB::table('system_settings')
                    ->where('key', 'maintenance_mode')
                    ->value('value')
                ?? false
            );
        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | If the settings table does not exist yet,
            | allow the application to continue normally.
            |--------------------------------------------------------------------------
            */

            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | 4. MAINTENANCE OFF
        |--------------------------------------------------------------------------
        */

        if (!$enabled) {
            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | 5. MAINTENANCE ON
        |--------------------------------------------------------------------------
        |
        | At this point:
        | - Guest users are blocked from protected pages
        | - Non-admin authenticated users are blocked
        | - Admin users already passed above
        |
        */

        $message = DB::table('system_settings')
            ->where('key', 'maintenance_message')
            ->value('value')
            ?? 'ANI-CARE is temporarily unavailable while system maintenance is being performed. Please try again later.';


        return response()->view(
            'maintenance.index',
            [
                'message' => $message,
            ],
            503
        );
    }
}