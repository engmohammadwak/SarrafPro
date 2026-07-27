<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class DashboardController extends Controller
{
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
        return back()->with('success', 'تم توقيف المحل بنجاح.');
    }

    public function shopUnblock(int $id)
    {
        $agent = $this->ownAgent($id);
        abort_if($agent->link_status !== 'approved', 403);
        $agent->update(['is_active' => true]);
        return back()->with('success', 'تم تفعيل المحل بنجاح.');
    }

    /**
     * طلب فك الارتباط:
     * - لو الرصيد = 0  → نفصل فوراً (user_id = null, link_status = rejected)
     * - لو الرصيد != 0 → نضع link_status = unlink_pending وتبقى البيانات كاملة
     *   حتى يتم تسوية الرصيد من قِبل الأدمن
     */
    public function shopUnlink(int $id)
    {
        $agent = $this->ownAgent($id);
        abort_if(!in_array($agent->link_status, ['approved', 'unlink_pending']), 403);

        if (!empty($agent->balance) && $agent->balance != 0) {
            // في رصيد → لا نحذف user_id، فقط نعلّم بانتظار التسوية
            $agent->update(['link_status' => 'unlink_pending']);
            return back()->with('success', 'تم تقديم طلب فك الارتباط. سيبقى السجل محفوظاً حتى تسوية الرصيد ('. number_format($agent->balance, 2) .').');
        }

        // رصيد صفر → فك فوري
        $agent->update(['link_status' => 'rejected', 'user_id' => null]);
        return redirect()->route('agent.shops.index')->with('success', 'تم فك الارتباط بنجاح.');
    }

    // ================================================================
    // LINK REQUESTS
    // ================================================================
    public function approveLink(Agent $agent)
    {
        abort_if($agent->user_id !== auth()->id(), 403);
        $agent->update(['link_status' => 'approved']);
        return back()->with('success', 'لقد وافقت على الانضمام إلى محل "' . ($agent->shop->name ?? '') . '".');
    }

    public function rejectLink(Agent $agent)
    {
        abort_if($agent->user_id !== auth()->id(), 403);
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
