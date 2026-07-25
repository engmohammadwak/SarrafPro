<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('name');                        // اسم البنك / الصراف / العملة
            $table->string('type');                        // cash, bank, exchange, crypto
            $table->string('country')->nullable();         // الدولة
            $table->string('currency')->default('OMR');
            $table->string('account_number')->nullable();  // رقم الحساب / IBAN
            $table->string('attachment')->nullable();      // مسار الملف
            $table->decimal('balance', 20, 4)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('accounts'); }
};
