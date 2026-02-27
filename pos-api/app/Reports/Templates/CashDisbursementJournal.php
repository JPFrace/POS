<?php

namespace App\Reports\Templates;

use App\Enums\TransType;
use App\Facades\SystemConfig;
use App\Models\AccountType;
use App\Models\ChartAccount;
use App\Models\Payment;
use DB;
use App\Models\Journal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CashDisbursementJournal
{
    public function __construct(protected array $dates) {}

    public static function make(array $dates)
    {
        return (new self($dates))->handle();
    }

    protected function handle()
    {
        $dates = $this->dates;

        $data = Payment::with(
            ['journals.chartAccount', 'payee', 'details.product.expenseAccount', 'cashBank']
        )
            ->whereHas('journals', function (Builder $query) use ($dates) {
                return $query->whereBetween(DB::raw('DATE(posted_at)'), [
                    $dates[0],
                    $dates[1]
                ]);
            })
            ->orderBy('created_at', 'asc')->get();

        $showZero = (bool) (SystemConfig::get('reports_show_zero_amounts')->value ?? 0);

        $reports['data'] = $this->formatZeroAmounts($data->toArray(), $showZero);
        $reports['total'] = $this->total($data);

        return $reports;
    }

    private function formatZeroAmounts(array $data, bool $showZero): array
    {
        if ($showZero) {
            return $data;
        }
        foreach ($data as &$receipt) {
            if (isset($receipt['journals']) && is_array($receipt['journals'])) {
                foreach ($receipt['journals'] as &$journal) {
                    if (isset($journal['debit']) && (float) $journal['debit'] === 0.0) {
                        $journal['debit'] = '';
                    }
                    if (isset($journal['credit']) && (float) $journal['credit'] === 0.0) {
                        $journal['credit'] = '';
                    }
                }
            }
        }
        return $data;
    }

    private function total(Collection $data)
    {
        $debits = $data->sum(function ($row) {
            return $row->journals->sum('debit');
        });
        $credits = $data->sum(function ($row) {
            return $row->journals->sum('credit');
        });

        return [
            'debits' => $debits,
            'credits' => $credits,
        ];
    }
}
