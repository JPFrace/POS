<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum Months: int
{
    case JANUARY = 1;
    case FEBRUARY = 2;
    case MARCH = 3;
    case APRIL = 4;
    case MAY = 5;
    case JUNE = 6;
    case JULY = 7;
    case AUGUST = 8;
    case SEPTEMBER = 9;
    case OCTOBER = 10;
    case NOVEMBER = 11;
    case DECEMBER = 12;

    // Get readable name from the enum instance
    public function label(): string
    {
        return Str::of($this->name)->title()->toString();
    }

    // Static helper: get enum by integer value
    public static function fromValue(int $value): ?self
    {
        return self::tryFrom($value);
    }

    // Static helper: get month name from integer
    public static function name(int $value): ?string
    {
        $month = self::tryFrom($value);
        return $month?->label();
    }
}
