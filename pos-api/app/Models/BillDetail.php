<?php

namespace App\Models;

use App\Contracts\Business\HasPayable;
use App\Contracts\Journals\Entryable;
use App\Enums\TransType;
use App\Supports\Dto\Journals\JournalEntry;
use App\Supports\Dto\Journals\JournalEntryDetail;
use App\Supports\Utils\Amount;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillDetail extends Relations\BillDetail implements Entryable, HasPayable
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'bill_id',
        'order_id',
        'product_id',
        'product_expense_id',
        'quantity',
        'rate',
        'product_name',
        'product_description',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
        'posted_at' => 'datetime',
        'unposted_at' => 'datetime',
    ];

    protected $hidden = [
        'product_id',
        'bill_id',
        'product_expense_id',
        'order_id',
        'posted_by',
        'unposted_by',
    ];

    protected $appends = [
        'sub_total'
    ];

    protected static function booted()
    {
        // Refresh order billed items
        static::saved(function ($item) {
            $order = $item->order;

            if (!empty($order)) {
                $item->order->refreshStatus();
                $item->parent->refreshDelivered();
            }
        });
    }

    public function rate(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['rate']),
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
            get: fn() => ($this->quantity * $this->rate),
        );
    }

    /**
     * Get journal debity entry raw data
     * @return array
     */
    public function debitRaw(): array
    {
        $parent = $this->parent;

        $data = [
            new JournalEntry(
                $this->expense,
                new Amount($this->sub_total),
                new JournalEntryDetail(
                    TransType::AP,
                    $parent->bill_no,
                    $parent->bill_no,
                    $parent->creator,
                    $parent->date,
                    $parent->vendor_name,
                    $parent->vendor->type,
                    $parent->vendor_idno,
                    $this->product_describe,
                    null,
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
                $this->product->payableAccount,
                new Amount($parent->totalPayable($this->product->payableAccount)),
                new JournalEntryDetail(
                    TransType::AP,
                    $parent->bill_no,
                    $parent->bill_no,
                    $parent->creator,
                    $parent->date,
                    $parent->vendor_name,
                    $parent->vendor->type,
                    $parent->vendor_idno,
                    $parent->remarks,
                    null,
                )
            ),
        ];

        return $data;
    }
}
