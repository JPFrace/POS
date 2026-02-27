<?php

namespace App\Reports\Templates;

use App\Facades\SystemConfig;
use App\Models\OfficialReceipt;
use Illuminate\Support\Facades\DB;

class CashReceiptsJournal
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
        $dateFrom = $dates[0];
        $dateTo = $dates[1];

        $showZero = (bool) (SystemConfig::get('reports_show_zero_amounts')->value ?? 0);

        $reports['total'] = $this->totalFromDb($dateFrom, $dateTo);

        $reports['data'] = [];
        $baseQuery = OfficialReceipt::with([
            'journals:id,transactable_type,transactable_id,chart_account_id,debit,credit',
            'journals.chartAccount:id,code,name',
            'details:id,or_id,product_name,quantity,rate',
            'denominations:or_id,payment_method_id,reference_no',
            'denominations.payment_method:id,name',
        ])
            ->whereHas('journals')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->orderBy('date', 'asc')
            ->select(['id', 'uuid', 'date', 'customer_name', 'or_no']);

        foreach ($baseQuery->lazy(500) as $receipt) {
            $reports['data'][] = $receipt->toArray();
        }
        $reports['data'] = $this->formatZeroAmounts($reports['data'], $showZero);

        return $reports;
    }

    private function totalFromDb(string $dateFrom, string $dateTo): array
    {
        $morphType = (new OfficialReceipt)->getMorphClass();
        $totals = DB::table('journals')
            ->join('official_receipts', function ($join) use ($morphType) {
                $join->on('journals.transactable_id', '=', 'official_receipts.id')
                    ->where('journals.transactable_type', '=', $morphType);
            })
            ->whereBetween('official_receipts.date', [$dateFrom, $dateTo])
            ->whereNull('official_receipts.deleted_at')
            ->selectRaw('COALESCE(SUM(journals.debit), 0) as debits, COALESCE(SUM(journals.credit), 0) as credits')
            ->first();

        return [
            'debits' => (float) $totals->debits,
            'credits' => (float) $totals->credits,
        ];
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

}