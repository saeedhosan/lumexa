<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

require __DIR__.'/settings.php';
require __DIR__.'/dashboard.php';

Route::middleware('auth')->group(function (): void {
    Route::name('admin.')->middleware('admin')->prefix('admin')->group(base_path('routes/admin.php'));
});
