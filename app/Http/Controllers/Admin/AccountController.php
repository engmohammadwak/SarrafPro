<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller {
    private function shopId() { return auth()->user()->shop_id; }

    public function index() {
        $accounts = Account::where('shop_id', $this->shopId())->latest()->get();
        return view('admin.accounts.index', compact('accounts'));
    }

    public function create() { return view('admin.accounts.create'); }

    public function store(Request $request) {
        $v = $request->validate([
            'name'     => 'required|string|max:255',
            'type'     => 'required|in:cash,bank,safe',
            'currency' => 'required|string|max:10',
            'balance'  => 'nullable|numeric',
            'notes'    => 'nullable|string',
        ]);
        Account::create(array_merge($v, ['shop_id' => $this->shopId()]));
        return redirect()->route('admin.accounts.index')->with('success', 'تم إضافة الحساب.');
    }

    public function edit(Account $account) {
        abort_if($account->shop_id !== $this->shopId(), 403);
        return view('admin.accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account) {
        abort_if($account->shop_id !== $this->shopId(), 403);
        $v = $request->validate([
            'name'     => 'required|string|max:255',
            'type'     => 'required|in:cash,bank,safe',
            'currency' => 'required|string|max:10',
            'notes'    => 'nullable|string',
        ]);
        $account->update($v);
        return redirect()->route('admin.accounts.index')->with('success', 'تم تحديث الحساب.');
    }

    public function destroy(Account $account) {
        abort_if($account->shop_id !== $this->shopId(), 403);
        $account->delete();
        return back()->with('success', 'تم حذف الحساب.');
    }
}
