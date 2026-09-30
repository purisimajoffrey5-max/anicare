<?php

namespace App\Http\Controllers;

use App\Models\SecurityEvent;
use App\Models\SuperAdminAuditLog;
use App\Models\User;
use App\Services\SuperAdminBackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $securityStats = [
            'failed_logins' => SecurityEvent::where(
                'event_type',
                'failed_login'
            )
            ->where('created_at', '>=', now()->subHours(24))
            ->count(),

            'security_events' => SecurityEvent::where(
                'created_at',
                '>=',
                now()->subHours(24)
            )->count(),

            'suspicious_requests' => SecurityEvent::where(
                'event_type',
                'suspicious_request'
            )
            ->where('created_at', '>=', now()->subHours(24))
            ->count(),

            'suspicious_files' => 0,

            'high_risk' => SecurityEvent::where(
                'risk_level',
                'high'
            )
            ->where('created_at', '>=', now()->subHours(24))
            ->count(),

            'medium_risk' => SecurityEvent::where(
                'risk_level',
                'medium'
            )
            ->where('created_at', '>=', now()->subHours(24))
            ->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Determine overall risk
        |--------------------------------------------------------------------------
        */

        if ($securityStats['high_risk'] > 0) {
            $securityRisk = 'HIGH';
            $securityRiskClass = 'danger';
        } elseif ($securityStats['medium_risk'] > 0) {
            $securityRisk = 'MEDIUM';
            $securityRiskClass = 'warning';
        } else {
            $securityRisk = 'LOW';
            $securityRiskClass = 'success';
        }

        /*
        |--------------------------------------------------------------------------
        | Recent Security Events
        |--------------------------------------------------------------------------
        */

        $securityEvents = SecurityEvent::with('user')
            ->latest()
            ->limit(15)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Existing Super Admin Audit Logs
        |--------------------------------------------------------------------------
        */

        $logs = SuperAdminAuditLog::with('user')
            ->latest()
            ->limit(20)
            ->get();

        return view('super-admin.dashboard', [

            /*
            |--------------------------------------------------------------------------
            | User Statistics
            |--------------------------------------------------------------------------
            */

            'users' => User::count(),

            'admins' => User::where(
                'role',
                'admin'
            )->count(),

            'superAdmins' => User::where(
                'role',
                'super_admin'
            )->count(),

            'farmers' => User::where(
                'role',
                'farmer'
            )->count(),

            'millers' => User::where(
                'role',
                'miller'
            )->count(),

            'residents' => User::where(
                'role',
                'resident'
            )->count(),

            /*
            |--------------------------------------------------------------------------
            | Security Monitoring
            |--------------------------------------------------------------------------
            */

            'securityStats' => $securityStats,

            'securityRisk' => $securityRisk,

            'securityRiskClass' => $securityRiskClass,

            'securityEvents' => $securityEvents,

            /*
            |--------------------------------------------------------------------------
            | Super Admin Audit Logs
            |--------------------------------------------------------------------------
            */

            'logs' => $logs,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE BACKUP
    |--------------------------------------------------------------------------
    */

    public function backup(SuperAdminBackupService $service)
    {
        $result = $service->create();

        $this->audit(
            'backup.created',
            'Created a full recovery snapshot: ' . $result['filename']
        );

        return response()
            ->download(
                $result['path'],
                $result['filename']
            )
            ->deleteFileAfterSend(true);
    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE FORM
    |--------------------------------------------------------------------------
    */

    public function restoreForm()
    {
        return view('super-admin.restore');
    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE BACKUP
    |--------------------------------------------------------------------------
    */

    public function restore(
        Request $request,
        SuperAdminBackupService $service
    ) {
        $data = $request->validate([
            'backup' => [
                'required',
                'file',
                'mimes:zip',
                'max:512000'
            ],

            'password' => [
                'required',
                'string'
            ],
        ]);

        if (!Hash::check(
            $data['password'],
            Auth::user()->password
        )) {

            return back()->withErrors([
                'password' =>
                    'Super Admin password is incorrect.'
            ]);
        }

        $path = $data['backup']->storeAs(
            'super-admin-temp',
            'restore-' .
            now()->format('YmdHis') .
            '-' .
            bin2hex(random_bytes(4)) .
            '.zip',
            'local'
        );

        try {

            $result = $service->restore(
                Storage::disk('local')->path($path)
            );

            $this->audit(
                'backup.restored',
                'Restored database rows: ' .
                $result['restored_rows'] .
                '; skipped tables: ' .
                $result['skipped_tables']
            );

            return redirect()
                ->route('super-admin.dashboard')
                ->with(
                    'success',
                    'Recovery completed. Restored ' .
                    $result['restored_rows'] .
                    ' database rows.'
                );

        } catch (\Throwable $e) {

            $this->audit(
                'backup.restore_failed',
                $e->getMessage()
            );

            return back()->withErrors([
                'backup' =>
                    'Recovery failed: ' .
                    $e->getMessage()
            ]);

        } finally {

            Storage::disk('local')->delete($path);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | AUDIT LOG
    |--------------------------------------------------------------------------
    */

    private function audit(
        string $action,
        string $details
    ): void {

        SuperAdminAuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'ip_address' => request()->ip(),
            'details' => $details,
        ]);
    }
}