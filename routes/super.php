<?php

declare(strict_types=1);

use App\Http\Controllers\Super\CompanyController;
use App\Http\Controllers\Super\DashboardController;
use App\Http\Controllers\Super\LogController;
use App\Http\Controllers\Super\PlanController;
use App\Http\Controllers\Super\ServiceController;
use App\Http\Controllers\Super\SettingController;
use App\Http\Controllers\Super\UserController;
use Illuminate\Support\Facades\Route;

/**
 * Here's you can defined all super/administrator routes
 */
Route::get('/', DashboardController::class)->name('home');
Route::get('/dashobard', DashboardController::class)->name('dashobard');

Route::resource('logs', LogController::class);
Route::resource('users', UserController::class);
Route::resource('plans', PlanController::class);
Route::resource('companies', CompanyController::class);
Route::resource('services', ServiceController::class);
Route::resource('settings', SettingController::class);
