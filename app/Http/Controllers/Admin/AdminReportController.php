<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\VatService;
use App\Models\MillingRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AdminReportController extends Controller
{
    private function requireAdmin(): void
    {
        $user = Auth::user();

        if (!$user || strtolower((string) $user->role) !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }

    public function index(Request $request)
    {
        $this->requireAdmin();

        $type = $request->input('type', 'all');
        $status = trim((string) $request->input('status', ''));
        $from = $request->input('from');
        $to = $request->input('to');

        if ($from && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
            $from = null;
        }

        if ($to && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
            $to = null;
        }

        $ordersQuery = Order::query()
            ->with(['product', 'resident', 'farmer'])
            ->latest();

        $millingQuery = MillingRequest::query()
            ->with(['requester', 'farmer', 'miller'])
            ->latest();

        if ($status !== '') {
            $ordersQuery->where('status', $status);
            $millingQuery->where('status', $status);
        }

        if ($from) {
            $ordersQuery->whereDate('created_at', '>=', $from);
            $millingQuery->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $ordersQuery->whereDate('created_at', '<=', $to);
            $millingQuery->whereDate('created_at', '<=', $to);
        }

        $orders = in_array($type, ['all', 'product'], true)
            ? $ordersQuery->get()
            : collect();

        $millingRequests = in_array($type, ['all', 'milling'], true)
            ? $millingQuery->get()
            : collect();

        $allStatuses = collect()
            ->merge($orders->pluck('status'))
            ->merge($millingRequests->pluck('status'))
            ->filter()
            ->map(fn ($value) => strtolower((string) $value))
            ->unique()
            ->sort()
            ->values();

        $productTotal = $orders->sum(function ($order) {
            return (float) ($order->grand_total ?? $order->total_price ?? 0);
        });

        $millingTotal = $millingRequests->sum(function ($request) {
            return (float) ($request->total_amount ?? 0);
        });

        $completedCount = $orders->whereIn('status', ['completed', 'Complete', 'complete'])->count()
            + $millingRequests->whereIn('status', ['completed', 'Complete', 'complete'])->count();

        $pendingCount = $orders->whereIn('status', ['pending', 'Pending'])->count()
            + $millingRequests->whereIn('status', ['pending', 'Pending'])->count();

        $totalTransactions = $orders->count() + $millingRequests->count();

        $vatEnabled = VatService::enabled();

        return view('admin.reports', compact(
            'orders',
            'millingRequests',
            'type',
            'status',
            'from',
            'to',
            'allStatuses',
            'productTotal',
            'millingTotal',
            'completedCount',
            'pendingCount',
            'totalTransactions',
            'vatEnabled'
        ));
    }
}
