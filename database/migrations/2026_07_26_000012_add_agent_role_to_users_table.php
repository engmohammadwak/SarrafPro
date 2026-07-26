<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // نتجاهل الـ Warning ونعدل الـ ENUM مباشرة
        DB::unprepared("ALTER IGNORE TABLE users MODIFY COLUMN role ENUM('super_admin','shop_admin','staff','agent') NOT NULL DEFAULT 'shop_admin'");
    }
    public function down(): void {
        DB::unprepared("ALTER IGNORE TABLE users MODIFY COLUMN role ENUM('super_admin','shop_admin','staff') NOT NULL DEFAULT 'shop_admin'");
    }
};
