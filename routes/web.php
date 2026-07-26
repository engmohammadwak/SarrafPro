<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\AuthController as SuperAdminAuth;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\ShopController as SuperAdminShop;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUser;
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\SettingsController;

// Root
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'super_admin') return redirect()->route('superadmin.dashboard');
        if ($role === 'shop_admin')  return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login');
});

// =====================
// Super Admin Routes
// =====================
Route::prefix('super-admin')->name('superadmin.')->group(function () {
    Route::get('login',  [SuperAdminAuth::class, 'showLogin'])->name('login');
    Route::post('login', [SuperAdminAuth::class, 'login'])->name('login.post');
    Route::post('logout',[SuperAdminAuth::class, 'logout'])->name('logout');

    Route::middleware('super_admin')->group(function () {
        Route::get('dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
        Route::resource('shops', SuperAdminShop::class);
        Route::patch('shops/{shop}/suspend',  [SuperAdminShop::class, 'suspend'])->name('shops.suspend');
        Route::patch('shops/{shop}/activate', [SuperAdminShop::class, 'activate'])->name('shops.activate');
        Route::resource('users', SuperAdminUser::class);
    });
});

// =====================
// Admin (Shop) Routes
// =====================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login',  [AdminAuth::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuth::class, 'login'])->name('login.post');
    Route::post('logout',[AdminAuth::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::resource('staff', StaffController::class);
        Route::resource('customers', CustomerController::class);
        Route::resource('accounts', AccountController::class);

        // Agents
        Route::resource('agents', AgentController::class);
        Route::get('agents/check-user',          [AgentController::class, 'checkUser'])->name('agents.check-user');
        Route::patch('agents/{agent}/approve-link', [AgentController::class, 'approveLink'])->name('agents.approve-link');
        Route::patch('agents/{agent}/reject-link',  [AgentController::class, 'rejectLink'])->name('agents.reject-link');

        Route::get('transactions',               [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/create',        [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions',              [TransactionController::class, 'store'])->name('transactions.store');
        Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});
