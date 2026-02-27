<?php

use App\Http\Controllers\Products\ProductCategoriesController;
use App\Http\Controllers\Products\ProductsController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->name('products.')->group(function () {
    Route::apiResource('product-categories', ProductCategoriesController::class);
    Route::apiResource('products', ProductsController::class);
});
