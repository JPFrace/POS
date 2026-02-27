<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Reconciliations\ReconcileRequest;
use App\Http\Requests\Accounting\Reconciliations\ReconciliationsRequest;
use App\Models\BankAccount;
use App\Models\ChartAccount;
use App\Models\Journal;
use App\Models\Reconciliation;
use App\Repositories\ReconciliationRepository;
use Carbon\Carbon;
use Exception;

class ReconciliationController extends Controller
{
    public function __construct(protected ReconciliationRepository $repository)
    {
    }

    public function getAllPendingReconciliations()
    {
        return $this->repository->allPendingReconciliations();
    }

    public function getTransactions(Request $request)
    {
        $cib = ChartAccount::where('uuid', $request->bank_account)->first()->id;
        $beginning_date = Carbon::parse($request->beginning_date . " 00:00:00", 'Asia/Manila')->utc();
        $ending_date = Carbon::parse($request->end_date . " 23:59:59", 'Asia/Manila')->utc();

        return [
            'data' => $this->repository->transactionList($cib, $beginning_date, $ending_date)
        ];
    }

    public function getPreviousReconciliation(BankAccount $bank)
    {
        $reconciliation = Reconciliation::where('account_id', $bank->account_id)->where('closed_at', "!=", null)->orderByDesc('end_at')->with(['user', 'cashInBank.bank'])->get();
        return $reconciliation[0] ?? null;
    }

    public function storeReconciliation(ReconciliationsRequest $request)
    {
        $reconciliationsCount = Reconciliation::where('account_id', $request->account_id)->where('closed_at', null)->count();
        throw_if($reconciliationsCount, Exception::class, 'Open reconciliation detected on the bank account');

        $reconciliation = $this->catch(fn() => $this->repository->create($request->only([
            'account_id',
            'calendar_id',
            'ending_balance',
            'bank_statement_ending_balance',
            'start_at',
            'end_at'
        ])), true);

        $session = [
            ...$reconciliation,
            'cash_in_bank' => $reconciliation->cashInBank()->first()
        ];

        return $session;
    }

    public function updateReconciliation(ReconciliationsRequest $request, Reconciliation $reconciliation)
    {

    }

    public function reconcileTransaction(ReconcileRequest $request)
    {
        $journal = Journal::where('id', $request->journal_id)->first();
        $journal->reconcile_at = match ($request->event) {
            'post' => Carbon::now(),
            default => null
        };

        $journal->save();

        return [
            'reconcile_at' => $journal->reconcile_at,
            'trans_total' => $this->repository->calculateReconciledTransactions(
                $journal->chart_account_id,
                $request->transaction_type,
                $request->beginning_at,
                $request->ending_at
            )->total ?? 0
        ];
    }

    public function reconciledTransactions(Request $request)
    {
        $cib = ChartAccount::where('uuid', $request->account_id)->first()->id;
        return [
            'deposits' => $this->repository->calculateReconciledTransactions(
                $cib,
                "70",
                $request->beginning_at,
                $request->ending_at
            )->total ?? 0,
            'sales' => $this->repository->calculateReconciledTransactions(
                $cib,
                "20",
                $request->beginning_at,
                $request->ending_at
            )->total ?? 0,
            'payments' => $this->repository->calculateReconciledTransactions(
                $cib,
                "30",
                $request->beginning_at,
                $request->ending_at
            )->total ?? 0
        ];
    }
}
