<?php

namespace App\Reports\Templates;

use App\Models\AccountType;
use App\Models\ChartAccount;
use DB;
use App\Models\Journal;
use Illuminate\Support\Collection;

class BalanceSheet
{
    public function __construct(protected array $dates)
    {

    }

    public static function make(array $dates)
    {
        return (new self($dates))->handle();
    }

    protected function handle()
    {
        $dates = $this->dates;

        $reports['current_assets'] = $this->query($dates, AccountType::whereIn('Name', [
            'Accounts Receivable',
            'Cash',
            'Inventory',
            'Other Current Assets'
        ])->pluck('id')->toArray())->reject(function ($row) {
            return $row->debits <= 0;
        })->values();
        $reports['total_current_assets'] = $this->total($reports['current_assets']);

        $reports['property_assets'] = $this->query($dates, AccountType::whereIn('Name', [
            'Fixed Assets',
            'Accumulated Depreciation',
        ])->pluck('id')->toArray())->reject(function ($row) {
            return $row->debits <= 0;
        })->values();
        $reports['total_property_assets'] = $this->total($reports['property_assets']);

        $reports['total_assets'] = $reports['total_property_assets']['debits'] + $reports['total_current_assets']['debits'];

        $reports['current_liabilities'] = $this->query($dates, AccountType::whereIn('Name', [
            'Other Current Liabilities',
            'Accounts Payable',
        ])->pluck('id')->toArray())->reject(function ($row) {
            return $row->debits <= 0;
        })->values();
        $reports['total_current_liabilities'] = $this->total($reports['current_liabilities']);

        $reports['longterm_liabilities'] = $this->query($dates, AccountType::whereIn('Name', [
            'Long Term Liabilities',
        ])->pluck('id')->toArray())->reject(function ($row) {
            return $row->debits <= 0;
        })->values();
        $reports['total_longterm_liabilities'] = $this->total($reports['longterm_liabilities']);

        $reports['total_liabilities'] = $reports['total_longterm_liabilities']['debits'] + $reports['total_current_liabilities']['debits'];

        $reports['capital'] = $this->query($dates, AccountType::whereIn('Name', [
            "Equity-doesn't close",
            "Equity-Retained Earnings"
        ])->pluck('id')->toArray())->reject(function ($row) {
            return $row->credits <= 0;
        })->values();
        $reports['total_capital'] = $this->total($reports['capital']);

        $reports['total_liabilities_capital'] = $reports['total_liabilities'] + $reports['total_capital']['credits'];

        return $reports;
    }

    private function query($dates, array $types)
    {
        return ChartAccount::withSum([
            'journals as credits' => function ($query) use ($dates) {
                $query->whereBetween(DB::raw('DATE(posted_at)'), [
                    $dates[0],
                    $dates[1],
                ]);
            }
        ], 'credit')
            ->withSum([
                'journals as debits' => function ($query) use ($dates) {
                    $query->whereBetween(DB::raw('DATE(posted_at)'), [
                        $dates[0],
                        $dates[1],
                    ]);
                }
            ], 'debit')
            ->whereIn('type_id', $types)
            ->orderBy('name', 'asc')
            ->get();
    }

    private function total(Collection $data)
    {
        $debits = $data->sum("debits");
        $credits = $data->sum("credits");

        return [
            'debits' => $debits,
            'credits' => $credits,
        ];
    }
}