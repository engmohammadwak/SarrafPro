<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Problem: the standard unique index on shops.username blocks re-using a username
 * that belongs to a soft-deleted shop, because MySQL's unique index does NOT
 * automatically exclude rows where deleted_at IS NOT NULL.
 *
 * Fix: drop the standard unique index and replace it with a partial (conditional)
 * unique index that only enforces uniqueness on non-deleted rows.
 *
 * NOTE: MySQL does not support partial indexes natively, so we use a
 * UNIQUE index on a generated (virtual) column that returns NULL for
 * deleted rows — MySQL ignores NULLs in unique indexes.
 */
return new class extends Migration {
    public function up(): void {
        // 1. Drop the old standard unique index
        Schema::table('shops', function ($table) {
            $table->dropUnique('shops_username_unique');
        });

        // 2. Add a generated virtual column:
        //    - Returns username when deleted_at IS NULL  (active row)
        //    - Returns NULL    when deleted_at IS NOT NULL (soft-deleted)
        //    MySQL skips NULLs in unique indexes, so deleted rows won't block reuse.
        DB::statement("
            ALTER TABLE shops
            ADD COLUMN username_unique_key VARCHAR(50)
                GENERATED ALWAYS AS (IF(deleted_at IS NULL, username, NULL))
                VIRTUAL
        ");

        // 3. Add the new unique index on the generated column
        DB::statement("
            ALTER TABLE shops
            ADD UNIQUE INDEX shops_username_active_unique (username_unique_key)
        ");
    }

    public function down(): void {
        // Reverse: remove generated column & index, restore the original unique index
        DB::statement('ALTER TABLE shops DROP INDEX shops_username_active_unique');
        DB::statement('ALTER TABLE shops DROP COLUMN username_unique_key');

        Schema::table('shops', function ($table) {
            $table->unique('username', 'shops_username_unique');
        });
    }
};
