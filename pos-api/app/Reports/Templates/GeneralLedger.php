<?php

namespace App\Reports\Templates;

use App\Enums\AccountCategory;
use App\Facades\SystemConfig;
use App\Models\ChartAccount;
use App\Models\OfficialReceipt;
use App\Services\Reports\BeginningEndingBalance;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class GeneralLedger
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

        $start = Carbon::parse($dates[0]);
        $end = Carbon::parse($dates[1]);

        $reports = [];

        $accounts = $this->queryOfChartAccounts($start, $end, AccountCategory::ASSETS);
        foreach ($accounts as $account) {
            $beginning = $account->balancesAt($start, $start)->get();
            $ending = $account->balancesAt($end, $end)->get();
            $beginning = $beginning->filter(fn($row) => $row->beginning > 0)->first();
            $ending = $ending->filter(fn($row) => $row->ending > 0)->first();

            $journals = $this->queryOfJournals($start, $end, $account);
            $showZero = (bool) (SystemConfig::get('reports_show_zero_amounts')->value ?? 0);

            $reports[] = [
                'account' => $account,
                'beginning' => $beginning,
                'ending' => $ending,
                'journals' => $journals,
                "config" => [
                    'showZero' => $showZero
                ]
            ];
        }

        return $reports;
    }

    private function queryOfChartAccounts($start, $end, $category)
    {
        return ChartAccount::whereHas("journals", function ($query) use ($start, $end) {
            return $query->whereBetween(DB::raw('DATE(posted_at)'), [
                $start->format('Y-m-d'),
                $end->format('Y-m-d'),
            ]);
        })
            ->whereHas('type', function ($query) use ($category) {
                return $query->whereRelation('category', 'name', $category);
            })
            ->orderBy('name', 'asc')
            ->get();
    }

    private function queryOfJournals($start, $end, ChartAccount $account)
    {
        return $account->journals()->with(['transactable', 'financialCode'])->whereBetween(DB::raw('DATE(posted_at)'), [
            $start->format('Y-m-d'),
            $end->format('Y-m-d'),
        ])->orderBy('posted_at', 'asc')->get();
    }

    private function diffMonths($start, $end)
    {
        return $start->diffInMonths($end);
    }
}