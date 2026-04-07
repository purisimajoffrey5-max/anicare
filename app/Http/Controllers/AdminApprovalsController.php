<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminApprovalsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));
        $role   = $request->get('role', 'all');
        $status = $request->get('status', 'pending');

        $query = User::query()
            ->whereIn('role', ['resident', 'farmer', 'miller']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('barangay', 'like', "%{$search}%");
            });
        }

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        if ($status === 'pending') {
            $query->where('is_approved', 0);
        } elseif ($status === 'approved') {
            $query->where('is_approved', 1);
        }

        $users = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        $pendingCount = User::whereIn('role', ['resident', 'farmer', 'miller'])
            ->where('is_approved', 0)
            ->count();

        $approvedCount = User::whereIn('role', ['resident', 'farmer', 'miller'])
            ->where('is_approved', 1)
            ->count();

        return view('admin.approvals', compact(
            'users',
            'pendingCount',
            'approvedCount',
            'search',
            'role',
            'status'
        ));
    }

    public function approve($id)
    {
        $user = User::whereIn('role', ['resident', 'farmer', 'miller'])->findOrFail($id);

        $user->is_approved = 1;
        $user->approved_at = now();
        $user->save();

        return back()->with('success', 'User approved successfully.');
    }

    public function revoke($id)
    {
        $user = User::whereIn('role', ['resident', 'farmer', 'miller'])->findOrFail($id);

        $user->is_approved = 0;
        $user->approved_at = null;
        $user->save();

        return back()->with('success', 'User approval revoked successfully.');
    }
}