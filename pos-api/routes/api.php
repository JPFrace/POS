<?php

use App\Http\Controllers\AccessTokenController;
use App\Http\Controllers\CommonController;

use App\Http\Controllers\SocialiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user()->load('roles.role.permissions.action.policy');
})->middleware('auth:sanctum');

Route::get('/generate-token', [AccessTokenController::class, 'generateToken'])->name('generate.token')->middleware('restrict.ip');

Route::group(['middleware' => ['auth:sanctum']], function () {
    require_once __DIR__ . "/security.php";
    require_once __DIR__ . "/setup.php";
    require_once __DIR__ . "/accounting.php";
    require_once __DIR__ . "/contacts.php";
    require_once __DIR__ . "/business.php";
    require_once __DIR__ . "/products.php";
    require_once __DIR__ . "/reports.php";
    require_once __DIR__ . "/budgets.php";
    require_once __DIR__ . "/externals.php";
    require_once __DIR__ . "/taxes.php";

});

Route::prefix('common')->name('common.')->group(function () {
    Route::post('index', [CommonController::class, 'index'])->name('index');
});

Route::prefix("auth")->controller(SocialiteController::class)->group(function () {
    Route::post('google/authorization', 'authorization')->name('google.authorization')->middleware("auth:sanctum");
});
