<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller {

    public function index() {
        $shops = Shop::with('creator')->latest()->paginate(20);
        return view('superadmin.shops.index', compact('shops'));
    }

    public function create() {
        return view('superadmin.shops.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'username'   => 'nullable|string|max:50|alpha_dash|unique:shops,username',
            'owner_name' => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
            'email'      => 'nullable|email|unique:shops,email',
            'address'    => 'nullable|string|max:255',
        ]);
        $data['status']     = 'active';
        $data['created_by'] = auth()->id();
        Shop::create($data);
        return redirect()->route('superadmin.shops.index')->with('success', 'تمّ إضافة المحل بنجاح');
    }

    public function show(Shop $shop) {
        $shop->load('creator');
        return view('superadmin.shops.show', compact('shop'));
    }

    public function edit(Shop $shop) {
        return view('superadmin.shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop) {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'owner_name' => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
            'email'      => 'nullable|email|unique:shops,email,'.$shop->id,
            'address'    => 'nullable|string|max:255',
        ]);
        $shop->update($data);
        return redirect()->route('superadmin.shops.show', $shop)->with('success', 'تمّ تحديث بيانات المحل');
    }

    public function destroy(Shop $shop) {
        $shop->delete();
        return redirect()->route('superadmin.shops.index')->with('success', 'تمّ حذف المحل');
    }

    public function suspend(Shop $shop) {
        $shop->update(['status' => 'suspended']);
        return back()->with('success', 'تمّ تعليق المحل');
    }

    public function activate(Shop $shop) {
        $shop->update(['status' => 'active']);
        return back()->with('success', 'تمّ تفعيل المحل');
    }
}
