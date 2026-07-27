<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    private function agentRecords()
    {
        return Agent::with('shop')->where('user_id', auth()->id())->get();
    }

    private function ownAgent(int $id): Agent
    {
        $agent = Agent::with('shop')->findOrFail($id);
        abort_if($agent->user_id !== auth()->id(), 403);
        return $agent;
    }

    private function notify(User $user, array $data): void
    {
        DatabaseNotification::create([
            'id'              => (string) Str::uuid(),
            'type'            => 'App\\Notifications\\AgentLinkNotification',
            'notifiable_type' => User::class,
            'notifiable_id'   => $user->id,
            'data'            => json_encode($data),
            'read_at'         => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    private function shopAdmin(Agent $agent): ?User
    {
        return User::where('shop_id', $agent->shop_id)
                   ->where('role', 'shop_admin')
                   ->first();
    }

    // ================================================================
    public function index()
    {
        $agents        = $this->agentRecords();
        $approvedCount = $agents->where('link_status', 'approved')->count();
        $pendingCount  = $agents->where('link_status', 'pending')->count();
        $rejectedCount = $agents->where('link_status', 'rejected')->count();
        $pendingAgents = $agents->where('link_status', 'pending');
        return view('agent.dashboard', compact('agents','approvedCount','pendingCount','rejectedCount','pendingAgents'));
    }

    public function shops()
    {
        $agents = Agent::with('shop')->where('user_id', auth()->id())->latest()->get();
        return view('agent.shops.index', compact('agents'));
    }

    public function shopShow(int $id)
    {
        $agent = $this->ownAgent($id);
        return view('agent.shops.show', compact('agent'));
    }

    /** معاملات محل محدد */
    public function shopTransactions(int $id)
    {
        $agent = $this->ownAgent($id);

        $transactions = Transaction::with('customer')
            ->where('shop_id', $agent->shop_id)
            ->where('agent_id', $agent->id)
            ->latest()
            ->paginate(30);

        return view('agent.shops.transactions', compact('agent', 'transactions'));
    }

    public function shopBlock(int $id)
    {
        $agent = $this->ownAgent($id);
        abort_if($agent->link_status !== 'approved', 403);
        $agent->update(['is_active' => false]);

        if ($admin = $this->shopAdmin($agent)) {
            $this->notify($admin, [
                'type'         => 'warning',
                'title'        => '⚠️ المندوب أوقف نشاطه',
                'action_label' => 'توقيف النشاط',
                'agent_name'   => auth()->user()->name,
                'message'      => 'قام المندوب بتوقيف نشاطه لديك، لن تظهر عملياته حتى إعادة التفعيل.',
                'url'          => route('admin.agents.index'),
            ]);
        }
        return back()->with('success', 'تم توقيف المحل بنجاح.');
    }

    public function shopUnblock(int $id)
    {
        $agent = $this->ownAgent($id);
        abort_if($agent->link_status !== 'approved', 403);
        $agent->update(['is_active' => true]);

        if ($admin = $this->shopAdmin($agent)) {
            $this->notify($admin, [
                'type'         => 'success',
                'title'        => '✅ المندوب فعّل نشاطه',
                'action_label' => 'تفعيل النشاط',
                'agent_name'   => auth()->user()->name,
                'message'      => 'عاد المندوب لتفعيل نشاطه لديك، ستظهر عملياته مجدداً من الآن.',
                'url'          => route('admin.agents.index'),
            ]);
        }
        return back()->with('success', 'تم تفعيل المحل بنجاح.');
    }

    public function shopUnlink(int $id)
    {
        $agent = $this->ownAgent($id);
        abort_if(!in_array($agent->link_status, ['approved','unlink_pending']), 403);

        $agentName = auth()->user()->name;

        if (!empty($agent->balance) && $agent->balance != 0) {
            $agent->update(['link_status' => 'unlink_pending']);

            if ($admin = $this->shopAdmin($agent)) {
                $this->notify($admin, [
                    'type'         => 'warning',
                    'title'        => '⏳ طلب فك ارتباط (رصيد معلق)',
                    'action_label' => 'فك ارتباط معلق',
                    'agent_name'   => $agentName,
                    'balance'      => number_format($agent->balance, 2),
                    'message'      => 'طلب المندوب فك الارتباط ولديه رصيد معلق بقيمة ' . number_format($agent->balance, 2) . '. يرجى تسوية الرصيد أولاً.',
                    'url'          => route('admin.agents.index'),
                ]);
            }
            return back()->with('success', 'تم تقديم طلب فك الارتباط. سيبقى السجل محفوظاً حتى تسوية الرصيد (' . number_format($agent->balance, 2) . ').');
        }

        $agent->update(['link_status' => 'rejected', 'user_id' => null]);

        if ($admin = $this->shopAdmin($agent)) {
            $this->notify($admin, [
                'type'         => 'danger',
                'title'        => '❌ فك ارتباط نهائي',
                'action_label' => 'فك الارتباط',
                'agent_name'   => $agentName,
                'message'      => 'قام المندوب بفك ارتباطه بمحلك نهائياً. لم يعد مرتبطاً بحساب أي مستخدم.',
                'url'          => route('admin.agents.index'),
            ]);
        }
        return redirect()->route('agent.shops.index')->with('success', 'تم فك الارتباط بنجاح.');
    }

    public function approveLink(Agent $agent)
    {
        abort_if($agent->user_id !== auth()->id(), 403);
        $agent->update(['link_status' => 'approved']);

        if ($admin = $this->shopAdmin($agent)) {
            $this->notify($admin, [
                'type'         => 'success',
                'title'        => '✅ وافق المندوب على الارتباط',
                'action_label' => 'موافقة طلب الربط',
                'agent_name'   => auth()->user()->name,
                'message'      => 'وافق المندوب على طلبك وأصبح مرتبطاً بمحلك ونشطاً الآن.',
                'url'          => route('admin.agents.index'),
            ]);
        }
        return back()->with('success', 'لقد وافقت على الانضمام إلى محل "' . ($agent->shop->name ?? '') . '".');
    }

    public function rejectLink(Agent $agent)
    {
        abort_if($agent->user_id !== auth()->id(), 403);
        $agentName = auth()->user()->name;

        if ($admin = $this->shopAdmin($agent)) {
            $this->notify($admin, [
                'type'         => 'danger',
                'title'        => '❌ رفض المندوب طلب الربط',
                'action_label' => 'رفض طلب الربط',
                'agent_name'   => $agentName,
                'message'      => 'رفض المندوب طلبك ولم يوافق على الارتباط. يمكنك إعادة إرسال طلب جديد.',
                'url'          => route('admin.agents.index'),
            ]);
        }

        $agent->update(['link_status' => 'rejected', 'user_id' => null]);
        return back()->with('success', 'تم رفض طلب الربط.');
    }

    public function transactions()  { return view('agent.transactions'); }
    public function reports()       { return view('agent.reports'); }

    public function notifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('agent.notifications', compact('notifications'));
    }

    public function markOneRead(string $id)
    {
        $n = DatabaseNotification::where('id', $id)
               ->where('notifiable_id', auth()->id())
               ->where('notifiable_type', 'App\\Models\\User')
               ->first();
        $url = route('agent.dashboard');
        if ($n) {
            $data = is_array($n->data) ? $n->data : json_decode($n->data, true);
            $url  = $data['url'] ?? $url;
            if (!$n->read_at) $n->markAsRead();
        }
        return redirect($url);
    }

    public function markAllRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        if ($request->wantsJson()) return response()->json(['success' => true]);
        return redirect()->route('agent.notifications');
    }
}
