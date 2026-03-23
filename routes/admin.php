<?php

declare(strict_types=1);

/**
 * Here's you can defined admin routes
 */

use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashobardController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\MemeberController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashobardController::class)->name('home');
Route::get('/dashboard', DashobardController::class)->name('dashboard');

Route::resource('company', CompanyController::class);
Route::resource('memebers', MemeberController::class);
Route::resource('services', ServiceController::class);
Route::resource('billing', BillingController::class);
Route::resource('invoices', InvoiceController::class);
Route::resource('invites', InviteController::class);
Route::resource('reports', ReportController::class);
