<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with('admin')->latest()->paginate(20);
        return view('superadmin.shops.index', compact('shops'));
    }

    public function show(Shop $shop)
    {
        $shop->load('admin');
        return view('superadmin.shops.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        return view('superadmin.shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:active,suspended,pending',
        ]);

        $shop->update($validated);

        return redirect()->route('superadmin.shops.index')
            ->with('success', 'Shop updated successfully.');
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();

        return redirect()->route('superadmin.shops.index')
            ->with('success', 'Shop deleted successfully.');
    }

    public function suspend(Shop $shop)
    {
        $shop->update(['status' => 'suspended']);

        return back()->with('success', 'Shop suspended successfully.');
    }

    public function activate(Shop $shop)
    {
        $shop->update(['status' => 'active']);

        return back()->with('success', 'Shop activated successfully.');
    }
}
