<?php

namespace App\Models\Relations;

use App\Models\Auditable;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class DepositDetail extends Auditable
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Deposit::class, 'deposit_id');
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


    public function transactable()
    {
        return $this->morphTo();
    }
}