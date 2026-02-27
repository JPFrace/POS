<?php

namespace App\Models;

use App\Contracts\Business\CanWithholdTax;
use App\Contracts\Business\HasCreditableTax;
use App\Contracts\Business\HasNegativeAsPayable;
use App\Contracts\Journals\Entryable;
use App\Enums\TransType;
use App\Models\Concerns\Payments\RevertableJournal;
use App\Models\Concerns\Payments\CreateableJournal;
use App\Supports\Dto\Journals\JournalEntry;
use App\Supports\Dto\Journals\JournalEntryDetail;
use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use App\Supports\Utils\Amount;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentDetail extends Relations\PaymentDetail implements
    Entryable,
    CanWithholdTax,
    HasCreditableTax,
    HasNegativeAsPayable
{
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'payment_id',
        'product_id',
        'product_name',
        'product_description',
        'contact_idno',
        'quantity',
        'rate',
        'product_expense_id',
        'withholding_tax_rate',
        'purchase_tax_rate',
        'withholding_tax_account_id',
        'purchase_tax_account_id',
    ];

    protected $hidden = [
        'id',
        'product_id',
        'payment_id',
        'product_expense_id',
        'deleted_at',
        'withholding_tax_account_id',
        'purchase_tax_account_id',
        'posted_by',
        'unposted_by',
    ];

    protected $casts = [
        'date' => 'datetime:m/d/Y',
        'rate' => 'decimal:4',
        'tax_rate' => 'decimal:4',
        'posted_at' => 'datetime:m/d/Y',
        'unposted_at' => 'datetime:m/d/Y',
    ];

    protected $appends = [
        'sub_total',
        'net_sub_total'
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

    public function purchaseTaxRate(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['purchase_tax_rate']),
            set: fn($value) => Amount::acceptable($value)
        );
    }

    public function subTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->quantity * $this->rate)
        );
    }

    public function netSubTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->quantity * $this->rate) - $this->withholding_tax_rate ?? 0
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
            get: fn() => $this->product_name . "," . $this->product_description,
        );
    }

    /**
     * Get journal debity entry raw data
     * @return array
     */
    public function debitRaw(): array
    {
        $parent = $this->parent;

        if ($this->rate < 0) {
            return [];
        }

        $data = [
            new JournalEntry(
                $this->expense,
                new Amount($parent->totalDebitableRate($this->expense)),
                new JournalEntryDetail(
                    TransType::DISBURSEMENT,
                    $parent->ref_no,
                    $parent->check_no,
                    $parent->creator,
                    $parent->date,
                    $parent->payee_name,
                    $parent->payee->type,
                    $parent->payee_idno,
                    $this->product_describe,
                    $this->contact_idno,
                )
            )
        ];

        return $data;
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
                $parent->cashInBank,
                new Amount($parent->total_cash_in_bank),
                new JournalEntryDetail(
                    TransType::DISBURSEMENT,
                    $parent->ref_no,
                    $parent->check_no,
                    $parent->creator,
                    $parent->date,
                    $parent->payee_name,
                    $parent->payee->type,
                    $parent->payee_idno,
                    $parent->remarks,
                    $this->contact_idno,
                )
            ),
        ];

        if ($this->rate < 0 && $this->product->payableAccount) {
            $data[] = new JournalEntry(
                $this->product->payableAccount,
                new Amount(abs($parent->totalPayableRate($this->product->payableAccount))),
                new JournalEntryDetail(
                    TransType::DISBURSEMENT,
                    $parent->ref_no,
                    $parent->check_no,
                    $parent->creator,
                    $parent->date,
                    $parent->payee_name,
                    $parent->payee->type,
                    $parent->payee_idno,
                    "Account payable",
                    $this->contact_idno,
                )
            );
        }

        if ($this->withholding_tax_rate > 0 && $this->withholdingTaxAccount) {
            $data[] = new JournalEntry(
                $this->withholdingTaxAccount,
                new Amount($parent->totalWithholdingTax($this->withholdingTaxAccount)),
                new JournalEntryDetail(
                    TransType::COLLECTION,
                    $parent->ref_no,
                    $parent->check_no,
                    $parent->creator,
                    $parent->date,
                    $parent->payee_name,
                    $parent->payee->type,
                    $parent->payee_idno,
                    strtoupper($this->withholdingTaxAccount->name),
                    $this->contact_idno,
                )
            );
        }

        if ($this->sales_tax_rate > 0 && $this->salesTaxAccount) {
            $data[] = new JournalEntry(
                $this->salesTaxAccount,
                new Amount($parent->totalPurchaseTaxTax($this->salesTaxAccount)),
                new JournalEntryDetail(
                    TransType::COLLECTION,
                    $parent->ref_no,
                    $parent->check_no,
                    $parent->creator,
                    $parent->date,
                    $parent->payee_name,
                    $parent->payee->type,
                    $parent->payee_idno,
                    strtoupper($this->salesTaxAccount->name),
                    $this->contact_idno,
                )
            );
        }

        return $data;
    }

}
