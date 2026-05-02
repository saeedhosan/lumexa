<?php

declare(strict_types=1);

use App\Http\Controllers\App\ActivityController;
use App\Http\Controllers\App\CampaignController;
use App\Http\Controllers\App\CompanyController;
use App\Http\Controllers\App\LeadController;
use App\Http\Controllers\App\ServiceController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/**
 * Here's defined dashboard routes
 */
Route::get('dashboard', DashboardController::class)->name('dashboard');
Route::name('app.')->group(function (): void {
    Route::resource('leads', LeadController::class);
    Route::resource('campaigns', CampaignController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('activities', ActivityController::class);
});
