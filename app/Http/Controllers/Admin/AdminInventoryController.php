<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InAppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AdminInventoryController extends Controller
{
    public function index(Request $request)
    {
        $items = InventoryItem::query()
            ->orderByDesc('created_at')
            ->paginate(15);

        $totalRice = InventoryItem::where('product_type', 'rice')->sum('kilos_available');
        $totalPalay = InventoryItem::where('product_type', 'palay')
            ->where('status', '!=', 'milled')
            ->sum('kilos_available');

        return view('admin.inventory', compact('items', 'totalRice', 'totalPalay'));
    }

    // Show assign form to select a miller for a palay inventory item
    public function assignForm($id)
    {
        $item = InventoryItem::with('millingRequest')->findOrFail($id);

        $millers = User::where('role', 'miller')
            ->select('id','fullname','username','is_open')
            ->orderByDesc('is_open')
            ->orderBy('fullname')
            ->get();

        return view('admin.inventory_assign', compact('item','millers'));
    }

    // Assign selected miller to the associated milling request
    public function assign(Request $request, $id)
    {
        $data = $request->validate([
            'miller_id' => ['required','integer','exists:users,id'],
        ]);

        $item = InventoryItem::with('millingRequest')->findOrFail($id);

        if (!$item->millingRequest) {
            return back()->withErrors(['item' => 'No milling request associated with this inventory item.']);
        }

        $miller = User::where('id', $data['miller_id'])->where('role','miller')->first();
        if (!$miller) {
            return back()->withErrors(['miller_id' => 'Selected miller is invalid.']);
        }

        $mr = $item->millingRequest;
        $mr->miller_id = $miller->id;
        $mr->status = 'assigned';
        $mr->save();

        $item->status = 'assigned';
        $item->save();

        // Create in-app notification for the miller
        try {
            InAppNotification::create([
                'user_id' => $miller->id,
                'type' => 'milling_assigned',
                'data' => [
                    'title' => 'New Milling Assignment',
                    'message' => 'You have been assigned a milling request #' . $mr->id . ' for ' . $item->kilos_available . ' kg of ' . $item->name,
                    'milling_request_id' => $mr->id,
                    'inventory_item_id' => $item->id,
                    'link' => route('miller.requests') . '?status=assigned'
                ],
            ]);
        } catch (\Throwable $e) {
            // swallow but log if available; do not break assignment flow
            Log::error('Failed to create in-app notification: ' . $e->getMessage());
        }

        return redirect()->route('admin.inventory')->with('success', 'Milling request assigned to ' . ($miller->fullname ?? $miller->username));
    }
}
