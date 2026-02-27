<?php

namespace App\Contracts\Transactions;

use App\Enums\PostingStatus;

interface StatusUpdatable
{
    public function update(PostingStatus $status, string $uuid);
}