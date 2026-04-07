<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // ✅ ADD THIS
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    private function validateLatLng(Request $request): array
    {
        return $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);
    }

    public function saveFarmer(Request $request)
    {
        $user = Auth::user();
        if (!$user || ($user->role ?? '') !== 'farmer') {
            abort(403);
        }

        $data = $this->validateLatLng($request);

        $user->latitude  = $data['latitude'];
        $user->longitude = $data['longitude'];
        $user->save();

        return back()->with('success', '✅ Farm location saved successfully!');
    }

    public function saveMiller(Request $request)
    {
        $user = Auth::user();
        if (!$user || ($user->role ?? '') !== 'miller') {
            abort(403);
        }

        $data = $this->validateLatLng($request);

        $user->latitude  = $data['latitude'];
        $user->longitude = $data['longitude'];
        $user->save();

        return back()->with('success', '✅ Milling location saved successfully!');
    }
}