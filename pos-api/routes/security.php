<?php

use App\Http\Controllers\Security\AuditController;
use App\Http\Controllers\Security\PoliciesController;
use App\Http\Controllers\Security\PositionsController;
use App\Http\Controllers\Security\RolesController;
use App\Http\Controllers\Security\UsersController;
use Illuminate\Support\Facades\Route;

Route::prefix('security')->name('security.')->group(function () {
    // Users
    Route::controller(UsersController::class)->prefix('users')->name('users.')->group(function () {
        Route::delete('/selected', 'destroySelected')->name('destroy.selected');
        Route::post('/list', 'index')->name('list');
        Route::get('/activities', 'activities')->name('activities');
        Route::post('change-password-profile', 'changePasswordOnProfile');
        Route::put('/update-profile/{user}', 'updateProfile');
        Route::patch('change-password-user/{user}', 'changePasswordOnUser');
    });

    Route::apiResource('users', UsersController::class);

    // Policies and permissions
    Route::controller(PoliciesController::class)->prefix('policies')->name('policies.')->group(function () {
        Route::post('/list', 'index')->name('list');
        Route::post('/set/{role}/{action}', 'set')->name('set');
    });

    // Roles
    Route::controller(RolesController::class)->prefix('roles')->name('roles.')->group(function () {
        Route::post('/list', 'index')->name('list');
        Route::post('/{role}/permissions', 'getPermissions')->name('permissions');
        // Route::patch('/{role}', 'update')->name('update');
    });
    Route::apiResource('policies', PoliciesController::class);
    Route::apiResource('roles', RolesController::class);

    // User-Positions
    Route::apiResource('/user-positions', PositionsController::class);

    // Audit
    Route::apiResource('audit-trails', AuditController::class)->only(['index', 'show'])->scoped();

    // User profile update
    Route::patch('users/profile/{user}', [UsersController::class, 'updateProfile'])->name('users.profile.update');
});