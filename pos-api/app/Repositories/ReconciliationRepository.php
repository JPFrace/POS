<?php

namespace App\Repositories;

use App\Models\Journal;
use App\Models\Reconciliation;

class ReconciliationRepository extends Repository
{
    public function __construct(protected Reconciliation $model)
    {

    }

    public function allPendingReconciliations()
    {
        return Reconciliation::where("closed_at", null)->with('cashInBank.bank')->get();
    }

    public function transactionList($cib, $beginning_date, $ending_date)
    {
        return Journal::where('chart_account_id', $cib)
            ->where(function ($q) {
                $q->where(function ($q) {
                    $q->where('trans_type', 20)
                        ->where('credit', 0);
                })
                    ->orWhere(function ($q) {
                        $q->where('trans_type', 30)
                            ->where('debit', 0);
                    })
                    ->orWhere('trans_type', 70);
            })
            ->whereBetween('posted_at', [$beginning_date, $ending_date])
            ->with([
                'transactable.paymentMethod' => function ($q) {
                    $q->whereIn('code', ['CHK', 'DC', 'CC']);
                }
            ])
            ->get();
    }

    public function calculateReconciledTransactions($cib, $transaction_type, $beginning_date, $ending_date)
    {
        $dc = match ($transaction_type) {
            "20", "70" => 'debit',
            "30" => 'credit',
            default => ''
        };
        return Journal::selectRaw("SUM($dc) as total")
            ->where('chart_account_id', $cib)
            ->where('trans_type', $transaction_type)
            ->where('reconcile_at', '!=', 'null')
            ->whereBetween('posted_at', [$beginning_date, $ending_date])->first();
    }
}