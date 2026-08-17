<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\MillingRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class MillingCompletionController extends Controller
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
    | FARMER CONFIRMS MILLING COMPLETION
    |--------------------------------------------------------------------------
    |
    | Same transaction rule used for Admin <-> Miller:
    |
    | FINISHED + PAID + PROOF
    |          ↓
    | Requester confirms
    |          ↓
    | COMPLETED
    |
    */

    public function confirmCompleted(int $id): RedirectResponse
    {
        $this->requireFarmer();

        $farmerId =
            (int) Auth::id();


        try {

            DB::transaction(function () use (
                $id,
                $farmerId
            ) {

                $query =
                    MillingRequest::query()
                        ->where('id', $id);


                /*
                |--------------------------------------------------------------------------
                | SUPPORT OLD + NEW FARMER REQUEST OWNERSHIP COLUMNS
                |--------------------------------------------------------------------------
                |
                | New:
                | requester_id
                |
                | Older ANI-CARE:
                | farmer_id / user_id
                |
                */

                $ownershipColumns = [];

                foreach (
                    [
                        'requester_id',
                        'farmer_id',
                        'user_id',
                    ] as $column
                ) {
                    if (
                        Schema::hasColumn(
                            'milling_requests',
                            $column
                        )
                    ) {
                        $ownershipColumns[] =
                            $column;
                    }
                }


                if (empty($ownershipColumns)) {
                    throw ValidationException::withMessages([
                        'request' =>
                            'No requester ownership column exists in milling_requests.',
                    ]);
                }


                $query->where(
                    function (Builder $ownerQuery) use (
                        $ownershipColumns,
                        $farmerId
                    ) {

                        foreach (
                            $ownershipColumns as $index => $column
                        ) {
                            if ($index === 0) {
                                $ownerQuery->where(
                                    $column,
                                    $farmerId
                                );
                            } else {
                                $ownerQuery->orWhere(
                                    $column,
                                    $farmerId
                                );
                            }
                        }
                    }
                );


                $millingRequest =
                    $query
                        ->lockForUpdate()
                        ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | MUST BE FINISHED
                |--------------------------------------------------------------------------
                */

                $status =
                    strtolower(
                        trim(
                            (string) (
                                $millingRequest->status
                                ?? 'pending'
                            )
                        )
                    );


                if ($status === 'completed') {
                    throw ValidationException::withMessages([
                        'request' =>
                            'This milling transaction is already COMPLETED.',
                    ]);
                }


                if ($status !== 'finished') {
                    throw ValidationException::withMessages([
                        'request' =>
                            'You can confirm completion only after the Miller marks the milling job as FINISHED.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | PAYMENT MUST BE PAID
                |--------------------------------------------------------------------------
                */

                if (
                    !Schema::hasColumn(
                        'milling_requests',
                        'payment_status'
                    )
                ) {
                    throw ValidationException::withMessages([
                        'payment' =>
                            'payment_status column is missing from milling_requests.',
                    ]);
                }


                $paymentStatus =
                    strtolower(
                        trim(
                            (string) (
                                $millingRequest->payment_status
                                ?? 'unpaid'
                            )
                        )
                    );


                if ($paymentStatus !== 'paid') {
                    throw ValidationException::withMessages([
                        'payment' =>
                            'The Miller has not confirmed the payment yet. You cannot complete an UNPAID milling transaction.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | PROOF IS REQUIRED
                |--------------------------------------------------------------------------
                */

                if (
                    empty(
                        $millingRequest->proof_of_milling_path
                    )
                ) {
                    throw ValidationException::withMessages([
                        'proof' =>
                            'Proof of milling is required before you can confirm completion.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | COMPLETE
                |--------------------------------------------------------------------------
                */

                $millingRequest->status =
                    'completed';


                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'requester_confirmed_at'
                    )
                ) {
                    $millingRequest->requester_confirmed_at =
                        now();
                }


                if (
                    Schema::hasColumn(
                        'milling_requests',
                        'completed_at'
                    )
                ) {
                    $millingRequest->completed_at =
                        now();
                }


                $millingRequest->save();
            });

        } catch (ValidationException $e) {

            return back()
                ->withErrors(
                    $e->errors()
                );
        }


        return redirect()
            ->route('farmer.milling.index')
            ->with(
                'success',
                'Milling transaction confirmed as COMPLETED.'
            );
    }
}
