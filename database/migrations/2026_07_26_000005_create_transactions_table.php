<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade'); // staff user
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('agent_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('account_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type');                    // buy, sell, transfer, deposit, withdraw
            $table->string('currency_from')->nullable();
            $table->string('currency_to')->nullable();
            $table->decimal('amount', 20, 4);
            $table->decimal('rate', 20, 6)->nullable();
            $table->decimal('amount_result', 20, 4)->nullable();
            $table->decimal('fee', 20, 4)->default(0);
            $table->string('reference')->nullable()->unique();
            $table->text('notes')->nullable();
            $table->string('status')->default('completed'); // completed, pending, cancelled
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('transactions'); }
};
