<?php

namespace App\Contracts\Accounting;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface HasCashInBank
{
    public function cashInBank(): BelongsTo;
}