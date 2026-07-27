<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('agents', function (Blueprint $table) {
            $table->string('phone2', 30)->nullable()->after('phone');       // رقم هاتف ثاني
            $table->string('attachment')->nullable()->after('notes');        // ملف مرفوع
            $table->text('admin_notes')->nullable()->after('attachment');    // ملاحظات داخلية للأدمن
        });
    }

    public function down(): void {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn(['phone2', 'attachment', 'admin_notes']);
        });
    }
};
