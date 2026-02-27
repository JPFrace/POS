<?php

namespace App\Models;

use App\Contracts\Business\CanCollectSalesTax;
use App\Contracts\Business\CanWithholdTax;
use App\Contracts\Business\HasCreditableWithholdingTax;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Enums\ContactType;
use App\Enums\TransType;
use App\Supports\Dto\Journals\JournalEntry;
use App\Supports\Dto\Journals\JournalEntryDetail;
use App\Supports\Utils\Amount;
use App\Contracts\Journals\Entryable;


class OfficialReceiptDetail extends Relations\OfficialReceiptDetail implements Entryable, HasCreditableWithholdingTax, CanCollectSalesTax
{
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'trans_type',
        'ref_no',
        'or_id',
        'product_id',
        'product_income_id',
        'quantity',
        'rate',
        'withholding_tax_rate',
        'sales_tax_rate',
        'product_name',
        'product_description',
        'withholding_tax_account_id',
        'sales_tax_account_id',
        'posted_by',
        'unposted_by',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'contact_type' => ContactType::class,
        'posted_at' => 'datetime:m/d/Y',
        'unposted_at' => 'datetime:m/d/Y',
        'trans_type' => TransType::class,
    ];

    protected $hidden = [
        'id',
        'deleted_at',
        'product_id',
        'or_id',
        'deleted_at',
        'tax_account_id',
        'posted_by',
        'unposted_by',
    ];

    protected $appends = [
        'sub_total'
    ];


    public function rate(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['rate']),
            set: fn($value) => Amount::acceptable($value)
        );
    }

    public function withholdingTaxRate(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['withholding_tax_rate']),
            set: fn($value) => Amount::acceptable($value)
        );
    }

    public function salesTaxRate(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['sales_tax_rate']),
            set: fn($value) => Amount::acceptable($value)
        );
    }

    public function quantity(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['quantity']),
            set: fn($value) => Amount::acceptable($value)
        );
    }

    public function productDescribe(): Attribute
    {
        return Attribute::make(
            get: fn() => implode(', ', array_filter([
                $this->product_name,
                $this->product_description
            ]))
        );
    }

    public function subTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->quantity * $this->rate),
        );
    }

    public function netSubTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->quantity * $this->rate) - $this->tax_rate ?? 0
        );
    }

    /**
     * Get journal debit entry raw data (debits only).
     * One debit per deposit account from denominations, summed per deposit.
     *
     * @return array<JournalEntry>
     */
    public function debitRaw(): array
    {
        $parent = $this->parent;

        $denominations = $parent->denominations()->with('deposit_account')->get();

        $totalsByDeposit = $denominations->groupBy('deposit_id')->map(function ($group) {
            $first = $group->first();
            $total = $group->sum(fn ($d) => $d->quantity * $d->denomination);

            return [
                'account' => $first->deposit_account,
                'amount'  => (float) $total,
            ];
        })->filter(fn ($item) => $item['account'] !== null && $item['amount'] > 0);

        $detail = new JournalEntryDetail(
            TransType::COLLECTION,
            $parent->ref_no,
            $parent->or_no,
            $parent->creator,
            $parent->date,
            $parent->customer_name,
            $parent->customer->type,
            $parent->customer_idno,
            $parent->remarks,
            $this->contact_idno,
        );

        return $totalsByDeposit->map(
            fn ($item) => new JournalEntry($item['account'], new Amount($item['amount']), $detail)
        )->values()->all();
    }

    /**
     * Get journal credit entry raw data
     * @return array
     */
    public function creditRaw(): array
    {
        $parent = $this->parent;

        $data = [
            new JournalEntry(
                $this->income,
                new Amount($parent->totalCreditableRate($this->income)),
                new JournalEntryDetail(
                    TransType::COLLECTION,
                    $parent->ref_no,
                    $parent->or_no,
                    $parent->creator,
                    $parent->date,
                    $parent->customer_name,
                    $parent->customer->type,
                    $parent->customer_idno,
                    $this->product_describe,
                    $this->contact_idno,
                )
            ),
        ];

        if ($this->withholding_tax_rate > 0 && $this->withholdingTaxAccount) {
            $data[] = new JournalEntry(
                $this->witholdingTaxAccount,
                new Amount($parent->totalWithholdingTax($this->withholdingTaxAccount)),
                new JournalEntryDetail(
                    TransType::COLLECTION,
                    $parent->ref_no,
                    $parent->or_no,
                    $parent->creator,
                    $parent->date,
                    $parent->customer_name,
                    $parent->customer->type,
                    $parent->customer_idno,
                    strtoupper($this->tax->name) . " of " . $this->product_describe,
                    $this->contact_idno,
                )
            );
        }

        if ($this->sales_tax_rate > 0 && $this->salesTaxAccount) {
            $data[] = new JournalEntry(
                $this->salesTaxAccount,
                new Amount($parent->totalSalesTaxTax($this->salesTaxAccount)),
                new JournalEntryDetail(
                    TransType::COLLECTION,
                    $parent->ref_no,
                    $parent->or_no,
                    $parent->creator,
                    $parent->date,
                    $parent->customer_name,
                    $parent->customer->type,
                    $parent->customer_idno,
                    strtoupper($this->tax->name) . " of " . $this->product_describe,
                    $this->contact_idno,
                )
            );
        }

        return $data;
    }
}