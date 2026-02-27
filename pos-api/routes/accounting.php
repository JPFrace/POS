<?php

use App\Http\Controllers\Accounting\AccountCategoriesController;
use App\Http\Controllers\Accounting\ChartAccountsController;
use App\Http\Controllers\Accounting\AccountClassController;
use App\Http\Controllers\Accounting\AccountTypesController;
use App\Http\Controllers\Accounting\AccountUsageTypesController;
use App\Http\Controllers\Accounting\CalendarController;
use App\Http\Controllers\Accounting\DimensionsController;
use App\Http\Controllers\Accounting\TransactionTemplateController;
use App\Http\Controllers\Accounting\ReconciliationController;
use Illuminate\Support\Facades\Route;

Route::prefix('accounting')->group(function () {
    Route::apiResource("account-types", AccountTypesController::class);
    Route::apiResource("chart-accounts", ChartAccountsController::class);
    Route::apiResource("account-categories", AccountCategoriesController::class)->only('index');
    Route::apiResource("account-classes", AccountClassController::class);
    Route::apiResource("calendars", CalendarController::class);
    Route::get('calendar-years', [CalendarController::class, 'getCalendars'])->name('calendar-years');
    Route::apiResource("account-usage-types", AccountUsageTypesController::class);
    Route::apiResource("dimensions", DimensionsController::class);
    Route::apiResource("transaction-templates", TransactionTemplateController::class);
    Route::controller(ReconciliationController::class)->prefix("reconciliations")->name('.reconciliations')->group(function () {
        Route::get('/pending-reconciliations', 'getAllPendingReconciliations');
        Route::get('/transaction-list', 'getTransactions');

        Route::post('/get-transactions', 'getTransactions');
        Route::post('/previous-reconciliation/{bank}', 'getPreviousReconciliation');
        Route::post('/save-reconciliation', 'storeReconciliation');
        Route::post('/reconcile-transaction', 'reconcileTransaction');
        Route::post('/reconciled-transactions', 'reconciledTransactions');

    });
});

Route::apiResource('transactions', ChartAccountsController::class)
    ->parameters([
        'transactions' => 'chartAccount',
    ])
    ->only(['show']);