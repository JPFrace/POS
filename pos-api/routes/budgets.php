<?php

use App\Http\Controllers\budgeting\BudgetPeriodsController;
use App\Http\Controllers\Budgeting\BudgetsController;
use App\Http\Controllers\Budgeting\BudgetTypesController;
use Illuminate\Support\Facades\Route;

Route::prefix('budgeting')->name('budgeting.')->group(function () {
    Route::apiResource("budget", BudgetsController::class);    
    Route::apiResource("budget-type", BudgetTypesController::class);
    Route::apiResource("budget-period", BudgetPeriodsController::class);
    Route::post('budget/post/{budget}', [BudgetsController::class, 'post']);
    Route::post('budget/unpost/{budget}', [BudgetsController::class, 'unpost']);
    Route::post('budget/save-as-new/{budget}', [BudgetsController::class, 'saveAsNew']);
});
