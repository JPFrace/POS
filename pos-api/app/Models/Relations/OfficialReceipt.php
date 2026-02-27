<?php

namespace App\Models\Relations;

use App\Models\Auditable;
use App\Models\BankAccount;
use App\Models\ChartAccount;
use App\Models\Contact;
use App\Models\Deposit;
use App\Models\File;
use App\Models\Journal;
use App\Models\OfficialReceiptDenomination;
use App\Models\OfficialReceiptDetail;
use App\Models\PaymentType;
use App\Models\Taxonomy;
use App\Models\TransactionDimension;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

abstract class OfficialReceipt extends Auditable
{
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function customer()
    {
        return $this->belongsTo(Contact::class, 'customer_idno', 'id_no');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_method_id');
    }

    public function deposit()
    {
        return $this->belongsTo(ChartAccount::class, 'deposit_id');
    }

    public function cashInBank(): BelongsTo
    {
        return $this->deposit();
    }

    public function details(): HasMany
    {
        return $this->hasMany(OfficialReceiptDetail::class, 'or_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'status_id');
    }

    public function journals(): MorphMany
    {
        return $this->morphMany(
            Journal::class,
            'transactable'
        );
    }

    public function transactionDimensions()
    {
        return $this->morphMany(
            TransactionDimension::class,
            'transactable'
        );
    }

    public function denominations(): HasMany
    {
        return $this->hasMany(OfficialReceiptDenomination::class, 'or_id');
    }

    public function bank()
    {
        return $this->belongsTo(BankAccount::class, 'deposit_id', 'account_id');
    }

    public function deposits(): MorphMany
    {
        return $this->morphMany(Deposit::class, 'transactable');
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