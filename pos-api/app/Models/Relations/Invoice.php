<?php

namespace App\Models\Relations;

use App\Enums\TransType;
use App\Models\Auditable;
use App\Models\Contact;
use App\Models\File;
use App\Models\InvoiceDetail;
use App\Models\Journal;
use App\Models\PaymentType;
use App\Models\Product;
use App\Models\Taxonomy;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class Invoice extends Auditable
{
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function type()
    {
        return $this->belongsTo(TransType::class);
    }

    public function customer()
    {
        return $this->belongsTo(Contact::class, 'customer_idno', 'id_no');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'status_id');
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function unpostedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unposted_by');
    }

    public function journals(): MorphMany
    {
        return $this->morphMany(
            Journal::class,
            'transactable'
        );
    }

}