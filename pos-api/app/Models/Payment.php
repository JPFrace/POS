<?php

namespace App\Models;

use App\Contracts\Accounting\HasCashInBank;
use App\Contracts\Business\CanPost;
use App\Contracts\Business\Transactable;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Supports\Utils\Amount;
use Carbon\Carbon;

class Payment extends Relations\Payment implements HasCashInBank, Transactable, CanPost
{
    use RouteModelBinding;
    use HasFactory;
    use SoftDeletes;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    use Concerns\CanPost;

    protected $fillable = [
        'ref_no',
        'check_no',
        'date',
        'payee_idno',
        'payment_method_id',
        'remarks',
        'payee_name',
        'payee_email',
        'payee_address',
        'cash_bank_id',
        'creator_id',
        'status_id',
        'file_id',
        'amount',
        'posted_by',
        'posted_at',
        'unposted_by',
        'unposted_at'
    ];

    protected $hidden = [
        'id',
        'file_id',
        'cash_bank_id',
        'creator_id',
        'payment_method_id',
        'status_id',
        'posted_by',
        'deleted_at',
    ];

    protected $casts = [
        'date' => 'datetime:m/d/Y',
        'amount' => 'decimal:2',
        'posted_at' => 'datetime',
    ];

    protected $appends = ['net', 'net_in_words', 'url'];


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

    public function totalGrossPayment(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->details->reduce(function ($sum, $item) {
                $sum += $item->sub_total;

                return $sum;
            }, 0)
        );
    }

    public function net(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->details->reduce(function ($sum, $item) {
                $sum += $item->net_sub_total;
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

    public function netInWords(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::inWords($this->net)
        );
    }


    public function contactName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->payee_name
        );
    }

    public function transactionDimensions()
    {
        return $this->morphMany(
            TransactionDimension::class,
            'transactable'
        );
    }
    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => '/business/make-payments/' . $this->uuid
        );
    }

    public function totalWithholdingTax(ChartAccount $taxAccount): float
    {
        return $this->details->reduce(function ($sum, $item) use ($taxAccount) {
            if ($item->withholding_tax_rate > 0 && $item->withholdingTaxAccount->is($taxAccount))
                $sum += $item->withholding_tax_rate;

            return $sum;
        }, 0);
    }

    public function totalPurchaseTaxTax(ChartAccount $taxAccount): float
    {
        return $this->details->reduce(function ($sum, $item) use ($taxAccount) {
            if ($item->purchase_tax_rate > 0 && $item->purchaseTaxAccount->is($taxAccount))
                $sum += $item->purchase_tax_rate;

            return $sum;
        }, 0);
    }

    public function totalPayableRate(ChartAccount $taxAccount): float
    {
        return $this->details->reduce(function ($sum, $item) use ($taxAccount) {
            if ($item->rate < 0 && $item->product->payableAccount->is($taxAccount))
                $sum += abs($item->rate);

            return $sum;
        }, 0);
    }

    public function totalDebitableRate(ChartAccount $expense): float
    {
        return $this->details->reduce(function ($sum, $item) use ($expense) {
            if ($item->expense->is($expense))
                $sum += $item->sub_total;

            return $sum;
        }, 0);
    }

    public function cashBank()
    {
        return $this->belongsTo(ChartAccount::class, 'cash_bank_id');
    }
}
