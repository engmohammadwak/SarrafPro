<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('shops', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('address');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('created_by');
            $table->string('attachment')->nullable()->after('notes');
        });
    }
    public function down(): void {
        Schema::table('shops', function (Blueprint $table) { $table->dropColumn('notes'); });
        Schema::table('users', function (Blueprint $table) { $table->dropColumn(['notes','attachment']); });
    }
};
