<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ShopController;
use Illuminate\Support\Facades\Route;

// Auth Routes (no middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'super_admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Shops Management
    Route::resource('shops', ShopController::class);
    Route::post('shops/{shop}/toggle-status', [ShopController::class, 'toggleStatus'])->name('shops.toggle-status');
});
