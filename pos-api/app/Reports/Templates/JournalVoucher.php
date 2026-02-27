<?php

namespace App\Reports\Templates;

use App\Enums\TransType;
use App\Models\AccountType;
use App\Models\ChartAccount;
use App\Models\JournalEntry;

use App\Models\Payment;
use DB;
use App\Models\Journal;
use Illuminate\Support\Collection;

class JournalVoucher
{
    public function __construct(protected string $uuid)
    {

    }

    public static function make($uuid)
    {
        return (new self($uuid))->handle();
    }

    protected function handle()
    {
        $journal_entries = JournalEntry::with([
            'details.chartAccount'
        ])
            ->where("uuid", $this->uuid)->first();
        if (!$journal_entries) {
            throw new \Exception("Not existing voucher");
        }

        $reports['journal_entries'] = $journal_entries;
        $reports['total'] = $this->total(
            $journal_entries->details
        );
        return $reports;
    }

    private function total(Collection $journals)
    {
        $debits = $journals->sum('debit');
        $credits = $journals->sum('credit');

        return [
            'debits' => $debits,
            'credits' => $credits != 0 ? '(' . abs($credits) . ')' : $credits,
        ];
    }
}