<?php

namespace App\Models\Relations;

use App\Models\Auditable;
use App\Models\ChartAccount;
use App\Models\OfficialReceipt;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class OfficialReceiptDetail extends Auditable
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(OfficialReceipt::class, 'or_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function income(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'product_income_id');
    }

    public function withholdingTaxAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'withholding_tax_account_id');
    }

    public function salesTaxAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'sales_tax_account_id');
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