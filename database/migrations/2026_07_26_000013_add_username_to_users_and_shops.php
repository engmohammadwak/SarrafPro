<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // users
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });
        // shops
        Schema::table('shops', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });

        // توليد username تلقائي للسجلات الحالية
        DB::statement("UPDATE users SET username = CONCAT('user_', id) WHERE username IS NULL");
        DB::statement("UPDATE shops SET username = CONCAT('shop_', id) WHERE username IS NULL");
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) { $table->dropColumn('username'); });
        Schema::table('shops', function (Blueprint $table) { $table->dropColumn('username'); });
    }
};
