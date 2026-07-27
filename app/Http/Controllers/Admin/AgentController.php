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

    // أدوار تعني شراكة ليس مندوباً
    private const PARTNER_ROLES = ['shop_admin', 'super_admin', 'admin'];

    private function resolveType(User $user): string {
        return in_array($user->role, self::PARTNER_ROLES) ? 'partner' : 'agent';
    }

    private function sendNotification(User $user, string $type, string $title, string $message, string $url = ''): void
    {
        if (!$url) {
            $url = match($user->role) {
                'super_admin'         => route('superadmin.dashboard'),
                'agent','cooperation','partner' => route('agent.dashboard'),
                default               => route('admin.agents.index'),
            };
        }
        DatabaseNotification::create([
            'id'              => (string) Str::uuid(),
            'type'            => 'App\\Notifications\\AgentLinkNotification',
            'notifiable_type' => User::class,
            'notifiable_id'   => $user->id,
            'data'            => json_encode([
                'type'    => $type,
                'title'   => $title,
                'message' => $message,
                'url'     => $url,
            ]),
            'read_at'    => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function index() {
        $agents = Agent::where('shop_id', $this->shopId())->with('user')->latest()->paginate(20);
        return view('admin.agents.index', compact('agents'));
    }

    public function create() { return view('admin.agents.create'); }

    /** فحص المستخدم - يقبل أي دور (النوع يتحدد تلقائياً) */
    public function checkUser(Request $request) {
        $request->validate(['username' => 'required|string']);
        $search = trim($request->username);

        $user = User::where(function($q) use ($search) {
            $q->where('username', $search)
              ->orWhere('email',   $search)
              ->orWhere('name',    $search);
        })->first();

        if (!$user)
            return response()->json(['found' => false, 'message' => 'لا يوجد حساب بهذا الاسم']);

        if ($user->status !== 'active')
            return response()->json(['found' => false, 'message' => 'هذا الحساب موقوف']);

        return response()->json([
            'found'    => true,
            'user_id'  => $user->id,
            'role'     => $user->role,         // الدور يرجع للواجهة
            'name'     => $user->name,
            'username' => $user->username ?? $user->name,
            'email'    => $user->email,
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

        $attachmentPath = null;
        if ($request->hasFile('attachment'))
            $attachmentPath = $request->file('attachment')->store('agents/attachments', 'public');

        $userId = null; $linkStatus = 'none'; $agentType = 'agent';

        if ($request->link_type === 'existing' && $request->user_id) {
            $linkedUser = User::findOrFail($request->user_id);
            if ($linkedUser->status !== 'active')
                return back()->withErrors(['user_id' => 'هذا الحساب موقوف.'])->withInput();

            $agentType  = $this->resolveType($linkedUser); // تحديد تلقائي
            $userId     = $linkedUser->id;
            $linkStatus = 'pending';

        } elseif ($request->link_type === 'create' && $request->new_email && $request->new_password) {
            $newUser = User::create([
                'name'     => $request->name,
                'email'    => $request->new_email,
                'password' => bcrypt($request->new_password),
                'role'     => 'agent',
                'status'   => 'active',
            ]);
            $userId     = $newUser->id;
            $linkStatus = 'approved';
            $agentType  = 'agent';
        }

        Agent::create([
            'shop_id'     => $this->shopId(),
            'user_id'     => $userId,
            'link_status' => $linkStatus,
            'type'        => $agentType,
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
            if ($targetUser) {
                $typeLabel = $agentType === 'partner' ? 'شريكاً' : 'مندوباً';
                $this->sendNotification($targetUser, 'warning', 'طلب ربط جديد',
                    'محل "' . $this->shopName() . '" يطلب ربطك ' . $typeLabel . '. بانتظار موافقتك.');
            }
        }

        return redirect()->route('admin.agents.index')->with('success', 'تم الإضافة بنجاح.');
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
            if ($targetUser->status !== 'active')
                return back()->withErrors(['assign_user_id' => 'هذا الحساب موقوف.'])->withInput();

            $resolvedType = $this->resolveType($targetUser);
            $v['user_id']     = $targetUser->id;
            $v['link_status'] = 'pending';
            $v['type']        = $resolvedType;

            $typeLabel = $resolvedType === 'partner' ? 'شريكاً' : 'مندوباً';
            $this->sendNotification($targetUser, 'warning', 'طلب ربط جديد',
                'محل "' . $this->shopName() . '" يطلب ربطك ' . $typeLabel . '. بانتظار موافقتك.');

        } elseif (!$agent->user_id && $linkType === 'create') {
            $newEmail    = $request->input('new_email');
            $newPassword = $request->input('new_password');
            if (!$newEmail || !$newPassword)
                return back()->withErrors(['new_email' => 'يرجى إدخال الإيميل وكلمة المرور.'])->withInput();
            $newUser = User::create([
                'name'     => $agent->name,
                'email'    => $newEmail,
                'password' => bcrypt($newPassword),
                'role'     => 'agent',
                'status'   => 'active',
            ]);
            $v['user_id']     = $newUser->id;
            $v['link_status'] = 'approved';
            $v['type']        = 'agent';
        }

        unset($v['link_type'], $v['assign_user_id'], $v['new_email'], $v['new_password']);
        $agent->update($v);

        return redirect()->route('admin.agents.index')->with('success', 'تم التحديث.');
    }

    public function approveLink(Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        $agent->update(['link_status' => 'approved']);
        $targetUser = User::find($agent->user_id);
        if ($targetUser) {
            $typeLabel = $agent->type === 'partner' ? 'شريكاً' : 'مندوباً';
            $this->sendNotification($targetUser, 'success', 'تمت موافقتك',
                'وافق محل "' . $this->shopName() . '" على طلب الربط. أنت الآن ' . $typeLabel . ' لديهم!');
        }
        return back()->with('success', 'تم قبول طلب الربط.');
    }

    public function rejectLink(Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        $targetUser = User::find($agent->user_id);
        if ($targetUser)
            $this->sendNotification($targetUser, 'danger', 'تم رفض طلب الربط',
                'للأسف، تم رفض طلب ربطك مع محل "' . $this->shopName() . '".');
        $agent->update(['link_status' => 'rejected', 'user_id' => null]);
        return back()->with('success', 'تم رفض طلب الربط.');
    }

    public function destroy(Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        $agent->delete();
        return back()->with('success', 'تم الحذف.');
    }
}
