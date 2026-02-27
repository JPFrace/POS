<?php

namespace App\Contracts\Business;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Transactable
{
    public function getRefNo(): mixed;

    public function getUuid(): string;

    public function getDate(): ?Carbon;

    public function details(): HasMany;

    public function journals(): MorphMany;

    public function status(): BelongsTo;

    public function postedBy(): BelongsTo;

    public function unpostedBy(): BelongsTo;
}