<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum InvoiceStatusEnum: int
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
            self::UNPOSTED => 'Invoice is complete but not yet posted to the ledger',
            self::POSTED => 'Invoice has been posted to the ledger and accounting entries are finalized',
            self::PAID => 'Invoice has been fully paid and the balance is settled',
            self::UNPAID => 'Invoice has been issued but no payment has been received',
            self::PARTIAL => 'Invoice has been partially paid and has an outstanding balance',
        };
    }
}
