<?php

namespace App\Models;

use App\Contracts\Business\CanPost;
use App\Contracts\Business\Transactable;
use App\Models\Concerns\DeliveredCalculate;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Supports\Models\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Relations\Bill implements Transactable, CanPost
{
    use DeliveredCalculate;
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    use Concerns\CanPost;

    protected $fillable = [
        'bill_no',
        'date',
        'due_date',
        'term_id',
        'remarks',
        'file_id',
        'status_id',
        'creator_id',
        'vendor_idno',
        'vendor_name',
        'vendor_email',
        'billing_address',
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
        'status_id',
        'creator_id',
        'deleted_at',
        'term_id',
    ];
    protected $appends = [
        'url'
    ];

    public static function booted()
    {
        static::creating(function (Bill $model) {
            if (empty($model->bill_no)) {
                $model->bill_no = (Bill::latest('id')->first()?->id ?? 0) + 1;
            }
        });
    }

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
            get: fn() => $this->vendor_name
        );
    }
    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => '/business/bills/' . $this->uuid
        );
    }

    public function totalPayable(ChartAccount $payable): float
    {
        return $this->details->reduce(function ($sum, $item) use ($payable) {
            if ($item->product->payableAccount->is($payable))
                $sum += $item->sub_total;

            return $sum;
        }, 0);
    }
}
