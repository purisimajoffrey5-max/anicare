<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $maintenanceEnabled = (bool) (DB::table('system_settings')
            ->where('key', 'maintenance_mode')
            ->value('value') ?? false);

        $maintenanceMessage = DB::table('system_settings')
            ->where('key', 'maintenance_message')
            ->value('value')
            ?? 'ANI-CARE is temporarily unavailable while system maintenance is being performed. Please try again later.';

        return view('admin.settings.index', compact(
            'maintenanceEnabled',
            'maintenanceMessage'
        ));
    }
}
