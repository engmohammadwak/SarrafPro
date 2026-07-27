<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class AgentController extends Controller {
    private function shopId()   { return auth()->user()->shop_id; }
    private function shopName() { return auth()->user()->shop->name ?? 'المحل'; }

    private function sendNotification(User $user, string $type, string $title, string $message, string $url = ''): void
    {
        DatabaseNotification::create([
            'id'              => (string) Str::uuid(),
            'type'            => 'App\\Notifications\\AgentLinkNotification',
            'notifiable_type' => User::class,
            'notifiable_id'   => $user->id,
            'data'            => json_encode([
                'type'    => $type,
                'title'   => $title,
                'message' => $message,
                'url'     => $url ?: route('agent.dashboard'),
            ]),
            'read_at'    => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /** التحقق أن المستخدم مؤهل للربط كمندوب */
    private function validateAgentUser(User $user): ?string
    {
        if ($user->status !== 'active')
            return 'هذا الحساب موقوف، لا يمكن الربط به';

        if (!in_array($user->role, ['agent', 'cooperation']))
            return 'لا يمكن ربط هذا الحساب كمندوب لأنه (' . $user->role . '). يجب أن يكون دور الحساب مندوباً';

        return null;
    }

    public function index() {
        $agents = Agent::where('shop_id', $this->shopId())->with('user')->latest()->paginate(20);
        return view('admin.agents.index', compact('agents'));
    }

    public function create() { return view('admin.agents.create'); }

    public function checkUser(Request $request) {
        $request->validate(['username' => 'required|string']);
        $search = trim($request->username);
        $user   = User::where(function($q) use ($search) {
                        $q->where('username', $search)
                          ->orWhere('email',    $search)
                          ->orWhere('name',     $search);
                    })->first();

        if (!$user)
            return response()->json(['found' => false, 'message' => 'لا يوجد حساب بهذا الاسم']);

        $error = $this->validateAgentUser($user);
        if ($error)
            return response()->json(['found' => false, 'message' => $error]);

        $agent = Agent::where('user_id', $user->id)->first();
        return response()->json([
            'found'    => true,
            'user_id'  => $user->id,
            'name'     => $agent?->name    ?? $user->name,
            'username' => $user->username  ?? $user->name,
            'email'    => $user->email,
            'phone'    => $agent?->phone   ?? $user->phone   ?? null,
            'phone2'   => $agent?->phone2  ?? null,
            'country'  => $agent?->country ?? $user->country ?? null,
            'company'  => $agent?->company ?? $user->company ?? null,
        ]);
    }

    public function store(Request $request) {
        $v = $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'nullable|string|max:30',
            'phone2'       => 'nullable|string|max:30',
            'country'      => 'nullable|string|max:100',
            'company'      => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
            'admin_notes'  => 'nullable|string',
            'attachment'   => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'link_type'    => 'required|in:none,existing,create',
            'user_id'      => 'nullable|exists:users,id',
            'new_email'    => 'nullable|email|unique:users,email',
            'new_password' => 'nullable|min:8',
        ]);

        if ($request->link_type === 'existing' && $request->user_id) {
            $linkedUser = User::find($request->user_id);
            if (!$linkedUser)
                return back()->withErrors(['user_id' => 'الحساب غير موجود.'])->withInput();

            $error = $this->validateAgentUser($linkedUser);
            if ($error)
                return back()->withErrors(['user_id' => $error])->withInput();
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment'))
            $attachmentPath = $request->file('attachment')->store('agents/attachments', 'public');

        $userId = null; $linkStatus = 'none';
        if ($request->link_type === 'existing' && $request->user_id) {
            $userId = $request->user_id; $linkStatus = 'pending';
        } elseif ($request->link_type === 'create' && $request->new_email && $request->new_password) {
            $newUser = User::create([
                'name'     => $request->name,
                'email'    => $request->new_email,
                'password' => bcrypt($request->new_password),
                'role'     => 'agent',
                'status'   => 'active',
            ]);
            $userId = $newUser->id; $linkStatus = 'approved';
        }

        Agent::create([
            'shop_id'     => $this->shopId(),
            'user_id'     => $userId,
            'link_status' => $linkStatus,
            'name'        => $v['name'],
            'phone'       => $v['phone']       ?? null,
            'phone2'      => $v['phone2']      ?? null,
            'country'     => $v['country']     ?? null,
            'company'     => $v['company']     ?? null,
            'notes'       => $v['notes']       ?? null,
            'admin_notes' => $v['admin_notes'] ?? null,
            'attachment'  => $attachmentPath,
            'balance'     => 0,
            'is_active'   => true,
        ]);

        if ($linkStatus === 'pending' && $userId) {
            $targetUser = User::find($userId);
            if ($targetUser)
                $this->sendNotification($targetUser, 'warning', 'طلب ربط جديد',
                    'محل "' . $this->shopName() . '" يطلب ربطك كمندوب. بانتظار موافقتك.',
                    route('agent.dashboard'));
        }

        return redirect()->route('admin.agents.index')->with('success', 'تم إضافة المندوب.');
    }

    public function edit(Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);

        $rules = [
            'name'         => 'required|string|max:255',
            'phone'        => 'nullable|string|max:30',
            'phone2'       => 'nullable|string|max:30',
            'country'      => 'nullable|string|max:100',
            'company'      => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
            'admin_notes'  => 'nullable|string',
            'attachment'   => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ];

        if (!$agent->user_id) {
            $rules['link_type']      = 'nullable|in:none,existing,create';
            $rules['assign_user_id'] = 'nullable|exists:users,id';
            $rules['new_email']      = 'nullable|email|unique:users,email';
            $rules['new_password']   = 'nullable|min:8';
        }

        $v = $request->validate($rules);
        $v['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('attachment')) {
            if ($agent->attachment) Storage::disk('public')->delete($agent->attachment);
            $v['attachment'] = $request->file('attachment')->store('agents/attachments', 'public');
        }
        if ($request->input('delete_attachment') === '1' && $agent->attachment) {
            Storage::disk('public')->delete($agent->attachment);
            $v['attachment'] = null;
        }

        $linkType = $request->input('link_type', 'none');

        if (!$agent->user_id && $linkType === 'existing') {
            $assignId   = $request->input('assign_user_id');
            $targetUser = $assignId ? User::find($assignId) : null;

            if (!$targetUser)
                return back()->withErrors(['assign_user_id' => 'الحساب غير موجود.'])->withInput();

            $error = $this->validateAgentUser($targetUser);
            if ($error)
                return back()->withErrors(['assign_user_id' => $error])->withInput();

            $v['user_id']     = $targetUser->id;
            $v['link_status'] = 'pending';

            $this->sendNotification($targetUser, 'warning', 'طلب ربط جديد',
                'محل "' . $this->shopName() . '" يطلب ربطك كمندوب. بانتظار موافقتك.',
                route('agent.dashboard'));

        } elseif (!$agent->user_id && $linkType === 'create') {
            $newEmail    = $request->input('new_email');
            $newPassword = $request->input('new_password');

            if (!$newEmail || !$newPassword)
                return back()->withErrors(['new_email' => 'يرجى إدخال الإيميل وكلمة المرور لإنشاء حساب جديد.'])->withInput();

            $newUser = User::create([
                'name'     => $agent->name,
                'email'    => $newEmail,
                'password' => bcrypt($newPassword),
                'role'     => 'agent',
                'status'   => 'active',
            ]);

            $v['user_id']     = $newUser->id;
            $v['link_status'] = 'approved';
        }

        unset($v['link_type'], $v['assign_user_id'], $v['new_email'], $v['new_password']);
        $agent->update($v);

        return redirect()->route('admin.agents.index')->with('success', 'تم تحديث بيانات المندوب.');
    }

    public function approveLink(Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        $agent->update(['link_status' => 'approved']);
        $targetUser = User::find($agent->user_id);
        if ($targetUser)
            $this->sendNotification($targetUser, 'success', 'تمت موافقتك',
                'لقد وافقت على طلب الربط مع محل "' . $this->shopName() . '". أهلاً بك!',
                route('agent.dashboard'));
        return back()->with('success', 'تم قبول طلب الربط.');
    }

    public function rejectLink(Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        $targetUser = User::find($agent->user_id);
        if ($targetUser)
            $this->sendNotification($targetUser, 'danger', 'تم رفض طلب الربط',
                'للأسف، تم رفض طلب ربطك مع محل "' . $this->shopName() . '".',
                route('agent.dashboard'));
        $agent->update(['link_status' => 'rejected', 'user_id' => null]);
        return back()->with('success', 'تم رفض طلب الربط.');
    }

    public function destroy(Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        $agent->delete();
        return back()->with('success', 'تم حذف المندوب.');
    }
}
