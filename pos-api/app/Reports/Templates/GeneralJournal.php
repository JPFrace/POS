<?php

namespace App\Reports\Templates;

use App\Enums\TransType;
use App\Facades\SystemConfig;
use App\Models\Journal;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GeneralJournal
{
    public function __construct(protected array $dates)
    {

    }

    public static function make(array $dates): array
    {
        return (new self($dates))->handle();
    }

    protected function handle()
    {
        [$dateFrom, $dateTo] = $this->dates;

        $journals = Journal::query()
            ->with('chartAccount', 'transactable')
            ->whereBetween(DB::raw('DATE(posted_at)'), [$dateFrom, $dateTo])
            ->where('trans_type', TransType::JOURNAL)
            ->orderBy('posted_at')
            ->orderBy('ref_no')
            ->orderBy('seq')
            ->get();

        $showZero = (bool) (SystemConfig::get('reports_show_zero_amounts')->value ?? 0);

        return [
            'journals' => $this->formatZeroAmounts($journals->toArray(), $showZero),
            'total' => $this->total($journals),
        ];
    }

    private function formatZeroAmounts(array $data, bool $showZero): array
    {
        if ($showZero) {
            return $data;
        }
        foreach ($data as &$journal) {
            if (isset($journal['debit']) && (float) $journal['debit'] === 0.0) {
                $journal['debit'] = '';
            }
            if (isset($journal['credit']) && (float) $journal['credit'] === 0.0) {
                $journal['credit'] = '';
            }
        }
        return $data;
    }

    private function total(Collection $journals): array
    {
        return [
            'debits' => $journals->sum('debit'),
            'credits' => $journals->sum('credit'),
        ];
    }
}
