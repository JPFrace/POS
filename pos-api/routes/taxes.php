<?php

use App\Http\Controllers\Taxes\TaxAgencyController;
use App\Http\Controllers\Taxes\TaxController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Taxes\TaxSetupController;

Route::prefix('taxes')->name('taxes.')->group(function () {
    Route::apiResource("tax-setup", TaxSetupController::class);
    Route::apiResource("tax", TaxController::class);
    Route::apiResource("tax-agency", TaxAgencyController::class);

});
