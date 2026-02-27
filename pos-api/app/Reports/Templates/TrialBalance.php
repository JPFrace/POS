<?php

namespace App\Reports\Templates;

use App\Enums\AccountCategory;
use App\Enums\AccountUsageType;
use App\Enums\NormalBalance;
use App\Facades\SystemConfig;
use App\Models\ChartAccount;
use App\Reports\LedgerQuery;
use GuzzleHttp\Psr7\Query;
use DB;

class TrialBalance
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
        $charts = $this->chartJournals($this->dates[0], $this->dates[1]);

        return $this->generateReport($charts);
    }

    protected function chartJournals($startDate, $endDate)
    {
        return ChartAccount::withSum([
            'journals as credit' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween(DB::raw('DATE(posted_at)'), [
                    $startDate,
                    $endDate,
                ]);
            }
        ], 'credit')
            ->withSum([
                'journals as debit' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween(DB::raw('DATE(posted_at)'), [
                        $startDate,
                        $endDate,
                    ]);
                }
            ], 'debit')
            ->with([
                'type' => function ($query) {
                    $query->select('id', 'category_id');
                },
                'type.category' => function ($query) {
                    $query->select('id', 'name', 'normal_balance');
                }
            ])->whereHas('journals')->get();
    }

    protected function generateReport($chartJournal)
    {
        $reports = [];
        $total = [
            'debit' => 0.0,
            'credit' => 0.0,
        ];

        $i = 0;

        foreach ($chartJournal as $chart) {
            $normalBal = $chart->type->category?->normal_balance;

            $reports[$i] = [
                'url' => $chart->url,
                'code' => $chart->code,
                'name' => $chart->name,
                'debit' => 0,
                'credit' => 0,
            ];

            $balance = $chart->debit - $chart->credit;

            if ($normalBal == NormalBalance::DEBIT) {
                if ($balance >= 0) {
                    $reports[$i]['debit'] = $balance;
                    $reports[$i]['credit'] = 0;
                } else {
                    $reports[$i]['debit'] = 0;
                    $reports[$i]['credit'] = abs($balance);
                }
            }

            if ($normalBal == NormalBalance::CREDIT) {
                $balance = $chart->credit - $chart->debit;
                if ($balance >= 0) {
                    $reports[$i]['credit'] = $balance;
                    $reports[$i]['debit'] = 0;
                } else {
                    $reports[$i]['credit'] = 0;
                    $reports[$i]['debit'] = abs($balance);
                }
            }

            $total['debit'] += $reports[$i]['debit'];
            $total['credit'] += $reports[$i]['credit'];

            $i++;
        }
        $showZero = (bool) (SystemConfig::get('reports_show_zero_amounts')->value ?? 0);

        return [
            "reports" => $reports,
            "total" => $total,
            "config" => [
            'showZero' => $showZero
            ]
        ];
    }
}
