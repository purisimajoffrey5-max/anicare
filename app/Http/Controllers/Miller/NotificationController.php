<?php

namespace App\Http\Controllers\Miller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\InAppNotification;

class NotificationController extends Controller
{
    private function requireMiller(): void
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'miller') abort(403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->requireMiller();

        $user = Auth::user();
        $notifications = InAppNotification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('miller.notifications', compact('notifications'));
    }

    public function markAsRead(Request $request, int $id)
    {
        $this->requireMiller();

        $n = InAppNotification::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $n->read_at = now();
        $n->save();

        return back()->with('success', 'Notification marked as read');
    }
}

