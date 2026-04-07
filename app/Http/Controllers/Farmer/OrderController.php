<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private function requireFarmer(): void
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'farmer') abort(403, 'Unauthorized');
    }

    public function index()
    {
        $this->requireFarmer();
        $user = Auth::user();

        $orders = Order::with([
                'product:id,name,type,price_per_kg,kilos_available,photo_path,user_id',
                'resident:id,fullname,username,email'
            ])
            ->where('farmer_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('farmer.orders.index', compact('user','orders'));
    }

    private function findMyOrderOrFail(int $id): Order
    {
        $this->requireFarmer();
        $user = Auth::user();

        return Order::where('id', $id)
            ->where('farmer_id', $user->id)
            ->firstOrFail();
    }

    public function approve(int $id)
    {
        $order = $this->findMyOrderOrFail($id);

        if ($order->status !== 'pending') {
            return back()->withErrors(['order' => 'Only PENDING orders can be approved.']);
        }

        $order->status = 'approved';
        $order->save();

        return back()->with('success', "Order #{$order->id} approved.");
    }

    public function complete(int $id)
    {
        $order = $this->findMyOrderOrFail($id);

        if (!in_array($order->status, ['approved','pending'], true)) {
            return back()->withErrors(['order' => 'Only APPROVED/PENDING orders can be completed.']);
        }

        $order->status = 'completed';
        $order->save();

        return back()->with('success', "Order #{$order->id} marked as completed.");
    }

    public function cancel(Request $request, int $id)
    {
        $order = $this->findMyOrderOrFail($id);

        if ($order->status === 'completed') {
            return back()->withErrors(['order' => 'Completed order cannot be cancelled.']);
        }

        // Optional: return stock if cancelled
        if ($order->product) {
            $order->product->kilos_available = (float)$order->product->kilos_available + (float)$order->quantity_kilos;
            $order->product->is_active = 1;
            $order->product->save();
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', "Order #{$order->id} cancelled (stock returned).");
    }
}