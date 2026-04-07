<?php

namespace App\Http\Controllers\Miller;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function requireMiller(): void
    {
        $u = Auth::user();
        if (!$u || ($u->role ?? '') !== 'miller') {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->requireMiller();

        /** @var User $user */
        $user = Auth::user();

        // ✅ Announcements (active only)
        $announcements = Announcement::active()
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('dashboards.miller', compact('user', 'announcements'));
    }

    public function toggleOpen(Request $request)
    {
        $this->requireMiller();

        /** @var User $u */
        $u = Auth::user();

        $u->is_open = !((bool) $u->is_open);
        $u->save();

        return redirect()->route('miller.dashboard')
            ->with('success', $u->is_open ? 'Status set to OPEN' : 'Status set to CLOSED');
    }
}