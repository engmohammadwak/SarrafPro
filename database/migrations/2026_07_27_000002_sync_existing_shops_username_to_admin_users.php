<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // For every shop that has a username set,
        // copy it to the linked shop_admin user if that user has no username yet.
        DB::statement("
            UPDATE users u
            INNER JOIN shops s ON s.id = u.shop_id
            SET u.username = s.username
            WHERE u.role = 'shop_admin'
              AND (u.username IS NULL OR u.username = '')
              AND s.username IS NOT NULL
              AND s.deleted_at IS NULL
        ");
    }

    public function down(): void {
        // Nothing to reverse safely
    }
};
