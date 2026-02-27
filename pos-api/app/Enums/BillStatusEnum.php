<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum BillStatusEnum: int
{
    case UNPOSTED = 1;
    case POSTED = 2;
    case PAID = 3;
    case UNPAID = 4;
    case PARTIAL = 5;

    public function label(): string
    {
        return Str::title($this->name);
    }

    public function description(): string
    {
        return match ($this) {
            self::UNPOSTED => 'Bill is complete but not yet posted to the ledger',
            self::POSTED => 'Bill has been posted to the ledger and accounting entries are finalized',
            self::PAID => 'Bill has been fully paid and the balance is settled',
            self::OPEN => 'Bill is open and has not been paid',
            self::PARTIAL => 'Bill has been partially paid and has an outstanding balance',
        };
    }
}
