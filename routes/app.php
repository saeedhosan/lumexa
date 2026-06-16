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
    Route::resource('leads', LeadController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('campaigns', CampaignController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('services', ServiceController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('companies', CompanyController::class)->only(['index', 'create', 'show', 'edit']);
    Route::resource('activities', ActivityController::class)->only(['index', 'create', 'show', 'edit']);
});
