<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // 1) حوّل لـ VARCHAR عشان نقدر نعدّل البيانات بحرية
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(30) NOT NULL DEFAULT 'user'");

        // 2) صلّح أي قيمة غير معروفة
        DB::statement("UPDATE users SET role = 'user' WHERE role NOT IN ('super_admin','admin','agent','cooperation','user')");

        // 3) أعد تعريفه كـ ENUM كامل
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','agent','cooperation','user') NOT NULL DEFAULT 'user'");
    }

    public function down(): void {
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(30) NOT NULL DEFAULT 'user'");
        DB::statement("UPDATE users SET role = 'agent' WHERE role = 'cooperation'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','agent','user') NOT NULL DEFAULT 'user'");
    }
};
