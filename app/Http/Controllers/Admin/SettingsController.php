<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller {
    public function index() {
        $shop = auth()->user()->shop;
        return view('admin.settings.index', compact('shop'));
    }

    public function update(Request $request) {
        $shop = auth()->user()->shop;
        $v = $request->validate([
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'city'           => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:100',
        ]);
        $shop->update($v);
        return back()->with('success', 'تم حفظ الإعدادات.');
    }
}
