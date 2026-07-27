<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        if (
            !Schema::hasTable('agents') ||
            !Schema::hasTable('shops') ||
            !Schema::hasTable('users') ||
            !Schema::hasTable('notifications')
        ) {
            return;
        }
        // جلب كل طلبات الربط المعلقة التي ليس لها إشعار بعد
        $pendingAgents = DB::table('agents')
            ->join('users', 'agents.user_id', '=', 'users.id')
            ->join('shops', 'agents.shop_id', '=', 'shops.id')
            ->where('agents.link_status', 'pending')
            ->whereNotNull('agents.user_id')
            ->select(
                'agents.user_id',
                'agents.created_at as agent_created_at',
                'shops.name as shop_name'
            )
            ->get();

        foreach ($pendingAgents as $row) {
            // تحقق ما فيش إشعار مشابه موجود مسبقاً
            $exists = DB::table('notifications')
                ->where('notifiable_id', $row->user_id)
                ->where('notifiable_type', 'App\\Models\\User')
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.title')) = ?", ['طلب ربط جديد'])
                ->exists();

            if (!$exists) {
                DB::table('notifications')->insert([
                    'id'              => (string) Str::uuid(),
                    'type'            => 'App\\Notifications\\AgentLinkNotification',
                    'notifiable_type' => 'App\\Models\\User',
                    'notifiable_id'   => $row->user_id,
                    'data'            => json_encode([
                        'type'    => 'warning',
                        'title'   => 'طلب ربط جديد',
                        'message' => 'محل "' . $row->shop_name . '" يطلب ربطك كمندوب. بانتظار موافقتك.',
                        'url'     => '/agent/dashboard',
                    ]),
                    'read_at'    => null,
                    'created_at' => $row->agent_created_at,
                    'updated_at' => $row->agent_created_at,
                ]);
            }
        }
    }

    public function down(): void
    {
        // لا يمكن التراجع بأمان بدون حذف إشعارات ربما حقيقية
    }
};
