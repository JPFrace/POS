<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum OfficialReceiptStatusEnum: int
{
    case PENDING = 1;
    case VERIFIED = 2;
    case APPROVED = 3;
    case POSTED = 4;
    case UNPOSTED = 5;

    public function label(): string
    {
        return Str::title($this->name);
    }

    public function description(): string
    {
        return match ($this) {
            self::PENDING => 'Receipt created and awaiting verification',
            self::VERIFIED => 'Receipt verified and ready for approval',
            self::APPROVED => 'Receipt approved and ready to post',
            self::POSTED => 'Receipt posted to general ledger',
            self::UNPOSTED => 'Receipt posting was reversed and removed from the general ledger',
        };
    }
}
