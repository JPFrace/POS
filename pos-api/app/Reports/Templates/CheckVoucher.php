<?php

namespace App\Reports\Templates;

use App\Enums\TransType;
use App\Models\AccountType;
use App\Models\ChartAccount;
use App\Models\Payment;
use DB;
use App\Models\Journal;
use Illuminate\Support\Collection;

class CheckVoucher
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
        $payment = Payment::with(
            [
                'journals' => function ($query) {
                    return $query->orderBy('seq', 'asc')->with('chartAccount.usageType');
                },
                'payee',
                'details.product.expenseAccount',
                'cashBank'
            ]
        )
            ->whereHas('journals')
            ->where("uuid", $this->uuid)->first();

        if (!$payment) {
            throw new \Exception("Not existing voucher");
        }

        $reports['payment'] = $payment;
        $reports['total'] = $this->total(collect($payment->journals));

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