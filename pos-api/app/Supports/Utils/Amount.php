<?php

namespace App\Supports\Utils;

use NumberFormatter;

readonly class Amount
{
    public function __construct(private float $amount, private string $currency = 'PHP')
    {
        // 
    }

    public function float(): float
    {
        return $this->amount;
    }

    public static function formatValue(float $amount, ?string $currency = null)
    {
        return (new Amount($amount, $currency ?: config('system.currency')))->value();
    }

    public static function acceptable(mixed $value): float
    {
        if (empty($value)) {
            return 0;
        }

        return (float) str_replace(',', '', $value);
    }

    public function value()
    {
        return $this->format($this->amount);
    }

    protected function format(float $amount)
    {
        return $this->currency . ' ' . number_format($amount, 2);
    }

    public function toWords(): string
    {
        $formatter = new NumberFormatter("en", NumberFormatter::SPELLOUT);

        $integerPart = floor($this->amount);
        $decimalPart = round(($this->amount - $integerPart) * 100);

        $words = ucfirst($formatter->format($integerPart));

        $words .= " and " . str_pad($decimalPart, 2, "0", STR_PAD_LEFT) . "/100";

        if ($this->currency) {
            $words .= " " . ucfirst($this->currency);
        }

        return "**{$words}**";
    }

    public static function inWords(float $amount, ?string $currency = null): string
    {
        return (new Amount($amount, $currency ?: config('system.currency')))->toWords();
    }
}