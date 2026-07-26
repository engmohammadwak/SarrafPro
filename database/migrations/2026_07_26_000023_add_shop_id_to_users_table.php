<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('users', 'shop_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('shop_id')->nullable()->after('role');
                $table->foreign('shop_id')->references('id')->on('shops')->nullOnDelete();
            });
        }
    }
    public function down(): void {
        if (Schema::hasColumn('users', 'shop_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['shop_id']);
                $table->dropColumn('shop_id');
            });
        }
    }
};
