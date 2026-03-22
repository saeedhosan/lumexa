<?php

declare(strict_types=1);

use App\Http\Controllers\App\ActivityController;
use App\Http\Controllers\App\BreachController;
use App\Http\Controllers\App\CompanyController;
use App\Http\Controllers\App\LeadController;
use App\Http\Controllers\App\ProductController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/**
 * Here's defined dashboard routes
 */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::name('app.')->group(function () {
        Route::resource('leads', LeadController::class);
        Route::resource('breaches', BreachController::class);
        Route::resource('products', ProductController::class);
        Route::resource('companies', CompanyController::class);
        Route::resource('activities', ActivityController::class);
    });
});
