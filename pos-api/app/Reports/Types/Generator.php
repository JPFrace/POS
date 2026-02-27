<?php

namespace App\Reports\Types;

use App\Enums\AccountCategory;
use App\Enums\AccountUsageType;
use App\Models\AccountClass;
use Carbon\Carbon;
use DB;

class Generator
{
    public function __construct(
        private array $dates,
        private ?\Closure $classWhere = null,
        private ?\closure $chartWhere
    ) {

    }

    public function handle()
    {
        $dates = $this->dates;

        $classes = AccountClass::select('account_classes.*')->join('chart_accounts', 'chart_accounts.class_id', '=', 'account_classes.id')
            ->leftJoin('account_types', 'account_types.id', '=', 'chart_accounts.type_id')
            ->leftJoin('account_categories', 'account_categories.id', '=', 'account_types.category_id')
            ->when(!empty($this->classWhere), fn($query) => $query->where($this->classWhere))
            ->orderBy('sort', 'asc')
            ->groupBy('account_classes.id')
            ->get();

        $year = Carbon::parse($dates[0])->format('Y');
        $month = Carbon::parse($dates[0])->format('m');

        $data = collect([]);
        foreach ($classes as $class) {
            $children = [];
            $charts = $class->chartAccounts()->with(['usageType', 'parent'])
                ->when(
                    !empty($this->chartWhere),
                    $this->chartWhere
                )
                ->get();

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

                $children[] = [
                    ...$chart->toArray(),
                    'debits' => $debits,
                    'credits' => $credits,
                    'budget_to_date' => 0,
                    'current_month_debits' => $currentMonthDebits,
                    'current_month_credits' => $currentMonthCredits,
                    'year_to_date_debits' => $yearToDateDebits,
                    'year_to_date_credits' => $yearToDateCredits
                ];
            }

            $beginningBalance = array_reduce($children, function ($sum, $chart) {
                $sum += $chart['balance'];

                return $sum;
            }, 0);


            $data->push([
                'name' => $class->name,
                'beginning_balance' => $beginningBalance,
                'children' => collect($children)
            ]);
        }

        return $data;
    }
}

