<?php

namespace App\Models;

use App\Contracts\Journals\Entryable;
use App\Enums\ContactType;
use App\Enums\TransType;
use App\Supports\Dto\Journals\JournalEntry;
use App\Supports\Dto\Journals\JournalEntryDetail;
use App\Supports\Utils\Amount;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceDetail extends Relations\InvoiceDetail implements Entryable
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_income_id',
        'product_receivable_id',
        'quantity',
        'rate',
        'tax_rate',
        'paid',
        'product_name',
        'product_description',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'tax_rate' => 'decimal:4',
        'paid' => 'decimal:4',
        'posted_at' => 'datetime:m/d/Y',
        'unposted_at' => 'datetime:m/d/Y',
        'contact_type' => ContactType::class
    ];

    protected $hidden = [
        'id',
        'invoice_id',
        'product_id',
        'product_income_id',
        'product_receivable_id',
        'posted_by',
        'unposted_by',
        'deleted_at',
    ];

    protected $appends = [
        'sub_total'
    ];

    protected static function booted()
    {
        static::saved(function ($item) {
            // $invoice = $item->parent;

            // $to_pay = $invoice->details->sum(fn($item) => $item->sub_total);
            // $paid = $invoice->details->sum(fn($item) => $item->paid);

            // if ($paid < $to_pay && $paid > 0) {
            //     $invoice->status_id = InvoiceStatusEnum::PARTIAL;
            // } elseif ($paid >= $to_pay) {
            //     $invoice->status_id = InvoiceStatusEnum::PAID;
            // } else {
            //     $invoice->status_id = InvoiceStatusEnum::UNPAID;
            // }

            // $invoice->save();
        });

    }

    public function rate(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['rate']),
            set: fn($value) => Amount::acceptable($value)
        );
    }

    public function taxRate(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['tax_rate']),
            set: fn($value) => Amount::acceptable($value)
        );
    }

    public function paid(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['paid']),
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
            get: fn() => $this->product_name . "," . $this->product_description,
        );
    }



    public function subTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->quantity * $this->rate) + $this->tax_rate,
        );
    }

    /**
     * Get journal debity entry raw data
     * @return array
     */
    public function debitRaw(): array
    {
        $parent = $this->parent;

        if (!$this->receivable) {
            return [];
        }

        $data = [
            new JournalEntry(
                $this->product->receivableAccount,
                new Amount($parent->totalReceivableRate($this->receivable)),
                new JournalEntryDetail(
                    TransType::INVOICE,
                    $parent->invoice_no,
                    $parent->invoice_no,
                    $parent->creator,
                    $parent->date,
                    $parent->customer_name,
                    $parent->customer->type,
                    $parent->customer_idno,
                    $parent->remarks,
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

        if (!$this->income) {
            return [];
        }
        $data = [
            new JournalEntry(
                $this->income,
                new Amount($parent->totalIncomeRate($this->income)),
                new JournalEntryDetail(
                    TransType::INVOICE,
                    $parent->invoice_no,
                    $parent->invoice_no,
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

        return $data;
    }

}
