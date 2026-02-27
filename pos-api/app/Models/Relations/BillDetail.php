<?php

namespace App\Models\Relations;

use App\Models\Auditable;
use App\Models\Bill;
use App\Models\ChartAccount;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class BillDetail extends Auditable
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'product_expense_id');
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