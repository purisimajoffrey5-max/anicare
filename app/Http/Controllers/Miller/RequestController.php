<?php

namespace App\Http\Controllers\Miller;

use App\Http\Controllers\Controller;
use App\Models\MillingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        // Default to show both pending and assigned so millers see assigned work
        $statusParam = $request->get('status');

        $q = MillingRequest::query()->orderByDesc('created_at');

        if (!$statusParam) {
            // show pending and assigned by default
            $q->whereIn('status', ['pending', 'assigned']);
            $status = 'pending,assigned';
        } else if ($statusParam === 'all') {
            $status = 'all';
        } else if (str_contains($statusParam, ',')) {
            $parts = array_map('trim', explode(',', $statusParam));
            $q->whereIn('status', $parts);
            $status = $statusParam;
        } else {
            $q->where('status', $statusParam);
            $status = $statusParam;
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

    public function accept($id)
    {
        $this->requireMiller();

        $mr = MillingRequest::findOrFail($id);
        if ($mr->status !== 'assigned' || $mr->miller_id !== Auth::id()) {
            abort(403, 'Invalid request acceptance.');
        }

        $mr->status = 'approved';
        $mr->save();

        return back()->with('success', 'Assigned request accepted.');
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

        if ($mr->miller_id !== Auth::id()) {
            abort(403, 'You can only complete your own milling requests.');
        }

        if ($mr->status === 'completed') {
            return back()->withErrors(['request' => 'This request is already completed.']);
        }

        if (! in_array($mr->status, ['approved'])) {
            return back()->withErrors(['request' => 'Request must be approved before completion.']);
        }

        $mr->status = 'completed';
        $mr->save();

        if ($mr->inventoryItem && $mr->inventoryItem->product_type === 'palay') {
            $mr->inventoryItem->status = 'milled';
            $mr->inventoryItem->kilos_available = 0;
            $mr->inventoryItem->notes = 'Palay processed to rice from milling request #' . $mr->id;
            $mr->inventoryItem->save();

            // Estimate rice yield from palay using configured conversion rate
            $conversionRate = config('milling.conversion_rate', 0.65);
            $estimatedRiceKilos = round($mr->kilos * $conversionRate, 2);

            // Convert palay to rice inventory item when milling is complete
            $riceItem = new \App\Models\InventoryItem();
            $riceItem->order_id = $mr->inventoryItem->order_id;
            $riceItem->milling_request_id = $mr->id;
            $riceItem->name = 'Milled Rice from ' . $mr->inventoryItem->name;
            $riceItem->product_type = 'rice';
            $riceItem->kilos_available = $estimatedRiceKilos;
            $riceItem->price_per_kg = $mr->inventoryItem->price_per_kg;
            $riceItem->status = 'available';
            $riceItem->notes = 'Estimated yield from ' . $mr->kilos . ' kg palay (approx. 65% => ' . $estimatedRiceKilos . ' kg rice).';
            $riceItem->save();

            // Notify admins that milling was completed and rice is available
            try {
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    \App\Models\InAppNotification::create([
                        'user_id' => $admin->id,
                        'type' => 'milling_completed',
                        'data' => [
                            'title' => 'Milling Completed',
                            'message' => 'Milling request #' . $mr->id . ' has been completed. Rice added to inventory: ' . $riceItem->kilos_available . ' kg.',
                            'milling_request_id' => $mr->id,
                            'inventory_item_id' => $riceItem->id,
                            'link' => route('admin.inventory'),
                        ],
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('Failed to create completion notifications: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Request marked as completed.');
    }
}