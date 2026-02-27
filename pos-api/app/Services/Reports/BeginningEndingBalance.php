<?php

namespace App\Services\Reports;

use App\Models\ChartAccountBalance;
use App\Enums\NormalBalance;
use App\Models\ChartAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use DB;

class BeginningEndingBalance
{
    public function __construct(protected ChartAccount $account, protected Carbon $start, protected Carbon $end)
    {

    }

    public static function generate(ChartAccount $account, Carbon $start, Carbon $end)
    {
        return (new self($account, $start, $end))->handle();
    }

    protected function handle(): self
    {
        return $this;
    }

    public function daily(): void
    {
        $beginning = (float) $this->account->balance;

        $months = ceil($this->diffMonths());

        $start = clone $this->start->subMonth();

        for ($m = 0; $m < $months; $m++) {
            $start->addMonth();
            $date = clone $start->startOfMonth();

            $startd = clone $date;
            $endd = $date->endOfMonth();

            $days = $startd->diffInDays($endd);

            $startd->subDay();

            for ($d = 0; $d < $days; $d++) {
                $startd->addDay();

                $this->record(clone $startd, clone $startd, $beginning);
            }
        }

        $this->account->running_balance = $beginning;
        $this->account->save();
    }

    public function runtime(): void
    {
        // Record beginning balance
        $balance = ChartAccountBalance::where([
            'start_at' => $this->start->yesterday()->startOfDay()->format('Y-m-d'),
            'end_at' => $this->start->yesterday()->endOfDay()->format('Y-m-d'),
            'chart_account_id' => $this->account->id
        ])->latest()->first();

        $beginning = $balance?->ending;

        // It is important to get the beginning balance of the account
        // to get the accurate running balance
        if (empty($beginning)) {
            $beginning = $this->account->balance;
            if (!empty($this->account->running_balance)) {
                $beginning = $this->account->running_balance;
            }
        }

        $beginning = $beginning ?: 0;

        $this->record(
            clone $this->start->startOfDay(),
            clone $this->end->endOfDay(),
            $beginning
        );

        $this->account->running_balance = $beginning;
        $this->account->save();
    }

    public function monthly(): void
    {
        $beginning = (float) $this->account->balance;

        $months = ceil($this->diffMonths());

        $start = clone $this->start->subMonth();

        for ($m = 0; $m < $months; $m++) {
            $start->addMonth();
            $date = clone $start->startOfMonth();

            $this->record(clone $date, clone $date->endOfMonth(), $beginning);
        }

        // $this->account->running_balance = $beginning;
        // $this->account->save();
    }

    public function period(): void
    {
        $beginning = (float) $this->account->balance;

        $this->record(
            clone $this->start->startOfMonth(),
            clone $this->end->endOfMonth(),
            $beginning
        );

        $this->account->running_balance = $beginning;
        $this->account->save();
    }

    protected function record(Carbon $start, Carbon $end, float &$beginning)
    {
        $account = $this->account->id;

        // Record beginning balance
        $balance = ChartAccountBalance::create([
            'start_at' => $start,
            'end_at' => $end,
            'beginning' => $beginning,
            'chart_account_id' => $account
        ]);

        $journals = $this->query($start, $end);

        // Calculate total debits and credits
        $total = $this->calculateTotal($journals);

        $beginning += $total;

        // Record ending balance after transactions
        ChartAccountBalance::updateOrCreate(['id' => $balance->id], [
            'start_at' => $start,
            'end_at' => $end,
            'ending' => $beginning,
            'chart_account_id' => $account
        ]);
    }

    protected function calculateTotal(Collection $journals)
    {
        $total = 0;
        $debits = $journals->sum('debit');
        $credits = $journals->sum('credit');

        $category = $this->account?->type?->category;

        if (empty($category)) {
            return 0;
        }

        if ($category->normal_balance == NormalBalance::DEBIT) {
            $total += $debits;
            $total -= $credits;
        }

        if ($category->normal_balance == NormalBalance::CREDIT) {
            $total += $credits;
            $total -= $debits;
        }

        return $total;
    }

    public function getBeginningBalance(): float
    {
        return $this->account->balance;
    }

    public function getEndingBalance(): float
    {
        return $this->ending;
    }

    public function getData(): array
    {
        return $this->data;
    }

    private function query(Carbon $start, Carbon $end)
    {
        return $this->account->journals()
            ->whereBetween(DB::raw("date(posted_at)"), [
                $start->format('Y-m-d'),
                $end->format('Y-m-d')
            ])
            ->get();
    }

    private function diffMonths()
    {
        return $this->start->diffInMonths($this->end);
    }
}