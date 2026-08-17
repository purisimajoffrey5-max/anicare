<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\InAppNotification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminInventoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REQUIRE ADMIN
    |--------------------------------------------------------------------------
    */
    private function requireAdmin(): void
    {
        $user = Auth::user();

        if (!$user || strtolower((string) $user->role) !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | INVENTORY PAGE
    |--------------------------------------------------------------------------
    |
    | What this does:
    |
    | 1. Finds ALL marketplace orders placed by the logged-in Admin.
    | 2. Only COMPLETED / received orders become Admin inventory.
    | 3. Automatically creates missing InventoryItem records for old and
    |    new completed purchases.
    | 4. Includes BOTH Rice and Palay.
    | 5. Rice becomes AVAILABLE.
    | 6. Palay becomes AWAITING_MILLING so it can later be assigned.
    | 7. Existing inventory records are NOT overwritten, so a Palay item
    |    that has already been milled/assigned will keep its current status.
    |
    */
    public function index(Request $request)
    {
        $this->requireAdmin();

        $admin = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | GET ADMIN'S COMPLETED MARKETPLACE PURCHASES
        |--------------------------------------------------------------------------
        |
        | In the current ANI-CARE orders table, resident_id is being used as
        | the buyer-user ID. Admin checkout also stores the Admin ID there.
        |
        */
        $completedOrders = Order::with([
                'product',
                'farmer',
            ])
            ->where('resident_id', $admin->id)
            ->where('status', 'completed')
            ->orderByDesc('updated_at')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | AUTO-SYNC COMPLETED ORDERS INTO INVENTORY
        |--------------------------------------------------------------------------
        |
        | This also repairs OLD Admin purchases that were completed before
        | the automatic inventory feature was added.
        |
        */
        DB::transaction(function () use ($completedOrders) {

            foreach ($completedOrders as $order) {

                $product = $order->product;


                /*
                 * Resolve product type.
                 *
                 * Prefer a saved snapshot when available, otherwise use
                 * the current RiceProduct record.
                 */
                $productType = strtolower(
                    trim(
                        (string) (
                            $order->product_type_snapshot
                            ?? $order->product_type
                            ?? $product?->type
                            ?? ''
                        )
                    )
                );


                /*
                 * Only marketplace Rice and Palay belong in this inventory.
                 */
                if (!in_array($productType, ['rice', 'palay'], true)) {
                    continue;
                }


                /*
                 * Resolve purchased quantity.
                 */
                $quantityKilos = (float) (
                    $order->quantity_kilos
                    ?? $order->total_kilos
                    ?? 0
                );

                if ($quantityKilos <= 0) {
                    continue;
                }


                /*
                 * IMPORTANT:
                 *
                 * Do not duplicate the original purchased inventory row.
                 *
                 * A completed milling transaction may later create another
                 * Rice inventory row using the SAME order_id. That is okay.
                 * We only check whether there is already an ORIGINAL record
                 * matching this purchase type.
                 */
                $existingPurchasedItem = InventoryItem::where(
                        'order_id',
                        $order->id
                    )
                    ->where(
                        'product_type',
                        $productType
                    )
                    ->first();

                if ($existingPurchasedItem) {
                    continue;
                }


                /*
                 * Resolve product name.
                 */
                $productName =
                    $order->product_name_snapshot
                    ?? $product?->name
                    ?? (
                        $productType === 'palay'
                            ? 'Purchased Palay'
                            : 'Purchased Rice'
                    );


                /*
                 * Resolve price per kilogram.
                 */
                $pricePerKg = (float) (
                    $order->price_per_kg
                    ?? $order->unit_price
                    ?? $product?->price_per_kg
                    ?? 0
                );


                /*
                 * CREATE INVENTORY ITEM
                 */
                $item = new InventoryItem();

                $item->order_id = $order->id;

                $item->name = $productName;

                $item->product_type = $productType;

                $item->kilos_available = $quantityKilos;

                $item->price_per_kg = $pricePerKg;


                /*
                 * Rice = ready for Admin stock.
                 *
                 * Palay = waiting to be assigned for milling.
                 */
                $item->status =
                    $productType === 'palay'
                        ? 'awaiting_milling'
                        : 'available';


                $item->notes =
                    'Marketplace purchase received by Admin. Order #' .
                    $order->id .
                    '.';

                $item->save();
            }
        });


        /*
        |--------------------------------------------------------------------------
        | ADMIN ORDER IDS
        |--------------------------------------------------------------------------
        |
        | We only show inventory connected to purchases made by THIS Admin.
        |
        | If Palay is later converted into Rice, the milling workflow keeps
        | the same order_id, so the resulting Rice remains visible here.
        |
        */
        $adminOrderIds = $completedOrders
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | BASE INVENTORY QUERY
        |--------------------------------------------------------------------------
        */
        $inventoryQuery = InventoryItem::query()
            ->with([
                'millingRequest',
            ]);


        if ($adminOrderIds->isEmpty()) {

            /*
             * No completed Admin purchases.
             */
            $inventoryQuery->whereRaw('1 = 0');

        } else {

            $inventoryQuery->whereIn(
                'order_id',
                $adminOrderIds
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL RICE
        |--------------------------------------------------------------------------
        |
        | Only inventory that still contains stock is counted.
        |
        */
        $totalRice = (clone $inventoryQuery)
            ->where('product_type', 'rice')
            ->where('kilos_available', '>', 0)
            ->sum('kilos_available');


        /*
        |--------------------------------------------------------------------------
        | TOTAL PALAY
        |--------------------------------------------------------------------------
        |
        | Do not count Palay that has already been marked MILLED.
        |
        */
        $totalPalay = (clone $inventoryQuery)
            ->where('product_type', 'palay')
            ->where('status', '!=', 'milled')
            ->where('kilos_available', '>', 0)
            ->sum('kilos_available');


        /*
        |--------------------------------------------------------------------------
        | INVENTORY ITEMS
        |--------------------------------------------------------------------------
        */
        $items = $inventoryQuery
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | ORDER MAP FOR BLADE
        |--------------------------------------------------------------------------
        |
        | Used by the updated inventory Blade to display:
        |
        | - Marketplace Order #
        | - Farmer / Seller
        | - Received date
        |
        */
        $orderMap = $completedOrders->keyBy('id');

        $purchasedCount = $completedOrders->count();


        return view(
            'admin.inventory',
            compact(
                'items',
                'totalRice',
                'totalPalay',
                'orderMap',
                'purchasedCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW ASSIGN MILLER FORM
    |--------------------------------------------------------------------------
    */
    public function assignForm(int $id)
    {
        $this->requireAdmin();

        $item = InventoryItem::with('millingRequest')
            ->findOrFail($id);


        /*
         * Only Palay should be assigned to a Miller.
         */
        if (strtolower((string) $item->product_type) !== 'palay') {

            return redirect()
                ->route('admin.inventory')
                ->withErrors([
                    'item' =>
                        'Only PALAY inventory can be assigned for milling.',
                ]);
        }


        /*
         * Palay that has already been milled should no longer be assigned.
         */
        if (strtolower((string) $item->status) === 'milled') {

            return redirect()
                ->route('admin.inventory')
                ->withErrors([
                    'item' =>
                        'This Palay inventory has already been milled.',
                ]);
        }


        $millers = User::where('role', 'miller')
            ->select(
                'id',
                'fullname',
                'username',
                'is_open'
            )
            ->orderByDesc('is_open')
            ->orderBy('fullname')
            ->get();


        return view(
            'admin.inventory_assign',
            compact(
                'item',
                'millers'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGN SELECTED MILLER
    |--------------------------------------------------------------------------
    */
    public function assign(
        Request $request,
        int $id
    ) {
        $this->requireAdmin();


        $data = $request->validate([
            'miller_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
        ]);


        $item = InventoryItem::with('millingRequest')
            ->findOrFail($id);


        /*
         * Safety: Rice must never be sent to the Palay milling assignment.
         */
        if (strtolower((string) $item->product_type) !== 'palay') {

            return back()->withErrors([
                'item' =>
                    'Only PALAY inventory can be assigned to a Miller.',
            ]);
        }


        /*
         * Existing workflow expects a MillingRequest connected
         * to this InventoryItem.
         */
        if (!$item->millingRequest) {

            return back()->withErrors([
                'item' =>
                    'No milling request is associated with this Palay inventory yet. Create the Admin milling request first, then assign the Miller.',
            ]);
        }


        /*
         * Validate selected user as Miller.
         */
        $miller = User::where(
                'id',
                $data['miller_id']
            )
            ->where(
                'role',
                'miller'
            )
            ->first();


        if (!$miller) {

            return back()->withErrors([
                'miller_id' =>
                    'Selected Miller is invalid.',
            ]);
        }


        /*
         * Optional but recommended:
         * only an OPEN Miller can receive a new assignment.
         */
        if (!(bool) $miller->is_open) {

            return back()->withErrors([
                'miller_id' =>
                    'Selected Miller is currently CLOSED. Please select an OPEN Miller.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SAVE ASSIGNMENT
        |--------------------------------------------------------------------------
        */
        DB::transaction(function () use (
            $item,
            $miller
        ) {

            $mr = $item->millingRequest;

            $mr->miller_id = $miller->id;

            $mr->status = 'assigned';

            $mr->save();


            $item->status = 'assigned';

            $item->save();
        });


        /*
        |--------------------------------------------------------------------------
        | MILLER NOTIFICATION
        |--------------------------------------------------------------------------
        */
        try {

            $mr = $item->fresh()
                ->millingRequest;

            if ($mr) {

                InAppNotification::create([

                    'user_id' =>
                        $miller->id,

                    'type' =>
                        'milling_assigned',

                    'data' => [

                        'title' =>
                            'New Milling Assignment',

                        'message' =>
                            'You have been assigned milling request #' .
                            $mr->id .
                            ' for ' .
                            number_format(
                                (float) $item->kilos_available,
                                2
                            ) .
                            ' kg of ' .
                            $item->name .
                            '.',

                        'milling_request_id' =>
                            $mr->id,

                        'inventory_item_id' =>
                            $item->id,

                        'link' =>
                            route('miller.requests') .
                            '?status=assigned',
                    ],
                ]);
            }

        } catch (\Throwable $e) {

            /*
             * Do not break the successful assignment if notification fails.
             */
            Log::error(
                'Failed to create in-app notification: ' .
                $e->getMessage()
            );
        }


        return redirect()
            ->route('admin.inventory')
            ->with(
                'success',
                'Milling request assigned to ' .
                (
                    $miller->fullname
                    ?? $miller->username
                ) .
                '.'
            );
    }
}