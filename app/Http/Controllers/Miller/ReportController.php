<?php

namespace App\Http\Controllers\Miller;

use App\Http\Controllers\Controller;
use App\Models\MillingRequest;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private function requireMiller(): void
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'miller') abort(403, 'Unauthorized');
    }

    public function index()
    {
        $this->requireMiller();

        $reports = MillingRequest::where('status', 'completed')
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('miller.reports', compact('reports'));
    }
}