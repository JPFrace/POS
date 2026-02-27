<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum
    PaymentStatusEnum: int
{
    case PENDING = 1;
    case PAID = 2;
    case UNPOSTED = 3;

    public function label(): string
    {
        return Str::title($this->name);
    }

    public function description(): string
    {
        return match ($this) {
            self::PENDING => "Payment has been created but not yet posted or completed",
            self::PAID => "Payment has been successfully posted and applied",
            self::UNPOSTED => "Payment was previously posted but has been reverted to an unposted state",
        };
    }
}
