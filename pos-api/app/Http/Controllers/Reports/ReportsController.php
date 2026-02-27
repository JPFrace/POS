<?php

namespace App\Http\Controllers\Reports;

use App\Enums\AccountCategory;
use App\Enums\AccountUsageType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Business\PaymentResource;
use App\Models\Payment;
use App\Models\Report;
use App\Reports\LedgerQuery;
use App\Reports\Templates\BalanceSheet;
use App\Reports\Templates\CashDisbursementJournal;
use App\Reports\Templates\CashReceiptsJournal;
use App\Reports\Templates\GeneralJournal;
use App\Reports\Templates\JournalVoucher;
use App\Reports\Templates\GeneralLedger;
use App\Reports\Templates\StatementIncomeExpenses;
use App\Reports\Templates\TrialBalance;
use App\Reports\Templates\CheckVoucher;
use App\Reports\Templates\CustomerLedger;
use App\Reports\Templates\OfficialReceiptReport;
use App\Repositories\ReportsRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function __construct(protected ReportsRepository $repository)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function statementIncomeExpenses(Request $request)
    {
        $report = Report::where('uuid', $request->report_id)
            ->with([
                'reportSignatories.signatory.position',
            ])
            ->first();


        $sie = StatementIncomeExpenses::make([
            Carbon::parse($request->get('date_from'))->format('Y-m-d'),
            Carbon::parse($request->get('date_to'))->format('Y-m-d'),
        ]);
        return ([
            ...$sie,
            'report' => $report
        ]);

    }

    /**
     * Display a listing of the resource.
     */
    public function generalJournal(Request $request)
    {
        return GeneralJournal::make([
            Carbon::parse($request->get('date_from'))->format('Y-m-d'),
            Carbon::parse($request->get('date_to'))->format('Y-m-d'),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function trialBalance(Request $request)
    {
        return TrialBalance::make([
            Carbon::parse($request->get('date_from'))->format('Y-m-d'),
            Carbon::parse($request->get('date_to'))->format('Y-m-d'),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function balanceSheet(Request $request)
    {
        $report = Report::where('uuid', $request->report_id)
            ->with([
                'reportSignatories.signatory.position',
            ])
            ->first();


        $bs = balanceSheet::make([
            Carbon::parse($request->get('date_from'))->format('Y-m-d'),
            Carbon::parse($request->get('date_to'))->format('Y-m-d'),
        ]);
        return ([
            ...$bs,
            'report' => $report
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function cashDisbursementJournal(Request $request)
    {
        return CashDisbursementJournal::make([
            Carbon::parse($request->get('date_from'))->format('Y-m-d'),
            Carbon::parse($request->get('date_to'))->format('Y-m-d'),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function cashReceiptsJournal(Request $request)
    {
        return CashReceiptsJournal::make([
            Carbon::parse($request->get('date_from'))->format('Y-m-d'),
            Carbon::parse($request->get('date_to'))->format('Y-m-d'),
        ]);
    }

    // Shared function for both Check Voucher and Cheque Layout requests.
    // Returns the payment data; frontend decides what to render.
    public function showPayment(Request $request)
    {
        return CheckVoucher::make(
            $request->get('uuid')
        );
    }

    public function journalVoucher(Request $request)
    {
        return JournalVoucher::make(
            $request->get('uuid')
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function generalLedger(Request $request)
    {
        return GeneralLedger::make([
            Carbon::parse($request->get('date_from'))->format('Y-m-d'),
            Carbon::parse($request->get('date_to'))->format('Y-m-d'),
        ]);
    }

    // For OR
    public function officialReceiptReport(Request $request)
    {
        return OfficialReceiptReport::make(
            $request->get('uuid')
        );
    }

    // For Customer Ledger
    public function CustomerLedger(Request $request)
    {
        return CustomerLedger::make([
            Carbon::parse($request->get('date_from'))->format('Y-m-d'),
            Carbon::parse($request->get('date_to'))->format('Y-m-d'),
        ]);
    }
}
