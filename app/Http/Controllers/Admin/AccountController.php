<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller {
    private function shopId() { return auth()->user()->shop_id; }

    public function index() {
        $accounts = Account::where('shop_id', $this->shopId())->with('agent')->latest()->get();
        return view('admin.accounts.index', compact('accounts'));
    }

    public function create() {
        $agents = Agent::where('shop_id', $this->shopId())->where('is_active', true)->get();
        return view('admin.accounts.create', compact('agents'));
    }

    public function store(Request $request) {
        $v = $request->validate([
            'type'           => 'required|in:cash,bank,exchange,crypto',
            'name'           => 'required|string|max:255',
            'agent_id'       => 'nullable|exists:agents,id',
            'country'        => 'nullable|string|max:100',
            'currency'       => 'required|string|max:10',
            'account_number' => 'nullable|string|max:100',
            'crypto_address' => 'nullable|string|max:255',
            'crypto_network' => 'nullable|string|max:100',
            'balance'        => 'nullable|numeric',
            'notes'          => 'nullable|string',
            'attachment'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('accounts', 'public');
        }

        Account::create(array_merge($v, [
            'shop_id'    => $this->shopId(),
            'attachment' => $path,
            'balance'    => $v['balance'] ?? 0,
        ]));

        return redirect()->route('admin.accounts.index')->with('success', 'تم إضافة الحساب.');
    }

    public function edit(Account $account) {
        abort_if($account->shop_id !== $this->shopId(), 403);
        $agents = Agent::where('shop_id', $this->shopId())->where('is_active', true)->get();
        return view('admin.accounts.edit', compact('account', 'agents'));
    }

    public function update(Request $request, Account $account) {
        abort_if($account->shop_id !== $this->shopId(), 403);
        $v = $request->validate([
            'type'           => 'required|in:cash,bank,exchange,crypto',
            'name'           => 'required|string|max:255',
            'agent_id'       => 'nullable|exists:agents,id',
            'country'        => 'nullable|string|max:100',
            'currency'       => 'required|string|max:10',
            'account_number' => 'nullable|string|max:100',
            'crypto_address' => 'nullable|string|max:255',
            'crypto_network' => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
            'attachment'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            if ($account->attachment) Storage::disk('public')->delete($account->attachment);
            $v['attachment'] = $request->file('attachment')->store('accounts', 'public');
        }

        $account->update($v);
        return redirect()->route('admin.accounts.index')->with('success', 'تم تحديث الحساب.');
    }

    public function destroy(Account $account) {
        abort_if($account->shop_id !== $this->shopId(), 403);
        if ($account->attachment) Storage::disk('public')->delete($account->attachment);
        $account->delete();
        return back()->with('success', 'تم حذف الحساب.');
    }
}
