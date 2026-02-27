<?php

use App\Http\Controllers\ExternalController;
use Illuminate\Support\Facades\Route;

Route::prefix('externals')->name('externals.')->group(function () {
    Route::apiResource("transactions", ExternalController::class);
});