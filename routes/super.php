<?php

declare(strict_types=1);

use App\Http\Controllers\Administrator\CompanyController;
use App\Http\Controllers\Administrator\DashboardController;
use App\Http\Controllers\Administrator\LogController;
use App\Http\Controllers\Administrator\PlanController;
use App\Http\Controllers\Administrator\ProductController;
use App\Http\Controllers\Administrator\SettingController;
use App\Http\Controllers\Administrator\UserController;
use Illuminate\Support\Facades\Route;

/**
 * Here's you can defined all administrator routes
 */
Route::get('/', DashboardController::class)->name('home');
Route::get('/dashobard', DashboardController::class)->name('dashobard');

Route::resource('logs', LogController::class);
Route::resource('users', UserController::class);
Route::resource('plans', PlanController::class);
Route::resource('companies', CompanyController::class);
Route::resource('products', ProductController::class);
Route::resource('settings', SettingController::class);
