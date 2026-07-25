<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Agent;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller {
    private function shopId() { return auth()->user()->shop_id; }

    public function index() {
        $transactions = Transaction::with(['performer','customer','account'])
            ->where('shop_id', $this->shopId())
            ->latest()->paginate(30);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function create() {
        $customers = Customer::where('shop_id', $this->shopId())->where('is_active',true)->get();
        $agents    = Agent::where('shop_id', $this->shopId())->where('is_active',true)->get();
        $accounts  = Account::where('shop_id', $this->shopId())->where('is_active',true)->get();
        return view('admin.transactions.create', compact('customers','agents','accounts'));
    }

    public function store(Request $request) {
        $v = $request->validate([
            'type'          => 'required|in:buy,sell,transfer,deposit,withdraw',
            'currency_from' => 'nullable|string|max:10',
            'currency_to'   => 'nullable|string|max:10',
            'amount'        => 'required|numeric|min:0.0001',
            'rate'          => 'nullable|numeric',
            'fee'           => 'nullable|numeric',
            'customer_id'   => 'nullable|exists:customers,id',
            'agent_id'      => 'nullable|exists:agents,id',
            'account_id'    => 'nullable|exists:accounts,id',
            'notes'         => 'nullable|string',
        ]);

        $amountResult = null;
        if (!empty($v['rate']) && !empty($v['amount'])) {
            $amountResult = $v['type'] === 'buy'
                ? $v['amount'] * $v['rate']
                : $v['amount'] / $v['rate'];
        }

        Transaction::create(array_merge($v, [
            'shop_id'       => $this->shopId(),
            'performed_by'  => auth()->id(),
            'amount_result' => $amountResult,
            'fee'           => $v['fee'] ?? 0,
            'reference'     => strtoupper(Str::random(10)),
            'status'        => 'completed',
        ]));

        return redirect()->route('admin.transactions.index')->with('success', 'تم تسجيل العملية بنجاح.');
    }

    public function show(Transaction $transaction) {
        abort_if($transaction->shop_id !== $this->shopId(), 403);
        $transaction->load(['performer','customer','agent','account']);
        return view('admin.transactions.show', compact('transaction'));
    }
}
