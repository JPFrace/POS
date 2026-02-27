<?php


use App\Http\Controllers\Setup\BankAccountsController;
use App\Http\Controllers\Setup\CompanyController;
use App\Http\Controllers\Setup\ConfigController;
use App\Http\Controllers\Setup\DepartmentsController;
use App\Http\Controllers\Setup\PaymentTypesController;
use App\Http\Controllers\Setup\ReportsController;
use App\Http\Controllers\Setup\ReportSignatoryController;
use App\Http\Controllers\Setup\SignatoriesController;
use App\Http\Controllers\Setup\WithholdingTaxController;
use App\Http\Controllers\Setup\WithholdingTaxTypesController;
use App\Http\Controllers\Setup\TaxesController;
use Illuminate\Support\Facades\Route;

Route::prefix('setup')->name('setup.')->group(function () {
    Route::apiResource('bank-accounts', BankAccountsController::class);
    Route::apiResource("payment-types", PaymentTypesController::class);
    Route::apiResource("departments", DepartmentsController::class);
    Route::apiResource('company', CompanyController::class);
    Route::apiResource('reports', ReportsController::class);
    Route::apiResource('signatories', SignatoriesController::class);
    Route::apiResource('withholding-tax', WithholdingTaxController::class);
    Route::apiResource('withholding-tax-types', WithholdingTaxTypesController::class);
    Route::apiResource('taxes', TaxesController::class);
    Route::apiResource('config', ConfigController::class);
    Route::apiResource('report-signatories', ReportSignatoryController::class);
    Route::prefix('profile')->name('profile.')->group(function () {
    });
});
