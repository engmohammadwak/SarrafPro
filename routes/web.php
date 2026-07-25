<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\AuthController as SuperAdminAuth;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\ShopController as SuperAdminShop;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUser;
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;

// =====================
// Super Admin Routes
// =====================
Route::prefix('super-admin')->name('superadmin.')->group(function () {

    // Auth (no middleware)
    Route::get('login',  [SuperAdminAuth::class, 'showLogin'])->name('login');
    Route::post('login', [SuperAdminAuth::class, 'login'])->name('login.post');
    Route::post('logout',[SuperAdminAuth::class, 'logout'])->name('logout');

    // Protected
    Route::middleware('super_admin')->group(function () {
        Route::get('dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');

        // Shops
        Route::resource('shops', SuperAdminShop::class)->names('superadmin.shops');
        Route::patch('shops/{shop}/suspend',  [SuperAdminShop::class, 'suspend'])->name('shops.suspend');
        Route::patch('shops/{shop}/activate', [SuperAdminShop::class, 'activate'])->name('shops.activate');

        // Users
        Route::resource('users', SuperAdminUser::class)->names('superadmin.users');
    });
});

// =====================
// Admin (Shop) Routes
// =====================
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (no middleware)
    Route::get('login',  [AdminAuth::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuth::class, 'login'])->name('login.post');
    Route::post('logout',[AdminAuth::class, 'logout'])->name('logout');

    // Protected
    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    });
});
