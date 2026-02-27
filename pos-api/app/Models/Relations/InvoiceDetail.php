<?php

namespace App\Models\Relations;

use App\Models\Auditable;
use App\Models\ChartAccount;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class InvoiceDetail extends Auditable
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function income()
    {
        return $this->belongsTo(ChartAccount::class, 'product_income_id');
    }

    public function receivable()
    {
        return $this->belongsTo(ChartAccount::class, 'product_receivable_id');
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function unpostedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unposted_by');
    }

    public function debit()
    {
        return $this->parent->journals()->where('debit', '>', 0);
    }

    public function credit()
    {
        return $this->parent->journals()->where('credit', '>', 0);
    }
}