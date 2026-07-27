<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Convert to VARCHAR first to safely modify
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(30) NOT NULL DEFAULT 'user'");

        // Fix any stray values
        DB::statement("UPDATE users SET role = 'user' WHERE role NOT IN ('super_admin','shop_admin','admin','agent','cooperation','user')");

        // Re-define ENUM with shop_admin added
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','shop_admin','admin','agent','cooperation','user') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(30) NOT NULL DEFAULT 'user'");
        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'shop_admin'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','agent','cooperation','user') NOT NULL DEFAULT 'user'");
    }
};
