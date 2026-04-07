<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminFarmersMillersController extends Controller
{
    private function requireAdmin(): void
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }

    public function index(Request $request)
    {
        $this->requireAdmin();

        $search = trim((string) $request->query('q', ''));
        $role   = $request->query('role', 'all');     // farmer / miller / all
        $status = $request->query('status', 'all');   // pending / approved / all

        $query = User::query()->whereIn('role', ['farmer', 'miller']);

        if ($role !== 'all' && in_array($role, ['farmer','miller'], true)) {
            $query->where('role', $role);
        }

        if ($status === 'pending') {
            $query->where('is_approved', false);
        } elseif ($status === 'approved') {
            $query->where('is_approved', true);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $totalFarmers = User::where('role', 'farmer')->count();
        $totalMillers = User::where('role', 'miller')->count();
        $pendingCount = User::whereIn('role', ['farmer','miller'])->where('is_approved', 0)->count();

        return view('admin.farmers_millers', compact(
            'users','search','role','status','totalFarmers','totalMillers','pendingCount'
        ));
    }

    public function show($id)
    {
        $this->requireAdmin();

        $user = User::whereIn('role', ['farmer','miller'])->findOrFail($id);
        return view('admin.farmers_millers_show', compact('user'));
    }

    public function destroy($id)
    {
        $this->requireAdmin();

        $user = User::findOrFail($id);

        // Safety: do not allow deleting admin
        if ($user->role === 'admin') {
            return back()->withErrors(['delete' => 'You cannot delete an admin account.']);
        }

        // Only allow deleting farmer/miller from this module
        if (!in_array($user->role, ['farmer','miller'], true)) {
            return back()->withErrors(['delete' => 'Invalid user role.']);
        }

        $user->delete();

        return redirect()->route('admin.farmers_millers')
            ->with('success', 'User deleted successfully.');
    }
}