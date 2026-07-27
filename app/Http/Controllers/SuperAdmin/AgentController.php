<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Agent;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AgentController extends Controller {

    public function index() {
        $agents = User::with('creator')->where('role','agent')->latest()->paginate(20);
        return view('superadmin.agents.index', compact('agents'));
    }

    public function create() {
        return view('superadmin.agents.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'username'   => 'nullable|string|max:50|alpha_dash|unique:users,username',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'notes'      => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('agents/attachments','public');
        }
        User::create([
            'name'       => $data['name'],
            'username'   => $data['username'] ?? null,
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
            'role'       => 'agent',
            'status'     => 'active',
            'notes'      => $data['notes'] ?? null,
            'attachment' => $attachmentPath,
            'created_by' => auth()->id(),
        ]);
        return redirect()->route('superadmin.agents.index')->with('success', 'تمّ إضافة المندوب بنجاح');
    }

    public function show(User $agent) {
        $agent->load('creator','updater');

        // جميع سجلات المندوب (لكل محل مربوط بيه)
        $agentRecords = Agent::with(['shop', 'user'])
            ->where('user_id', $agent->id)
            ->get();

        // لكل سجل مندوب: جلب حساباته المفعلة مع رصيدها
        $agentIds = $agentRecords->pluck('id');
        $accountsByAgent = Account::whereIn('agent_id', $agentIds)
            ->where('is_active', true)
            ->select('agent_id','currency','balance','name','type')
            ->orderBy('currency')
            ->get()
            ->groupBy('agent_id');

        // أرصدة المندوب الإجمالية (multi-currency) عبر جميع المحلات
        $allBalances = Account::whereIn('agent_id', $agentIds)
            ->where('is_active', true)
            ->select('currency', \Illuminate\Support\Facades\DB::raw('SUM(balance) as total'), 'type')
            ->groupBy('currency','type')
            ->orderBy('currency')
            ->get();

        return view('superadmin.agents.show', compact(
            'agent', 'agentRecords', 'accountsByAgent', 'allBalances'
        ));
    }

    public function edit(User $agent) {
        return view('superadmin.agents.edit', compact('agent'));
    }

    public function update(Request $request, User $agent) {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'username'   => ['nullable','string','max:50','alpha_dash', Rule::unique('users','username')->ignore($agent->id)],
            'email'      => ['required','email', Rule::unique('users','email')->ignore($agent->id)],
            'password'   => 'nullable|min:6',
            'notes'      => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);
        if ($request->hasFile('attachment')) {
            if ($agent->attachment) Storage::disk('public')->delete($agent->attachment);
            $data['attachment'] = $request->file('attachment')->store('agents/attachments','public');
        }
        if (empty($data['password'])) unset($data['password']);
        else $data['password'] = bcrypt($data['password']);
        $data['updated_by'] = auth()->id();
        $agent->update($data);
        return redirect()->route('superadmin.agents.show', $agent)->with('success', 'تمّ تحديث بيانات المندوب');
    }

    public function destroy(User $agent) {
        if ($agent->attachment) Storage::disk('public')->delete($agent->attachment);
        $agent->delete();
        return redirect()->route('superadmin.agents.index')->with('success', 'تمّ حذف المندوب');
    }

    public function suspend(User $agent) {
        $agent->update(['status' => 'suspended', 'updated_by' => auth()->id()]);
        return back()->with('success', 'تمّ تعليق حساب المندوب');
    }

    public function activate(User $agent) {
        $agent->update(['status' => 'active', 'updated_by' => auth()->id()]);
        return back()->with('success', 'تمّ تفعيل حساب المندوب');
    }

    public function deleteAttachment(User $agent) {
        if ($agent->attachment) {
            Storage::disk('public')->delete($agent->attachment);
            $agent->update(['attachment' => null, 'updated_by' => auth()->id()]);
        }
        return back()->with('success', 'تمّ حذف الملف بنجاح');
    }
}
