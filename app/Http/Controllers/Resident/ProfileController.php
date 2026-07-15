<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private function requireResident(): void
    {
        $u = Auth::user();

        if (!$u || $u->role !== 'resident') {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->requireResident();

        $user = Auth::user();

        return view('resident.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $this->requireResident();

        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}