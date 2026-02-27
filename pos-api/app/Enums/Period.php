<?php

namespace App\Enums;
use Illuminate\Support\Str;

enum Period: string
{
    case MONTHLY = "Monthly";
    case QUARTERLY = "Quarterly";
    case ANNUALY = "Annualy";


    // Get readable name from the enum instance
    public function label(): string
    {
        return Str::of($this->name)->title()->toString();
    }

    // Static helper: get enum by integer value
    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }

    // Static helper: get month name from integer
    public static function name(string $value): ?string
    {
        $month = self::tryFrom($value);
        return $month?->label();
    }
}
