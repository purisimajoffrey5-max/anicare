<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // ✅ ADD THIS
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    private function validateLatLng(Request $request): array
    {
        return $request->validate([
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);
    }

    public function saveFarmer(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || ($user->role ?? '') !== 'farmer') {
            abort(403);
        }

        $data = $this->validateLatLng($request);

        $user->latitude  = $data['latitude'] ?? null;
        $user->longitude = $data['longitude'] ?? null;
        $user->save();

        return back()->with('success', '✅ Farm location saved successfully!');
    }

    public function saveMiller(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || ($user->role ?? '') !== 'miller') {
            abort(403);
        }

        $data = $this->validateLatLng($request);

        $user->latitude  = $data['latitude'] ?? null;
        $user->longitude = $data['longitude'] ?? null;
        $user->save();

        return back()->with('success', '✅ Milling location saved successfully!');
    }
}