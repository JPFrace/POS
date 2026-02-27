<?php

namespace App\Models;

use App\Contracts\Accounting\HasCashInBank;
use App\Contracts\Business\CanPost;
use App\Contracts\Business\Transactable;
use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Relations\Deposit implements Transactable, CanPost, HasCashInBank
{
    use HasFactory;
    use SoftDeletes;

    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    use Concerns\CanPost;

    protected $fillable = [
        'date',
        'ref_no',
        'remarks',
        'file_id',
        'cash_bank_id',
        'amount',
        'status_id',
        'creator_id',
        'posted_by',
        'posted_at',
        'unposted_by',
        'unposted_at'
    ];

    protected $casts = [
        'date' => 'datetime:m/d/Y',
        'deposited_at' => 'datetime',
        'deposit_transit_at' => 'datetime',
        'amount' => 'decimal:2',
        'posted_at' => 'datetime',
        'unposted_at' => 'datetime',
    ];

    public function getRefNo(): mixed
    {
        return $this->bill_no;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getDate(): ?Carbon
    {
        return $this->date;
    }

    /**
     * Check if posted
     * @return bool
     */
    public function isPosted(): bool
    {
        return !empty($this->posted_at);
    }

    public function totalTransactable(ChartAccount $transactable): float
    {
        return $this->details->reduce(function ($sum, $item) use ($transactable) {
            if ($item->transactable->cashInBank->is($transactable))
                $sum += $item->rate;

            return $sum;
        }, 0);
    }

    public function totalDeposit(): float
    {
        return $this->details->reduce(function ($sum, $item) {
            $sum += $item->rate;

            return $sum;
        }, 0);
    }
}
