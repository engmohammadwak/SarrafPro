<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // تصحيح أي قيم غير معروفة قبل تعديل الـ enum
        DB::statement("UPDATE users SET role = 'user' WHERE role NOT IN ('super_admin','admin','agent','user')");

        // إضافة cooperation للـ enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','agent','cooperation','user') NOT NULL DEFAULT 'user'");
    }
    public function down(): void {
        // إعادة cooperation إلى agent عند الرولباك
        DB::statement("UPDATE users SET role = 'agent' WHERE role = 'cooperation'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','agent','user') NOT NULL DEFAULT 'user'");
    }
};
