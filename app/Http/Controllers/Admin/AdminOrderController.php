<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\MillingRequest;
use App\Models\Order;
use App\Models\RiceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminOrderController extends Controller
{
    private function requireAdmin(): void
    {
        $u = Auth::user();

        if (!$u || $u->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }

    public function showCheckout(int $id)
    {
        $this->requireAdmin();

        $user = Auth::user();

        $product = RiceProduct::with('user:id,fullname,username')
            ->where('id', $id)
            ->where('is_active', 1)
            ->firstOrFail();

        return view('admin.checkout', compact('user', 'product'));
    }

    public function placeOrder(Request $request, int $id)
    {
        $this->requireAdmin();

        $user = Auth::user();

        $data = $request->validate([
            'buyer_name'        => ['required', 'string', 'max:150'],
            'contact_number'    => ['required', 'string', 'max:30'],
            'quantity_kilos'    => ['required', 'numeric', 'min:0.01'],
            'fulfillment_type'  => ['required', 'in:delivery,pickup'],
            'delivery_address'  => ['nullable', 'string', 'max:255'],
            'pickup_address'    => ['nullable', 'string', 'max:255'],
            'payment_method'    => ['required', 'in:gcash,bank_transfer,cash_on_delivery,cash_on_pickup'],
            'notes'             => ['nullable', 'string', 'max:500'],
        ]);

        if ($data['fulfillment_type'] === 'delivery' && empty($data['delivery_address'])) {
            return back()->withErrors([
                'delivery_address' => 'Delivery address is required for delivery orders.'
            ])->withInput();
        }

        if ($data['fulfillment_type'] === 'pickup' && empty($data['pickup_address'])) {
            return back()->withErrors([
                'pickup_address' => 'Pickup address is required for pickup orders.'
            ])->withInput();
        }

        try {
            DB::transaction(function () use ($data, $user, $id) {
                $product = RiceProduct::where('id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$product->is_active) {
                    throw ValidationException::withMessages([
                        'order' => 'This product is not available right now.',
                    ]);
                }

                $stock = (float) $product->kilos_available;
                $qty   = (float) $data['quantity_kilos'];

                if ($stock <= 0) {
                    throw ValidationException::withMessages([
                        'order' => 'Out of stock.',
                    ]);
                }

                if ($qty > $stock) {
                    throw ValidationException::withMessages([
                        'order' => 'Not enough stock available. Max: ' . number_format($stock, 2) . ' kg',
                    ]);
                }

                $unitPrice = (float) $product->price_per_kg;
                $total     = $unitPrice * $qty;

                $order = Order::create([
                    'resident_id'      => $user->id,
                    'rice_product_id'  => $product->id,
                    'farmer_id'        => $product->user_id,
                    'buyer_name'       => $data['buyer_name'],
                    'contact_number'   => $data['contact_number'],
                    'quantity_kilos'   => $qty,
                    'unit_price'       => $unitPrice,
                    'total_price'      => $total,
                    'status'           => 'pending',
                    'fulfillment_type' => $data['fulfillment_type'],
                    'delivery_address' => $data['delivery_address'] ?? null,
                    'pickup_address'   => $data['pickup_address'] ?? null,
                    'payment_method'   => $data['payment_method'],
                    'notes'            => $data['notes'] ?? null,
                ]);

                $product->kilos_available = $stock - $qty;

                if ((float) $product->kilos_available <= 0) {
                    $product->kilos_available = 0;
                    $product->is_active = 0;
                }

                $product->save();

                $itemType = strtolower($product->type) === 'palay' ? 'palay' : 'rice';
                $itemStatus = $itemType === 'palay' ? 'awaiting_milling' : 'available';

                $inventory = InventoryItem::create([
                    'order_id'        => $order->id,
                    'name'            => $product->name,
                    'product_type'    => $itemType,
                    'kilos_available' => $qty,
                    'price_per_kg'    => $unitPrice,
                    'status'          => $itemStatus,
                    'notes'           => 'Admin purchase from product #' . $product->id,
                ]);

                if ($itemType === 'palay') {
                    MillingRequest::create([
                        'user_id'          => $user->id,
                        'miller_id'        => null,
                        'kilos'            => $qty,
                        'notes'            => 'Admin palay purchase - order #' . $order->id,
                        'status'           => 'pending',
                        'inventory_item_id'=> $inventory->id,
                    ]);
                }
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        return redirect()->route('admin.market')
            ->with('success', 'Order placed successfully! Status: PENDING');
    }
}


