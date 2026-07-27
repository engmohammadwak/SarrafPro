<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $notifications = auth()->user()->notifications()->latest()->paginate(30);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAllRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        if ($request->wantsJson()) return response()->json(['success' => true]);
        return back();
    }

    public function markOneRead(string $id)
    {
        $n = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', auth()->id())
            ->where('notifiable_type', 'App\\Models\\User')
            ->first();

        // الرابط الافتراضي حسب دور المستخدم الحالي
        $role = auth()->user()->role;
        $defaultUrl = match($role) {
            'super_admin'              => route('superadmin.dashboard'),
            'agent', 'cooperation'     => route('agent.dashboard'),
            default                    => route('admin.dashboard'),
        };

        $url = $defaultUrl;

        if ($n) {
            $data        = is_array($n->data) ? $n->data : json_decode($n->data, true);
            $storedUrl   = $data['url'] ?? null;

            // تحقق: هل الرابط المحفوظ خاص بدور المستخدم الحالي
            if ($storedUrl && $this->urlMatchesRole($storedUrl, $role)) {
                $url = $storedUrl;
            }
            // إذا الرابط لدور مختلف نستخدم الرابط الافتراضي

            if (!$n->read_at) $n->markAsRead();
        }

        return redirect($url);
    }

    /**
     * تحقق أن الرابط ينتمي لنفس مجال دور المستخدم
     */
    private function urlMatchesRole(string $url, string $role): bool
    {
        $agentPrefixes      = ['/agent/'];
        $adminPrefixes      = ['/admin/'];
        $superAdminPrefixes = ['/super-admin/'];

        $path = parse_url($url, PHP_URL_PATH) ?? $url;

        $isAgentUrl      = collect($agentPrefixes)->contains(fn($p)      => str_starts_with($path, $p));
        $isAdminUrl      = collect($adminPrefixes)->contains(fn($p)      => str_starts_with($path, $p));
        $isSuperAdminUrl = collect($superAdminPrefixes)->contains(fn($p) => str_starts_with($path, $p));

        return match($role) {
            'super_admin'          => $isSuperAdminUrl,
            'agent', 'cooperation' => $isAgentUrl,
            default                => $isAdminUrl,  // shop_admin, admin, staff
        };
    }

    /** AJAX: آخر 5 إشعارات + عدد غير مقروءة */
    public function latest()
    {
        $user   = auth()->user();
        $unread = $user->unreadNotifications()->count();
        $items  = $user->notifications()->latest()->take(5)->get()->map(function ($n) {
            $data = is_array($n->data) ? $n->data : json_decode($n->data, true);
            return [
                'id'      => $n->id,
                'type'    => $data['type']    ?? 'info',
                'title'   => $data['title']   ?? '',
                'message' => $data['message'] ?? '',
                'url'     => route('admin.notifications.read', $n->id),
                'read'    => !is_null($n->read_at),
                'time'    => $n->created_at->diffForHumans(),
            ];
        });
        return response()->json(['unread' => $unread, 'items' => $items]);
    }
}
