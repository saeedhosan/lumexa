<?php

declare(strict_types=1);

/**
 * Here's you can defined admin routes
 */

use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CompanyMemberController;
use App\Http\Controllers\Admin\DashobardController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashobardController::class)->name('home');
Route::get('/dashboard', DashobardController::class)->name('dashboard');

Route::resource('companies', CompanyController::class);
Route::get('/companies/{company}/members', [CompanyMemberController::class, 'index'])->name('companies.members.index');
Route::post('/companies/{company}/members', [CompanyMemberController::class, 'store'])->name('companies.members.store');
Route::delete('/companies/{company}/members/{user}', [CompanyMemberController::class, 'destroy'])->name('companies.members.destroy');
Route::resource('services', ServiceController::class);
Route::resource('billing', BillingController::class);
Route::resource('invoices', InvoiceController::class);
Route::resource('invites', InviteController::class);
Route::resource('reports', ReportController::class);

Route::resource('users', UserController::class);
Route::resource('plans', PlanController::class);
Route::resource('settings', SettingController::class);
Route::resource('logs', LogController::class);
