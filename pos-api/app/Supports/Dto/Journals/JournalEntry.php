<?php

namespace App\Supports\Dto\Journals;

use App\Models\ChartAccount;
use App\Supports\Utils\Amount;

class JournalEntry
{
    public function __construct(
        protected ChartAccount $account,
        protected Amount $amount,
        protected JournalEntryDetail $detail
    ) {

    }

    public function account(): ChartAccount
    {
        return $this->account;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function detail(): JournalEntryDetail
    {
        return $this->detail;
    }
}