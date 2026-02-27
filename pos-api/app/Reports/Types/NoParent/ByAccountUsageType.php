<?php

namespace App\Reports\Types\NoParent;

use App\Enums\AccountCategory;
use App\Enums\AccountUsageType;
use App\Models\AccountClass;
use App\Models\ChartAccount;
use Carbon\Carbon;
use DB;

class ByAccountUsageType
{
    public function __construct(private AccountUsageType $usage, private array $dates)
    {

    }

    public function handle()
    {
        $usage = $this->usage;
        $dates = $this->dates;

        $year = Carbon::parse($dates[0])->format('Y');
        $month = Carbon::parse($dates[0])->format('m');

        $data = collect([]);
        $charts = ChartAccount::with('usageType')->whereRelation('usageType', 'code', $usage->value)->get();
        foreach ($charts as $chart) {
            $debits = $chart->journals()->whereBetween(DB::raw('DATE(posted_at)'), [
                $dates[0],
                $dates[1],
            ])->sum('debit');

            $credits = $chart->journals()->whereBetween(DB::raw('DATE(posted_at)'), [
                $dates[0],
                $dates[1],
            ])->sum('credit');

            $currentMonthCredits = $chart->journals()->where(DB::raw('YEAR(posted_at)'), $year)
                ->where(DB::raw('MONTH(posted_at)'), $month)->sum('credit');
            $currentMonthDebits = $chart->journals()->where(DB::raw('YEAR(posted_at)'), $year)
                ->where(DB::raw('MONTH(posted_at)'), $month)->sum('credit');

            $yearToDateDebits = $chart->journals()->whereYear(DB::raw('YEAR(posted_at)'), $year)->sum('debit');
            $yearToDateCredits = $chart->journals()->whereYear(DB::raw('YEAR(posted_at)'), $year)->sum('credit');

            $data->push([
                ...$chart->toArray(),
                'debits' => $debits,
                'credits' => $credits,
                'current_month_debits' => $currentMonthDebits,
                'current_month_credits' => $currentMonthCredits,
                'year_to_date_debits' => $yearToDateDebits,
                'year_to_date_credits' => $yearToDateCredits,
                'budget_to_date' => 0
            ]);
        }

        return $data;
    }
}

