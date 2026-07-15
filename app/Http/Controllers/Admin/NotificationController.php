<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InAppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private function requireAdmin(): void
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'admin') abort(403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->requireAdmin();

        $notifications = InAppNotification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.notifications', compact('notifications'));
    }

    public function markAsRead(Request $request, $id)
    {
        $this->requireAdmin();

        $notification = InAppNotification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->read_at = now();
        $notification->save();

        return back()->with('success', 'Notification marked as read.');
    }
}
