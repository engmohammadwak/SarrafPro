<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // تعديل enum في users.role لإضافة قيمة cooperation
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','agent','cooperation','user') NOT NULL DEFAULT 'user'");
    }
    public function down(): void {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','agent','user') NOT NULL DEFAULT 'user'");
    }
};
