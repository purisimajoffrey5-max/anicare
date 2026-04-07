<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'fullname' => ['required', 'string', 'min:3', 'max:100'],
            'username' => ['required', 'string', 'min:4', 'max:30', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'barangay' => ['required', 'string', 'max:100'],
            'role' => ['required', 'in:resident,farmer,miller'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/'
            ],
        ]);

        User::create([
            'fullname' => $data['fullname'],
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'barangay' => $data['barangay'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
            'is_approved' => 0,
            'approved_at' =>  null,
        ]);

        if (in_array($data['role'], ['farmer', 'miller', 'resident'])) {
            return redirect()->route('login')->with('success', 'Account created successfully. Please wait for admin approval before logging in.');
        }

        return redirect()->route('login')->with('success', 'Account created successfully. You may now log in.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['login' => 'Invalid username or password.'])
                ->withInput($request->only('username'));
        }

        if ((int) $user->is_approved !== 1) {
            return back()
                ->withErrors(['login' => 'Your account is still pending admin approval.'])
                ->withInput($request->only('username'));
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard.redirect');
    }

    public function redirectDashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'farmer' => redirect()->route('farmer.dashboard'),
            'miller' => redirect()->route('miller.dashboard'),
            'resident' => redirect()->route('resident.dashboard'),
            default => redirect()->route('login'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}