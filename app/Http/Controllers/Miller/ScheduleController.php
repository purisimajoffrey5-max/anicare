<?php

namespace App\Http\Controllers\Miller;

use App\Http\Controllers\Controller;
use App\Models\MillingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    private function requireMiller(): void
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'miller') abort(403, 'Unauthorized');
    }

    public function index()
    {
        $this->requireMiller();

        $approved = MillingRequest::where('status', 'approved')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('miller.schedule', compact('approved'));
    }

    public function setSchedule(Request $request, $id)
    {
        $this->requireMiller();

        $data = $request->validate([
            'scheduled_at' => ['required', 'date'],
        ]);

        $mr = MillingRequest::findOrFail($id);
        $mr->scheduled_at = $data['scheduled_at'];
        $mr->miller_id = Auth::id();
        $mr->save();

        return back()->with('success', 'Schedule saved.');
    }
}