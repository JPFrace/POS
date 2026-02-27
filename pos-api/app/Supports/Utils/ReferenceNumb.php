<?php

namespace App\Supports\Utils;

use App\Facades\SystemConfig;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;
use Opcodes\LogViewer\Logs\Log;

use function Symfony\Component\Clock\now;

class ReferenceNumb
{
    /**
     * Generate a new reference number for a given module.
     *
     * @param string $moduleKey The configuration slug for the module.
     * @param string $modelClass The Eloquent model class to check existing numbers.
     * @param string $column The column name in the model to store the reference number.
     * @param DateTime $date The date to use for placeholders.
     * @param array $args Additional arguments for placeholder replacement.
     * @return string The generated reference number.
     * @throws Exception If the configuration is invalid.
     */
    public function generate(string $moduleKey, string $modelClass, string $column, DateTime $date, array $args = []): string
    {
        return $this->buildRefNum($moduleKey, $modelClass, $column, $date, $args);
    }

    private function buildRefNum(string $moduleKey, string $modelClass, string $column, DateTime $date, array $args = []): string
    {
        $config = SystemConfig::get($moduleKey);
        $reset = SystemConfig::value($moduleKey . '_reset', 'n');

        if (!$config) {
            \Log::info("Reference Number: Configuration for '$moduleKey' not found. Using default configuration.");

            $config = (object)[
                'type' => 'string',
                'use_prefix' => false,
                'use_suffix' => false,
                'prefix' => '',
                'suffix' => '',
                'value' => '0000',
            ];
        }

        $prefix = $config->use_prefix ? $this->replacePlaceholders($config->prefix ?? '', $date, $args) : '';
        $suffix = $config->use_suffix ? $this->replacePlaceholders($config->suffix ?? '', $date, $args) : '';

        $valueFormat = !empty($config->value) ? $config->value : '0000';
        $pad = strlen($valueFormat);

        $model = new $modelClass;
        $table = $model->getTable();

        $prefixLike = $prefix;
        $suffixLike = $suffix;

        $date = Carbon::parse($date);

        if ($reset === 'm') {
            $prefixLike = str_replace(
                ['{YYYY}', '{YY}', '{MM}', '{M}'],
                [$date->format('Y'), $date->format('y'), $date->format('m'), $date->format('n')],
                $prefix
            );

            $suffixLike = str_replace(
                ['{YYYY}', '{YY}', '{MM}', '{M}'],
                [$date->format('Y'), $date->format('y'), $date->format('m'), $date->format('n')],
                $suffix
            );
        }

        if ($reset === 'y') {
            $prefixLike = str_replace(
                ['{YYYY}', '{YY}'],
                [$date->format('Y'), $date->format('y')],
                $prefix
            );

            $suffixLike = str_replace(
                ['{YYYY}', '{YY}'],
                [$date->format('Y'), $date->format('y')],
                $suffix
            );
        }

        $prefixLength = strlen($prefixLike);
        $sqlStartPos = $prefixLength + 1;

        $maxNum = DB::table($table)
            ->where($column, 'LIKE', $prefixLike . '%')
            ->where($column, 'LIKE', '%' . $suffixLike)
            ->select(DB::raw("
                MAX(
                    CAST(
                        SUBSTRING(
                            REPLACE(SUBSTRING($column, $sqlStartPos), '$suffixLike', ''),
                        1)
                        AS UNSIGNED
                    )
                ) AS max_num
            "))
            ->first()
            ->max_num ?? 0;

        $newNum = $maxNum + 1;
        $newValue = str_pad($newNum, $pad, '0', STR_PAD_LEFT);

        return $prefix . $newValue . $suffix;
    }

    private function replacePlaceholders(string $format, DateTime $date, array $args = []): string
    {
        $map = [
            '{MMM}' => strtoupper($date->format('F')), // DECEMBER
            '{MM}' => strtoupper($date->format('M')), // DEC
            '{M}' => $date->format('m'), // 12
            '{YYYY}' => $date->format('Y'), // 2025
            '{YY}' => $date->format('y'), // 25
            '{DD}' => $date->format('d'), // 09
            '{D}' => $date->format('j'), // 9
            '{MMYY}' => $date->format('my'), // 1225
            '{MMYYYY}' => $date->format('mY'), // 122025
            '{YYMM}' => $date->format('ym'), // 2512
            '{YYYYMM}' => $date->format('Ym'), // 202512
            '{DDMMYY}' => $date->format('dmy'), // 041225
        ];

        foreach ($args as $key => $value) {
            $map['{' . strtoupper($key) . '}'] = strtoupper($value);
        }

        uksort($map, fn($a, $b) => strlen($b) <=> strlen($a));

        return str_replace(array_keys($map), array_values($map), $format);
    }
}
