<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum JournalEntryStatusEnum: int
{
    case DRAFT = 1;
    case FOR_REVIEW = 2;
    case RETURNED = 3;
    case APPROVED = 4;
    case POSTED = 5;
    case UNPOSTED = 6;
    case VOIDED = 7;

    public function label(): string
    {
        return Str::of($this->name)->replace('_', ' ')->title()->toString();
    }

    public function description(): string
    {
        return match ($this) {
            self::DRAFT => 'Entry is being prepared and can be edited',
            self::FOR_REVIEW => 'Entry is submitted and awaiting review',
            self::RETURNED => 'Entry was returned for corrections',
            self::APPROVED => 'Entry has been approved and ready to post',
            self::POSTED => 'Entry has been posted to the ledger',
            self::UNPOSTED => 'Entry has been unposted and reverted from the ledger',
            self::VOIDED => 'Entry has been voided and is no longer valid',
        };
    }
}
