<?php

namespace App\Http\Controllers\Miller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'miller') abort(403);

        return view('miller.profile', compact('user'));
    }
}