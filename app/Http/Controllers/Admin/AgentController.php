<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;

class AgentController extends Controller {
    private function shopId() { return auth()->user()->shop_id; }

    public function index() {
        $agents = Agent::where('shop_id', $this->shopId())->with('user')->latest()->paginate(20);
        return view('admin.agents.index', compact('agents'));
    }

    public function create() { return view('admin.agents.create'); }

    // AJAX: التحقق من وجود المستخدم
    public function checkUser(Request $request) {
        $request->validate(['username' => 'required|string']);
        $user = User::where('email', $request->username)
                    ->orWhere('name', $request->username)
                    ->first();
        if (!$user) {
            return response()->json(['found' => false, 'message' => 'لا يوجد حساب بهذا الاسم']);
        }
        return response()->json([
            'found'   => true,
            'user_id' => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
        ]);
    }

    public function store(Request $request) {
        $v = $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|string|max:30',
            'country'     => 'nullable|string|max:100',
            'company'     => 'nullable|string|max:255',
            'notes'       => 'nullable|string',
            'link_type'   => 'required|in:none,existing,create',
            'user_id'     => 'nullable|exists:users,id',
            'new_email'   => 'nullable|email|unique:users,email',
            'new_password'=> 'nullable|min:8',
        ]);

        $userId     = null;
        $linkStatus = 'none';

        if ($request->link_type === 'existing' && $request->user_id) {
            $userId     = $request->user_id;
            $linkStatus = 'pending';
        } elseif ($request->link_type === 'create' && $request->new_email && $request->new_password) {
            $newUser = User::create([
                'name'     => $request->name,
                'email'    => $request->new_email,
                'password' => bcrypt($request->new_password),
                'role'     => 'agent',
            ]);
            $userId     = $newUser->id;
            $linkStatus = 'approved';
        }

        Agent::create([
            'shop_id'     => $this->shopId(),
            'user_id'     => $userId,
            'link_status' => $linkStatus,
            'name'        => $v['name'],
            'phone'       => $v['phone'] ?? null,
            'country'     => $v['country'] ?? null,
            'company'     => $v['company'] ?? null,
            'notes'       => $v['notes'] ?? null,
            'balance'     => 0,
            'is_active'   => true,
        ]);

        return redirect()->route('admin.agents.index')->with('success', 'تم إضافة المندوب.');
    }

    public function approveLink(Agent $agent) {
        abort_if($agent->user_id !== auth()->id(), 403);
        $agent->update(['link_status' => 'approved']);
        return back()->with('success', 'تم قبول طلب الربط.');
    }

    public function rejectLink(Agent $agent) {
        abort_if($agent->user_id !== auth()->id(), 403);
        $agent->update(['link_status' => 'rejected', 'user_id' => null]);
        return back()->with('success', 'تم رفض طلب الربط.');
    }

    public function edit(Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        $v = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:30',
            'country' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:255',
            'notes'   => 'nullable|string',
        ]);
        $agent->update($v);
        return redirect()->route('admin.agents.index')->with('success', 'تم تحديث المندوب.');
    }

    public function destroy(Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        $agent->delete();
        return back()->with('success', 'تم حذف المندوب.');
    }
}
