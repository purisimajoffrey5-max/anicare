<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function requireFarmer(): void
    {
        $u = Auth::user();
        if (!$u || ($u->role ?? '') !== 'farmer') abort(403, 'Unauthorized');
    }

    public function index()
    {
        $this->requireFarmer();

        $user = Auth::user();

        $announcements = Announcement::active()
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('dashboards.farmer', compact('user', 'announcements'));
    }
}