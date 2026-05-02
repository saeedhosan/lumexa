<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

require __DIR__.'/settings.php';

Route::middleware(['auth', 'verified'])->group(base_path('routes/app.php'));

Route::middleware('auth')->group(function (): void {
    Route::name('admin.')->middleware('admin')->prefix('admin')->group(base_path('routes/admin.php'));
});
