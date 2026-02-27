<?php

namespace App\Models\Relations;

use App\Models\Auditable;
use App\Models\BankAccount;
use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\File;
use App\Models\Journal;
use App\Models\PaymentDetail;
use App\Models\PaymentType;
use App\Models\Taxonomy;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class Payment extends Auditable
{
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function payee()
    {
        return $this->belongsTo(Contact::class, 'payee_idno', 'id_no');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function cashInBank(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'cash_bank_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'status_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PaymentDetail::class, 'payment_id');
    }


    public function journals(): MorphMany
    {
        return $this->morphMany(
            Journal::class,
            'transactable'
        );
    }

    public function bank()
    {
        return $this->belongsTo(BankAccount::class, 'cash_bank_id', 'account_id');
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function unpostedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unposted_by');
    }
}