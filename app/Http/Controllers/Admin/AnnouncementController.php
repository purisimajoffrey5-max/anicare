<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    private function requireAdmin(): void
    {
        $u = Auth::user();
        if (!$u || ($u->role ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->requireAdmin();

        $announcements = Announcement::active()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $this->requireAdmin();

        $data = $request->validate([
            'title'   => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        Announcement::create([
            'admin_id' => Auth::id(),
            'title'    => $data['title'],
            'message'  => $data['message'],
            'status'   => 'active',
        ]);

        return back()->with('success', 'Announcement posted!');
    }

    // ✅ TAPOS NA (archive)
    public function archive($id)
    {
        $this->requireAdmin();

        $a = Announcement::findOrFail($id);
        $a->status = 'archived';
        $a->archived_at = now();
        $a->save();

        return back()->with('success', 'Announcement archived (tapos na).');
    }

    public function library()
    {
        $this->requireAdmin();

        $archived = Announcement::archived()
            ->orderByDesc('archived_at')
            ->paginate(10);

        return view('admin.announcements.library', compact('archived'));
    }

    public function restore($id)
    {
        $this->requireAdmin();

        $a = Announcement::findOrFail($id);
        $a->status = 'active';
        $a->archived_at = null;
        $a->save();

        return back()->with('success', 'Announcement restored to active.');
    }

    // optional permanent delete (library)
    public function destroy($id)
    {
        $this->requireAdmin();

        $a = Announcement::findOrFail($id);
        $a->delete();

        return back()->with('success', 'Announcement permanently deleted.');
    }
}