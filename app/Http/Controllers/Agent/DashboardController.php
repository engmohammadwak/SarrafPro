<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    // ================================================================
    // أدوات مساعدة
    // ================================================================

    private function agentRecords()
    {
        return Agent::with('shop')
            ->where('user_id', auth()->id())
            ->get();
    }

    private function ownAgent(int $id): Agent
    {
        $agent = Agent::with('shop')->findOrFail($id);
        abort_if($agent->user_id !== auth()->id(), 403);
        return $agent;
    }

    /**
     * إرسال إشعار لأي مستخدم
     */
    private function notify(User $user, string $type, string $title, string $message, string $url = ''): void
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
                'url'     => $url ?: route('admin.agents.index'),
            ]),
            'read_at'    => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * اجلب مستخدم الأدمن الخاص بالمحل
     */
    private function shopAdmin(Agent $agent): ?User
    {
        // صاحب المحل = User الذي لديه shop_id مطابق و role = admin
        return User::where('shop_id', $agent->shop_id)
                   ->where('role', 'admin')
                   ->first();
    }

    // ================================================================
    // DASHBOARD
    // ================================================================
    public function index()
    {
        $agents        = $this->agentRecords();
        $approvedCount = $agents->where('link_status', 'approved')->count();
        $pendingCount  = $agents->where('link_status', 'pending')->count();
        $rejectedCount = $agents->where('link_status', 'rejected')->count();
        $pendingAgents = $agents->where('link_status', 'pending');

        return view('agent.dashboard', compact(
            'agents', 'approvedCount', 'pendingCount', 'rejectedCount', 'pendingAgents'
        ));
    }

    // ================================================================
    // SHOPS
    // ================================================================
    public function shops()
    {
        $agents = Agent::with('shop')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('agent.shops.index', compact('agents'));
    }

    public function shopShow(int $id)
    {
        $agent = $this->ownAgent($id);
        return view('agent.shops.show', compact('agent'));
    }

    public function shopBlock(int $id)
    {
        $agent = $this->ownAgent($id);
        abort_if($agent->link_status !== 'approved', 403);
        $agent->update(['is_active' => false]);

        // إشعار الأدمن
        if ($admin = $this->shopAdmin($agent)) {
            $this->notify(
                $admin,
                'warning',
                'المندوب أوقف نشاطه',
                'المندوب "' . auth()->user()->name . '" قام بتوقيف نشاطه في محلك.',
                route('admin.agents.index')
            );
        }

        return back()->with('success', 'تم توقيف المحل بنجاح.');
    }

    public function shopUnblock(int $id)
    {
        $agent = $this->ownAgent($id);
        abort_if($agent->link_status !== 'approved', 403);
        $agent->update(['is_active' => true]);

        // إشعار الأدمن
        if ($admin = $this->shopAdmin($agent)) {
            $this->notify(
                $admin,
                'success',
                'المندوب فعّل نشاطه',
                'المندوب "' . auth()->user()->name . '" قام بتفعيل نشاطه في محلك.',
                route('admin.agents.index')
            );
        }

        return back()->with('success', 'تم تفعيل المحل بنجاح.');
    }

    /**
     * طلب فك الارتباط:
     * - لو الرصيد != 0 → link_status = unlink_pending (يبقى السجل كاملاً)
     * - لو الرصيد = 0  → فك فوري
     */
    public function shopUnlink(int $id)
    {
        $agent = $this->ownAgent($id);
        abort_if(!in_array($agent->link_status, ['approved', 'unlink_pending']), 403);

        $agentName = auth()->user()->name;
        $shopName  = $agent->shop->name ?? '';

        if (!empty($agent->balance) && $agent->balance != 0) {
            $agent->update(['link_status' => 'unlink_pending']);

            // إشعار الأدمن
            if ($admin = $this->shopAdmin($agent)) {
                $this->notify(
                    $admin,
                    'warning',
                    'طلب فك ارتباط (رصيد معلق)',
                    'المندوب "' . $agentName . '" طلب فك الارتباط بمحلك ولديه رصيد معلق بقيمة ' . number_format($agent->balance, 2) . '. يرجى تسوية الرصيد أولاً.',
                    route('admin.agents.index')
                );
            }

            return back()->with('success', 'تم تقديم طلب فك الارتباط. سيبقى السجل محفوظاً حتى تسوية الرصيد (' . number_format($agent->balance, 2) . ').');
        }

        // رصيد صفر → فك فوري
        $agent->update(['link_status' => 'rejected', 'user_id' => null]);

        // إشعار الأدمن
        if ($admin = $this->shopAdmin($agent)) {
            $this->notify(
                $admin,
                'danger',
                'فك ارتباط مندوب',
                'المندوب "' . $agentName . '" قام بفك ارتباطه بمحلك نهائياً.',
                route('admin.agents.index')
            );
        }

        return redirect()->route('agent.shops.index')->with('success', 'تم فك الارتباط بنجاح.');
    }

    // ================================================================
    // LINK REQUESTS (موافقة / رفض طلب الأدمن)
    // ================================================================
    public function approveLink(Agent $agent)
    {
        abort_if($agent->user_id !== auth()->id(), 403);
        $agent->update(['link_status' => 'approved']);

        // إشعار الأدمن
        if ($admin = $this->shopAdmin($agent)) {
            $this->notify(
                $admin,
                'success',
                'وافق المندوب على الارتباط',
                'المندوب "' . auth()->user()->name . '" وافق على طلب الربط بمحلك. أصبح نشطاً الآن.',
                route('admin.agents.index')
            );
        }

        return back()->with('success', 'لقد وافقت على الانضمام إلى محل "' . ($agent->shop->name ?? '') . '".');
    }

    public function rejectLink(Agent $agent)
    {
        abort_if($agent->user_id !== auth()->id(), 403);

        $agentName = auth()->user()->name;

        // إشعار الأدمن قبل إلغاء الربط
        if ($admin = $this->shopAdmin($agent)) {
            $this->notify(
                $admin,
                'danger',
                'رفض المندوب طلب الربط',
                'المندوب "' . $agentName . '" رفض طلب الربط بمحلك.',
                route('admin.agents.index')
            );
        }

        $agent->update(['link_status' => 'rejected', 'user_id' => null]);
        return back()->with('success', 'تم رفض طلب الربط.');
    }

    // ================================================================
    // MISC
    // ================================================================
    public function transactions()
    {
        return view('agent.transactions');
    }

    public function notifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('agent.notifications', compact('notifications'));
    }

    public function markOneRead(string $id)
    {
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', auth()->id())
            ->where('notifiable_type', 'App\\Models\\User')
            ->first();

        $redirectUrl = route('agent.dashboard');
        if ($notification) {
            $data = is_array($notification->data)
                ? $notification->data
                : json_decode($notification->data, true);
            $redirectUrl = $data['url'] ?? $redirectUrl;
            if (!$notification->read_at) $notification->markAsRead();
        }
        return redirect($redirectUrl);
    }

    public function reports()
    {
        return view('agent.reports');
    }

    public function markAllRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        if ($request->wantsJson()) return response()->json(['success' => true]);
        return redirect()->route('agent.notifications');
    }
}
