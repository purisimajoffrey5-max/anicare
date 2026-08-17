<?php

namespace App\Http\Controllers\Miller;

use App\Http\Controllers\Controller;
use App\Models\MillingRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EarningsController extends Controller
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
    | MILLER EARNINGS & ANALYTICS
    |--------------------------------------------------------------------------
    |
    | Milling Service Revenue
    | = total_amount for requests whose payment_status is PAID.
    |
    | Transport Collected
    | = shipping_fee from PAID milling transactions.
    |
    | Total Cash Received
    | = service revenue + transport collected.
    |
    | Pending Receivables
    | = grand total of active requests that are still UNPAID.
    |
    */

    public function index(Request $request): View
    {
        $this->requireMiller();

        $miller =
            Auth::user();


        $requests =
            MillingRequest::query()
                ->with([
                    'requester',
                    'farmer',
                ])
                ->where(
                    'miller_id',
                    $miller->id
                )
                ->latest('created_at')
                ->get();


        $paidRequests =
            $requests->filter(
                fn ($milling) =>
                    strtolower(
                        (string) (
                            $milling->payment_status
                            ?? 'unpaid'
                        )
                    ) === 'paid'
            );


        $receivableRequests =
            $requests->filter(function ($milling) {

                $status =
                    strtolower(
                        (string) (
                            $milling->status
                            ?? ''
                        )
                    );

                $payment =
                    strtolower(
                        (string) (
                            $milling->payment_status
                            ?? 'unpaid'
                        )
                    );


                return
                    !in_array(
                        $status,
                        [
                            'rejected',
                            'cancelled',
                        ],
                        true
                    )
                    &&
                    $payment !== 'paid';
            });


        $finishedRequests =
            $requests->filter(
                fn ($milling) =>
                    in_array(
                        strtolower(
                            (string) (
                                $milling->status
                                ?? ''
                            )
                        ),
                        [
                            'finished',
                            'completed',
                        ],
                        true
                    )
            );


        $completedRequests =
            $requests->filter(
                fn ($milling) =>
                    strtolower(
                        (string) (
                            $milling->status
                            ?? ''
                        )
                    ) === 'completed'
            );


        /*
        |--------------------------------------------------------------------------
        | FINANCIAL METRICS
        |--------------------------------------------------------------------------
        */

        $serviceRevenue =
            $paidRequests->sum(
                fn ($milling) =>
                    (float) (
                        $milling->total_amount
                        ?? 0
                    )
            );


        $transportCollected =
            $paidRequests->sum(
                fn ($milling) =>
                    (float) (
                        $milling->shipping_fee
                        ?? 0
                    )
            );


        $totalCashReceived =
            $serviceRevenue +
            $transportCollected;


        $pendingReceivables =
            $receivableRequests->sum(
                fn ($milling) =>
                    $this->millingGrandTotal(
                        $milling
                    )
            );


        $completedJobs =
            $completedRequests->count();


        $totalKilosProcessed =
            $finishedRequests->sum(
                fn ($milling) =>
                    $this->quantityKilos(
                        $milling
                    )
            );


        $averageRevenuePerPaidJob =
            $paidRequests->count() > 0
                ? (
                    $serviceRevenue /
                    $paidRequests->count()
                )
                : 0;


        $paidRates =
            $paidRequests
                ->map(
                    fn ($milling) =>
                        (float) (
                            $milling->milling_fee_per_kg
                            ?? 0
                        )
                )
                ->filter(
                    fn ($rate) =>
                        $rate > 0
                );


        $averageMillingRate =
            $paidRates->count() > 0
                ? $paidRates->avg()
                : 0;


        /*
        |--------------------------------------------------------------------------
        | THIS MONTH
        |--------------------------------------------------------------------------
        */

        $now =
            now('Asia/Manila');


        $thisMonthRevenue =
            $paidRequests
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
                        (float) (
                            $milling->total_amount
                            ?? 0
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
                $paidRequests
                    ->filter(
                        fn ($milling) =>
                            $this->sameMonth(
                                $this->paymentDate(
                                    $milling
                                ),
                                $month
                            )
                    )
                    ->sum(
                        fn ($milling) =>
                            (float) (
                                $milling->total_amount
                                ?? 0
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
        | REQUESTER BREAKDOWN
        |--------------------------------------------------------------------------
        */

        $requesterBreakdown = [
            'farmer' => 0,
            'admin' => 0,
        ];


        foreach ($requests as $milling) {

            $role =
                strtolower(
                    (string) (
                        $milling->requester_role
                        ?? $milling->actual_requester_role
                        ?? 'farmer'
                    )
                );


            if (
                array_key_exists(
                    $role,
                    $requesterBreakdown
                )
            ) {
                $requesterBreakdown[$role]++;
            }
        }


        $recentRequests =
            $requests
                ->take(12)
                ->values();


        return view(
            'miller.earnings.index',
            compact(
                'miller',
                'serviceRevenue',
                'transportCollected',
                'totalCashReceived',
                'pendingReceivables',
                'completedJobs',
                'totalKilosProcessed',
                'averageRevenuePerPaidJob',
                'averageMillingRate',
                'thisMonthRevenue',
                'monthlySeries',
                'chartMax',
                'requesterBreakdown',
                'recentRequests'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function quantityKilos(
        $milling
    ): float {

        return (float) (
            $milling->quantity_kilos
            ?? $milling->quantity_kg
            ?? $milling->kilos
            ?? $milling->quantity
            ?? $milling->weight_kg
            ?? 0
        );
    }


    private function millingGrandTotal(
        $milling
    ): float {

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


    private function paymentDate(
        $model
    ): ?Carbon {

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
}