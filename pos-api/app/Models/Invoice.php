<?php

namespace App\Models;

use App\Contracts\Business\CanPost;
use App\Contracts\Business\Transactable;
use App\Enums\InvoiceStatusEnum as EnumStatus;
use App\Enums\TransType;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Supports\Models\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Relations\Invoice implements Transactable, CanPost
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
        'invoice_no',
        'date',
        'due_date',
        'remarks',
        'file_id',
        'creator_id',
        'status_id',
        'customer_idno',
        'customer_name',
        'customer_email',
        'billing_address',
        'payment_method_id',
        'amount',
        'posted_by',
        'posted_at',
        'unposted_by',
        'unposted_at'
    ];

    protected $casts = [
        'date' => 'datetime:m/d/Y',
        'due_date' => 'datetime:m/d/Y',
        'amount' => 'decimal:2',
        'posted_at' => 'datetime',
        'unposted_at' => 'datetime',
    ];

    protected $hidden = [
        'id',
        'file_id',
        'payment_method_id',
        'creator_id',
        'status_id',
        'deleted_at',
    ];

    public static function booted()
    {
        static::creating(function (Invoice $model) {
            if (empty($model->invoice_no)) {
                $model->invoice_no = (Invoice::latest('id')->first()?->id ?? 0) + 1;
            }
        });
    }

    public function getRefNo(): mixed
    {
        return $this->invoice_no;
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

    public function total(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->details->reduce(function ($sum, $item) {
                $sum += $item->sub_total;

                return $sum;
            }, 0)
        );
    }

    public function contactName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->customer_name
        );
    }

    public function totalReceivableRate(ChartAccount $receivable): float
    {
        return $this->details->reduce(function ($sum, $item) use ($receivable) {
            if ($item->receivable->is($receivable))
                $sum += $item->sub_total;

            return $sum;
        }, 0);
    }

    public function totalIncomeRate(ChartAccount $income): float
    {
        return $this->details->reduce(function ($sum, $item) use ($income) {
            if ($item->income->is($income))
                $sum += $item->sub_total;

            return $sum;
        }, 0);
    }
}
