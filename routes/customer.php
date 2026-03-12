<?php

declare(strict_types=1);

use App\Http\Controllers\Customer\ActivityController;
use App\Http\Controllers\Customer\BreachController;
use App\Http\Controllers\Customer\CompanyController;
use App\Http\Controllers\Customer\LeadController;
use App\Http\Controllers\Customer\ProductController;
use Illuminate\Support\Facades\Route;

/**
 * Here's defined customer routes
 */
Route::resource('leads', LeadController::class);
Route::resource('breaches', BreachController::class);
Route::resource('products', ProductController::class);
Route::resource('companies', CompanyController::class);
Route::resource('activities', ActivityController::class);
