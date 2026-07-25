<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller {
    private function shopId() { return auth()->user()->shop_id; }

    public function index() {
        $customers = Customer::where('shop_id', $this->shopId())->latest()->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }

    public function create() { return view('admin.customers.create'); }

    public function store(Request $request) {
        $v = $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'id_number'   => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:100',
            'notes'       => 'nullable|string',
        ]);
        Customer::create(array_merge($v, ['shop_id' => $this->shopId()]));
        return redirect()->route('admin.customers.index')->with('success', 'تم إضافة العميل.');
    }

    public function edit(Customer $customer) {
        abort_if($customer->shop_id !== $this->shopId(), 403);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer) {
        abort_if($customer->shop_id !== $this->shopId(), 403);
        $v = $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:20',
            'id_number'   => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:100',
            'notes'       => 'nullable|string',
        ]);
        $customer->update($v);
        return redirect()->route('admin.customers.index')->with('success', 'تم تحديث بيانات العميل.');
    }

    public function destroy(Customer $customer) {
        abort_if($customer->shop_id !== $this->shopId(), 403);
        $customer->delete();
        return back()->with('success', 'تم حذف العميل.');
    }
}
