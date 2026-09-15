<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VatSettingsController extends Controller
{
    public function edit()
    {
        $settings = DB::table('vat_settings')->first();

        if (!$settings) {
            DB::table('vat_settings')->insert([
                'enabled' => false,
                'rate' => 12.0000,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $settings = DB::table('vat_settings')->first();
        }

        return view('admin.vat-settings', [
            'vatEnabled' => (bool) $settings->enabled,
            'vatRate' => (float) $settings->rate,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'vat_enabled' => ['required', 'boolean'],
            'vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        DB::table('vat_settings')->updateOrInsert(
            ['id' => 1],
            [
                'enabled' => (bool) $validated['vat_enabled'],
                'rate' => (float) $validated['vat_rate'],
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return redirect()
            ->route('admin.reports.vat.settings')
            ->with('success', 'Central VAT setting updated successfully.');
    }
}
