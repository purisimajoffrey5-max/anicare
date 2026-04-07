<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmProfileController extends Controller
{
    private function requireFarmer(): void
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'farmer') abort(403, 'Unauthorized');
    }

    public function show()
    {
        $this->requireFarmer();
        $user = Auth::user();

        $profile = FarmProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'address' => '',
                'barangay' => '',
                'municipality' => 'Allacapan',
                'province' => 'Cagayan',
                'farm_size_hectares' => 0,
                'contact_no' => '',
            ]
        );

        return view('farmer.profile', compact('profile', 'user'));
    }

    public function update(Request $request)
    {
        $this->requireFarmer();
        $user = Auth::user();

        $data = $request->validate([
            'address' => ['nullable','string','max:255'],
            'barangay' => ['nullable','string','max:100'],
            'municipality' => ['nullable','string','max:100'],
            'province' => ['nullable','string','max:100'],
            'farm_size_hectares' => ['nullable','numeric','min:0'],
            'contact_no' => ['nullable','string','max:50'],
        ]);

        $profile = FarmProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return back()->with('success', 'Farm profile updated successfully.');
    }
}