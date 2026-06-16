<?php

declare(strict_types=1);

/**
 * Here's you can defined admin routes
 */

use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('home');
Route::get('/dashboard', DashboardController::class)->name('dashboard');

Route::resource('companies', CompanyController::class);
Route::resource('services', ServiceController::class)->only(['index']);
Route::resource('billing', BillingController::class)->only(['index', 'create', 'show', 'edit']);
Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'show', 'edit']);
Route::resource('invites', InviteController::class);
Route::resource('reports', ReportController::class)->only(['index']);

Route::resource('users', UserController::class);
Route::resource('plans', PlanController::class)->only(['index', 'create', 'show', 'edit']);
Route::resource('settings', SettingController::class)->only(['index', 'create', 'show', 'edit']);
Route::resource('logs', LogController::class)->only(['index']);
