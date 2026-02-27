<?php

namespace App\Enums;
use Illuminate\Support\Str;

enum RateType: string
{
    case percent = 'percent';
    case fixed = 'fixed';

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