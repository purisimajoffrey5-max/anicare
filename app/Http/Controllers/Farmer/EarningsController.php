<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\MillingRequest;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class EarningsController extends Controller
{
    private function requireFarmer(): void
    {
        $user = Auth::user();

        if (
            !$user ||
            strtolower((string) $user->role) !== 'farmer'
        ) {
            abort(403, 'Unauthorized');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FARMER EARNINGS & ANALYTICS
    |--------------------------------------------------------------------------
    |
    | IMPORTANT ACCOUNTING DEFINITIONS USED BY THIS PAGE
    |
    | Marketplace Sales Revenue
    | = PRODUCT subtotal of orders whose payment was actually marked PAID.
    |
    | Pending Receivables
    | = PRODUCT subtotal of non-cancelled orders that are still UNPAID.
    |
    | Milling Expenses
    | = Farmer milling requests whose payment_status is PAID.
    |
    | Net Transaction Balance
    | = Marketplace Sales Revenue - Paid Milling Expenses.
    |
    | This is intentionally NOT called "Net Profit" because production costs,
    | labor, fertilizer, fuel, taxes, etc. are not tracked by ANI-CARE yet.
    |
    */

    public function index(Request $request): View
    {
        $this->requireFarmer();

        $farmer = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | MARKETPLACE ORDERS
        |--------------------------------------------------------------------------
        */

        $orders = Order::query()
            ->with([
                'product',
                'resident',
            ])
            ->where(
                'farmer_id',
                $farmer->id
            )
            ->latest('created_at')
            ->get();


        $paidOrders =
            $orders->filter(
                fn ($order) =>
                    strtolower(
                        (string) (
                            $order->payment_status
                            ?? 'unpaid'
                        )
                    ) === 'paid'
            );


        $completedPaidOrders =
            $paidOrders->filter(
                fn ($order) =>
                    strtolower(
                        (string) (
                            $order->status
                            ?? ''
                        )
                    ) === 'completed'
            );


        $receivableOrders =
            $orders->filter(function ($order) {

                $status =
                    strtolower(
                        (string) (
                            $order->status
                            ?? ''
                        )
                    );

                $payment =
                    strtolower(
                        (string) (
                            $order->payment_status
                            ?? 'unpaid'
                        )
                    );

                return
                    !in_array(
                        $status,
                        [
                            'cancelled',
                            'rejected',
                        ],
                        true
                    )
                    &&
                    $payment !== 'paid';
            });


        /*
        |--------------------------------------------------------------------------
        | SALES METRICS
        |--------------------------------------------------------------------------
        */

        $salesRevenue =
            $paidOrders->sum(
                fn ($order) =>
                    $this->orderProductAmount(
                        $order
                    )
            );


        $completedSalesRevenue =
            $completedPaidOrders->sum(
                fn ($order) =>
                    $this->orderProductAmount(
                        $order
                    )
            );


        $pendingReceivables =
            $receivableOrders->sum(
                fn ($order) =>
                    $this->orderProductAmount(
                        $order
                    )
            );


        $shippingCollected =
            $paidOrders->sum(
                fn ($order) =>
                    (float) (
                        $order->shipping_fee
                        ?? 0
                    )
            );


        $completedSalesCount =
            $completedPaidOrders->count();


        $totalKilosSold =
            $completedPaidOrders->sum(
                fn ($order) =>
                    (float) (
                        $order->quantity_kilos
                        ?? $order->total_kilos
                        ?? 0
                    )
            );


        /*
        |--------------------------------------------------------------------------
        | FARMER MILLING EXPENSES
        |--------------------------------------------------------------------------
        */

        $millingRequests =
            $this->farmerMillingQuery(
                (int) $farmer->id
            )
                ->latest('created_at')
                ->get();


        $paidMillingRequests =
            $millingRequests->filter(
                fn ($milling) =>
                    strtolower(
                        (string) (
                            $milling->payment_status
                            ?? 'unpaid'
                        )
                    ) === 'paid'
            );


        $millingExpenses =
            $paidMillingRequests->sum(
                fn ($milling) =>
                    $this->millingGrandTotal(
                        $milling
                    )
            );


        $netTransactionBalance =
            $salesRevenue -
            $millingExpenses;


        /*
        |--------------------------------------------------------------------------
        | THIS MONTH
        |--------------------------------------------------------------------------
        */

        $now =
            now('Asia/Manila');


        $thisMonthSales =
            $paidOrders
                ->filter(
                    fn ($order) =>
                        $this->sameMonth(
                            $this->paymentDate(
                                $order
                            ),
                            $now
                        )
                )
                ->sum(
                    fn ($order) =>
                        $this->orderProductAmount(
                            $order
                        )
                );


        $thisMonthMillingExpenses =
            $paidMillingRequests
                ->filter(
                    fn ($milling) =>
                        $this->sameMonth(
                            $this->paymentDate(
                                $milling
                            ),
                            $now
                        )
                )
                ->sum(
                    fn ($milling) =>
                        $this->millingGrandTotal(
                            $milling
                        )
                );


        /*
        |--------------------------------------------------------------------------
        | LAST 6 MONTHS CHART
        |--------------------------------------------------------------------------
        */

        $monthlySeries = [];

        for ($i = 5; $i >= 0; $i--) {

            $month =
                $now
                    ->copy()
                    ->subMonths($i)
                    ->startOfMonth();


            $amount =
                $paidOrders
                    ->filter(
                        fn ($order) =>
                            $this->sameMonth(
                                $this->paymentDate(
                                    $order
                                ),
                                $month
                            )
                    )
                    ->sum(
                        fn ($order) =>
                            $this->orderProductAmount(
                                $order
                            )
                    );


            $monthlySeries[] = [
                'label' =>
                    $month->format('M'),

                'month' =>
                    $month->format('Y-m'),

                'amount' =>
                    round(
                        (float) $amount,
                        2
                    ),
            ];
        }


        $chartMax =
            max(
                1,
                ...array_column(
                    $monthlySeries,
                    'amount'
                )
            );


        /*
        |--------------------------------------------------------------------------
        | TOP PRODUCTS
        |--------------------------------------------------------------------------
        */

        $topProducts =
            $completedPaidOrders
                ->groupBy(
                    fn ($order) =>
                        (string) (
                            $order->product_name_snapshot
                            ?? $order->product?->name
                            ?? 'Product'
                        )
                )
                ->map(function (Collection $productOrders, string $name) {

                    return [
                        'name' =>
                            $name,

                        'orders' =>
                            $productOrders->count(),

                        'kilos' =>
                            (float) $productOrders->sum(
                                fn ($order) =>
                                    (float) (
                                        $order->quantity_kilos
                                        ?? $order->total_kilos
                                        ?? 0
                                    )
                            ),

                        'revenue' =>
                            (float) $productOrders->sum(
                                fn ($order) =>
                                    $this->orderProductAmount(
                                        $order
                                    )
                            ),
                    ];
                })
                ->sortByDesc(
                    'revenue'
                )
                ->take(5)
                ->values();


        /*
        |--------------------------------------------------------------------------
        | RECENT TRANSACTIONS
        |--------------------------------------------------------------------------
        */

        $recentOrders =
            $orders
                ->take(12)
                ->values();


        return view(
            'farmer.earnings.index',
            compact(
                'farmer',
                'salesRevenue',
                'completedSalesRevenue',
                'pendingReceivables',
                'shippingCollected',
                'completedSalesCount',
                'totalKilosSold',
                'millingExpenses',
                'netTransactionBalance',
                'thisMonthSales',
                'thisMonthMillingExpenses',
                'monthlySeries',
                'chartMax',
                'topProducts',
                'recentOrders'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function orderProductAmount($order): float
    {
        return (float) (
            $order->total_price
            ?? $order->subtotal
            ?? (
                (float) (
                    $order->quantity_kilos
                    ?? 0
                )
                *
                (float) (
                    $order->unit_price
                    ?? $order->price_per_kg
                    ?? 0
                )
            )
        );
    }


    private function millingGrandTotal($milling): float
    {
        return (float) (
            $milling->grand_total
            ?? (
                (float) (
                    $milling->total_amount
                    ?? 0
                )
                +
                (float) (
                    $milling->shipping_fee
                    ?? 0
                )
            )
        );
    }


    private function paymentDate($model): ?Carbon
    {
        $value =
            $model->paid_at
            ?? $model->updated_at
            ?? $model->created_at
            ?? null;


        if (!$value) {
            return null;
        }


        return Carbon::parse(
            $value
        )->timezone(
            'Asia/Manila'
        );
    }


    private function sameMonth(
        ?Carbon $date,
        Carbon $month
    ): bool {

        if (!$date) {
            return false;
        }


        return
            $date->year ===
                $month->year
            &&
            $date->month ===
                $month->month;
    }


    private function farmerMillingQuery(
        int $farmerId
    ): Builder {

        $query =
            MillingRequest::query();


        $hasRequesterId =
            Schema::hasColumn(
                'milling_requests',
                'requester_id'
            );

        $hasFarmerId =
            Schema::hasColumn(
                'milling_requests',
                'farmer_id'
            );

        $hasUserId =
            Schema::hasColumn(
                'milling_requests',
                'user_id'
            );


        $query->where(
            function (Builder $ownerQuery) use (
                $farmerId,
                $hasRequesterId,
                $hasFarmerId,
                $hasUserId
            ) {

                $first = true;


                foreach (
                    [
                        'requester_id' =>
                            $hasRequesterId,

                        'farmer_id' =>
                            $hasFarmerId,

                        'user_id' =>
                            $hasUserId,
                    ] as $column => $exists
                ) {

                    if (!$exists) {
                        continue;
                    }


                    if ($first) {

                        $ownerQuery->where(
                            $column,
                            $farmerId
                        );

                        $first =
                            false;

                    } else {

                        $ownerQuery->orWhere(
                            $column,
                            $farmerId
                        );
                    }
                }


                /*
                 * If the database has no compatible ownership field, this
                 * prevents accidentally exposing every milling request.
                 */
                if ($first) {
                    $ownerQuery->whereRaw(
                        '1 = 0'
                    );
                }
            }
        );


        return $query;
    }
}