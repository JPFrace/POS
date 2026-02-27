<?php

namespace App\Models\Relations;

use App\Models\Auditable;
use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class PaymentDetail extends Auditable
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function expense()
    {
        return $this->belongsTo(ChartAccount::class, 'product_expense_id');
    }

    public function subContact()
    {
        return $this->belongsTo(Contact::class, 'contact_idno', 'id_no');
    }

    public function withholdingTaxAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class);
    }

    public function purchaseTaxAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function unpostedBy()
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