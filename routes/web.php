<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

Route::middleware('auth')->group(function () {
    Route::name('super.')->middleware('super')->prefix('super')->group(base_path('routes/super.php'));
    Route::name('admin.')->middleware('admin')->prefix('admin')->group(base_path('routes/admin.php'));
    Route::name('customer')->middleware('customer')->group(base_path('routes/customer.php'));
    Route::name('user.')->group(base_path('routes/user.php'));
});
