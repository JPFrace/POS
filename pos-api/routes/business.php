<?php

use App\Http\Controllers\Business\DepositsController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Business\BillTermsController;
use App\Http\Controllers\Business\BillsController;
use App\Http\Controllers\Business\BillStatusesController;
use App\Http\Controllers\Business\InvoicesController;
use App\Http\Controllers\Business\InvoiceStatusesController;
use App\Http\Controllers\Business\OrdersController;
use App\Http\Controllers\Business\OfficialReceiptsController;
use App\Http\Controllers\Business\OfficialReceiptStatusesController;
use App\Http\Controllers\Business\JournalEntriesController;
use App\Http\Controllers\Business\JournalEntryStatusesController;
use App\Http\Controllers\business\OfficialReceiptDenominationsController;
use App\Http\Controllers\Business\PaymentsController;
use App\Http\Controllers\Business\PaymentStatusesController;

Route::prefix('business')->name('business.')->group(function () {
    Route::patch("journal-entries/update-status", [JournalEntriesController::class, 'updateStatus']);
    Route::apiResource('journal-entry-statuses', JournalEntryStatusesController::class);
    Route::apiResource("journal-entries", JournalEntriesController::class);

    Route::patch("official-receipts/update-status", [OfficialReceiptsController::class, 'updateStatus']);
    Route::apiResource('official-receipt-statuses', OfficialReceiptStatusesController::class);
    Route::apiResource("official-receipts", OfficialReceiptsController::class);
    Route::apiResource("official-receipt-denominations", OfficialReceiptDenominationsController::class);

    Route::patch("payments/update-status", [PaymentsController::class, 'updateStatus']);
    Route::patch("payments/journals-preview", [PaymentsController::class, 'journalsPreview']);
    Route::apiResource('payment-statuses', PaymentStatusesController::class);
    Route::apiResource("payments", PaymentsController::class);

    Route::patch("invoices/update-status", [InvoicesController::class, 'updateStatus']);
    Route::apiResource('invoice-statuses', InvoiceStatusesController::class);
    Route::apiResource("invoices", InvoicesController::class);

    Route::patch("bills/update-status", [BillsController::class, 'updateStatus']);
    Route::apiResource('bill-statuses', BillStatusesController::class);
    Route::apiResource("bills", BillsController::class);

    Route::apiResource("orders", OrdersController::class);
    Route::apiResource("bills", BillsController::class);
    Route::apiResource("terms", BillTermsController::class);
    Route::apiResource("deposits", DepositsController::class);
});
