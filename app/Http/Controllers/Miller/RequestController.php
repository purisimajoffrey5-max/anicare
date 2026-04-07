<?php

namespace App\Http\Controllers\Miller;

use App\Http\Controllers\Controller;
use App\Models\MillingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    private function requireMiller(): void
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'miller') abort(403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->requireMiller();

        $status = $request->get('status', 'pending');

        $q = MillingRequest::query()->orderByDesc('created_at');

        if ($status !== 'all') {
            $q->where('status', $status);
        }

        $requests = $q->paginate(10);

        return view('miller.requests', compact('requests', 'status'));
    }

    public function approve($id)
    {
        $this->requireMiller();

        $mr = MillingRequest::findOrFail($id);
        $mr->status = 'approved';
        $mr->miller_id = Auth::id(); // assign current miller
        $mr->save();

        return back()->with('success', 'Request approved.');
    }

    public function reject($id)
    {
        $this->requireMiller();

        $mr = MillingRequest::findOrFail($id);
        $mr->status = 'rejected';
        $mr->miller_id = Auth::id();
        $mr->save();

        return back()->with('success', 'Request rejected.');
    }

    public function complete($id)
    {
        $this->requireMiller();

        $mr = MillingRequest::findOrFail($id);
        $mr->status = 'completed';
        $mr->save();

        return back()->with('success', 'Request marked as completed.');
    }
}