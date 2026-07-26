<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('username');
        });
        Schema::table('shops', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('username');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) { $table->dropForeignIdFor(\App\Models\User::class,'created_by'); $table->dropColumn('created_by'); });
        Schema::table('shops', function (Blueprint $table) { $table->dropForeignIdFor(\App\Models\User::class,'created_by'); $table->dropColumn('created_by'); });
    }
};
