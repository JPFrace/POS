<?php

use App\Http\Controllers\Contacts\ContactsController;
use App\Http\Controllers\Contacts\ContactSubTypesController;
use App\Http\Controllers\Contacts\CustomerContactsController;
use App\Http\Controllers\Contacts\CustomersController;
use App\Http\Controllers\Contacts\VendorContactsController;
use App\Http\Controllers\Contacts\VendorsController;
use App\Http\Controllers\Contacts\ContactClassController;
use App\Http\Controllers\Contacts\CountriesController;
use Illuminate\Support\Facades\Route;


Route::prefix('contacts')->group(function () {
    Route::apiResource("contacts", ContactsController::class);
    Route::apiResource('contact-sub-types', ContactSubTypesController::class)->only(['index'])->scoped();
    Route::apiResource("customers", CustomersController::class);
    Route::apiResource('customers.contacts', CustomerContactsController::class)->only(['destroy'])->scoped();
    Route::apiResource('vendors', VendorsController::class);
    Route::apiResource('vendors.contacts', VendorContactsController::class)->only(['destroy'])->scoped();
    Route::apiResource('contact-classes', ContactClassController::class);
    Route::apiResource('countries', CountriesController::class);
});