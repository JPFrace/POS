<?php

namespace App\Models;

use App\Contracts\Accounting\HasCashInBank;
use App\Contracts\Business\CanPost;
use App\Contracts\Business\Transactable;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Supports\Models\HasUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;



class OfficialReceipt extends Relations\OfficialReceipt implements HasCashInBank, Transactable, CanPost
{
    use RouteModelBinding;
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    use Concerns\CanPost;

    protected $fillable = [
        'or_no',
        'ref_no',
        'date',
        'remarks',
        'file_id',
        'creator_id',
        'status_id',
        'customer_idno',
        'customer_name',
        'customer_email',
        'billing_address',
        'amount',
        'gross_amount',
        'actual_receive_amount',
        'deposited_at',
        'deposit_transit_at',
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

    protected $hidden = [
        'id',
        'file_id',
        'creator_id',
        'status_id',
        'deleted_at',
    ];

    protected $appends = [
        'url',
        'total',
        'references',
    ];


    public static function booted()
    {
        static::creating(function (OfficialReceipt $model) {
            if (empty($model->or_no)) {
                $model->or_no = (OfficialReceipt::latest('id')->first()?->id ?? 0) + 1;
            }
        });
    }

    public function getRefNo(): mixed
    {
        return $this->ref_no;
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

    public function totalCashInBank(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->details->reduce(function ($sum, $item) {
                $sum += $item->net_sub_total;

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

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => '/business/receive-money/' . $this->uuid
        );
    }

    public function totalWithholdingTax(ChartAccount $taxAccount): float
    {
        return $this->details->reduce(function ($sum, $item) use ($taxAccount) {
            if ($item->withholdingTaxAccount->is($taxAccount))
                $sum += $item->withholding_tax_rate;

            return $sum;
        }, 0);
    }

    public function totalSalesTaxTax(ChartAccount $taxAccount): float
    {
        return $this->details->reduce(function ($sum, $item) use ($taxAccount) {
            if ($item->salesTaxAccount->is($taxAccount))
                $sum += $item->purchase_tax_rate;

            return $sum;
        }, 0);
    }

    public function totalCreditableRate(ChartAccount $income): float
    {
        return $this->details->reduce(function ($sum, $item) use ($income) {
            if ($item->income->is($income))
                $sum += $item->sub_total;

            return $sum;
        }, 0);
    }

    public function references(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->denominations->map(function ($denomination) {
                $method = $denomination->payment_method;
                $methodName = $method ? strtolower($method->name) : '';
                if ($methodName === 'cash') {
                    return 'CASH';
                }
                if ($methodName === 'check' && !empty($denomination->reference_no)) {
                    return strtoupper($denomination->reference_no);
                }
                return $method ? strtoupper($method->name) : '';
            })->filter()->unique()->values()->join('; ') ?: 'CASH',
        );
    }
}
