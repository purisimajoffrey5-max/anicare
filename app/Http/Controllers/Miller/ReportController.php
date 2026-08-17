<?php

namespace App\Http\Controllers\Miller;

use App\Http\Controllers\Controller;
use App\Models\MillingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    private function requireMiller(): void
    {
        $user = Auth::user();

        if (
            !$user ||
            strtolower((string) $user->role) !== 'miller'
        ) {
            abort(403, 'Unauthorized');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MILLER - COMPLETED MILLING REPORTS
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | MillingRequest does NOT have a "user" relationship.
    |
    | Current workflow:
    | requester -> Admin or Farmer
    | farmer    -> legacy Farmer requester
    | miller    -> selected Miller
    |
    | So we eager-load requester + farmer and use the correct fallback.
    |
    */

    public function index(): View
    {
        $this->requireMiller();

        $reports = MillingRequest::query()
            ->with([
                'requester',
                'farmer',
            ])
            ->where(
                'miller_id',
                Auth::id()
            )
            ->where(
                'status',
                'completed'
            )
            ->orderByDesc(
                'completed_at'
            )
            ->orderByDesc(
                'updated_at'
            )
            ->paginate(10);

        return view(
            'miller.reports',
            compact('reports')
        );
    }
}