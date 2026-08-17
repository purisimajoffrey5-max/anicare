<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MillingRequest extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    |
    | We keep this model backward-compatible with the existing ANI-CARE
    | milling_requests table while adding the new transaction workflow.
    |
    | Existing old fields + new fields can continue to be saved without
    | constantly updating a long $fillable list.
    |
    */

    protected $guarded = [];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'preferred_date'          => 'date',
        'scheduled_at'            => 'datetime',
        'started_at'              => 'datetime',
        'finished_at'             => 'datetime',
        'paid_at'                 => 'datetime',
        'requester_confirmed_at'  => 'datetime',
        'completed_at'            => 'datetime',

        'milling_fee_per_kg'      => 'decimal:2',
        'total_amount'            => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | REQUESTER
    |--------------------------------------------------------------------------
    |
    | New workflow:
    | requester_id   = Farmer OR Admin user id
    | requester_role = farmer OR admin
    |
    */

    public function requester(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'requester_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LEGACY / FARMER RELATION
    |--------------------------------------------------------------------------
    |
    | Keep this because the existing Farmer/Miller module may still use:
    |
    | $request->farmer
    | $request->farmer_id
    |
    */

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'farmer_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MILLER
    |--------------------------------------------------------------------------
    */

    public function miller(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'miller_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUAL REQUESTER
    |--------------------------------------------------------------------------
    |
    | New records:
    | requester relation
    |
    | Old Farmer records:
    | fallback to farmer relation
    |
    | Usage:
    |
    | $millingRequest->actual_requester
    |
    */

    public function getActualRequesterAttribute(): ?User
    {
        if ($this->requester_id) {
            return $this->requester;
        }

        return $this->farmer;
    }


    /*
    |--------------------------------------------------------------------------
    | REQUESTER ROLE
    |--------------------------------------------------------------------------
    |
    | New records use requester_role.
    | Existing Farmer requests automatically fall back to "farmer".
    |
    */

    public function getActualRequesterRoleAttribute(): string
    {
        $role = strtolower(
            (string) ($this->requester_role ?? '')
        );

        if (in_array($role, ['farmer', 'admin'], true)) {
            return $role;
        }

        if (!empty($this->farmer_id)) {
            return 'farmer';
        }

        return 'unknown';
    }


    /*
    |--------------------------------------------------------------------------
    | REQUESTER NAME
    |--------------------------------------------------------------------------
    */

    public function getRequesterNameAttribute(): string
    {
        $user = $this->actual_requester;

        if (!$user) {
            return 'Unknown Requester';
        }

        return (string) (
            $user->fullname
            ?? $user->username
            ?? 'Unknown Requester'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return strtolower(
            (string) ($this->status ?? '')
        ) === 'pending';
    }

    public function isAccepted(): bool
    {
        return strtolower(
            (string) ($this->status ?? '')
        ) === 'accepted';
    }

    public function isScheduled(): bool
    {
        return strtolower(
            (string) ($this->status ?? '')
        ) === 'scheduled';
    }

    public function isInProgress(): bool
    {
        return strtolower(
            (string) ($this->status ?? '')
        ) === 'in_progress';
    }

    public function isFinished(): bool
    {
        return strtolower(
            (string) ($this->status ?? '')
        ) === 'finished';
    }

    public function isCompleted(): bool
    {
        return strtolower(
            (string) ($this->status ?? '')
        ) === 'completed';
    }

    public function isRejected(): bool
    {
        return strtolower(
            (string) ($this->status ?? '')
        ) === 'rejected';
    }

    public function isCancelled(): bool
    {
        return strtolower(
            (string) ($this->status ?? '')
        ) === 'cancelled';
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPaid(): bool
    {
        return strtolower(
            (string) ($this->payment_status ?? 'unpaid')
        ) === 'paid';
    }

    public function isUnpaid(): bool
    {
        return !$this->isPaid();
    }


    /*
    |--------------------------------------------------------------------------
    | REQUESTER QUERY SCOPE
    |--------------------------------------------------------------------------
    |
    | Works for:
    |
    | Admin:
    | requester_id = admin user id
    |
    | Farmer new records:
    | requester_id = farmer user id
    |
    | Farmer legacy records:
    | farmer_id = farmer user id
    |
    | Usage:
    |
    | MillingRequest::forRequester(Auth::user())->latest()->get();
    |
    */

    public function scopeForRequester(
        Builder $query,
        User $user
    ): Builder {
        $role = strtolower(
            (string) ($user->role ?? '')
        );

        return $query->where(function (Builder $q) use ($user, $role) {

            $q->where(
                'requester_id',
                $user->id
            );

            if ($role === 'farmer') {
                $q->orWhere(
                    'farmer_id',
                    $user->id
                );
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | MILLER QUERY SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeForMiller(
        Builder $query,
        User $user
    ): Builder {
        return $query->where(
            'miller_id',
            $user->id
        );
    }
}