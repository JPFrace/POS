<?php

use App\Http\Controllers\Reports\BookmarksController;
use App\Http\Controllers\Reports\ReportsController;
use Illuminate\Support\Facades\Route;

Route::prefix('reports')->controller(ReportsController::class)->name('reports.')->group(function () {
    Route::get('statement-income-expenses', 'statementIncomeExpenses')->name('statement-income-expenses');
    Route::get('general-journal', 'generalJournal')->name('general-journal');
    Route::get('trial-balance', 'trialBalance')->name('trial-balance');
    Route::get('balance-sheet', 'balanceSheet')->name('balance-sheet');
    Route::get('cash-disbursement-journal', 'cashDisbursementJournal')->name('cash-disbursement-journal');
    Route::get('cash-receipts-journal', 'cashReceiptsJournal')->name('cash-receipts-journal');
    Route::get('general-ledger', 'generalLedger')->name('general-ledger');
    Route::get('journal-voucher', 'journalVoucher')->name('journal-voucher');
    Route::get('official-receipt-report', 'officialReceiptReport')->name('official-receipt-report');
    Route::get('customer-ledger', 'customerLedger')->name('customer-ledger');

    Route::get('check-voucher', 'showPayment')->name('check-voucher');
    Route::get('cheque-layout', 'showPayment')->name('cheque-layout');

    Route::apiResource('bookmarks', BookmarksController::class);
});