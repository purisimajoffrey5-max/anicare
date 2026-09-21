<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function edit()
    {
        $enabled = (bool) (DB::table('system_settings')
            ->where('key', 'maintenance_mode')
            ->value('value') ?? false);

        $message = DB::table('system_settings')
            ->where('key', 'maintenance_message')
            ->value('value')
            ?? 'ANI-CARE is temporarily unavailable while system maintenance is being performed. Please try again later.';

        return view('admin.settings.maintenance', [
            'maintenanceEnabled' => $enabled,
            'maintenanceMessage' => $message,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'maintenance_enabled' => ['required', 'boolean'],
            'maintenance_message' => ['nullable', 'string', 'max:500'],
        ]);

        $message = trim($validated['maintenance_message'] ?? '');

        if ($message === '') {
            $message = 'ANI-CARE is temporarily unavailable while system maintenance is being performed. Please try again later.';
        }

        DB::table('system_settings')->updateOrInsert(
            ['key' => 'maintenance_mode'],
            [
                'value' => $validated['maintenance_enabled'] ? '1' : '0',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        DB::table('system_settings')->updateOrInsert(
            ['key' => 'maintenance_message'],
            [
                'value' => $message,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect()
            ->route('admin.settings')
            ->with('success', $validated['maintenance_enabled']
                ? 'Maintenance Mode enabled. Non-admin users will see the maintenance page.'
                : 'Maintenance Mode disabled. ANI-CARE is available again.');
    }
}
