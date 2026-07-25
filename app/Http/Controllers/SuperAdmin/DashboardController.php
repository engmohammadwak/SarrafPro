<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalShops    = Shop::count();
        $activeShops   = Shop::where('is_active', true)->count();
        $inactiveShops = Shop::where('is_active', false)->count();
        $totalUsers    = User::where('role', '!=', 'super_admin')->count();
        $latestShops   = Shop::with('admin')->latest()->take(10)->get();

        return view('superadmin.dashboard', compact(
            'totalShops', 'activeShops', 'inactiveShops', 'totalUsers', 'latestShops'
        ));
    }
}
