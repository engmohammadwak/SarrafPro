<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('agents', function (Blueprint $table) {
            // رابط للمستخدم في النظام (اختياري)
            $table->foreignId('user_id')->nullable()->after('shop_id')->constrained()->nullOnDelete();
            // pending = بانتظار موافقة المستخدم, approved = تمت الموافقة, none = بدون حساب
            $table->enum('link_status', ['none','pending','approved','rejected'])->default('none')->after('user_id');
        });
    }
    public function down(): void {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id','link_status']);
        });
    }
};
