<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number', 50)->unique();
            $table->decimal('amount', 15, 2);
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_channel', 50)->nullable();
            $table->string('transaction_id', 100)->nullable()->comment('ID dari payment aggregator');
            $table->enum('transaction_status', ['pending', 'success', 'failed', 'expired', 'refund'])->default('pending');
            $table->datetime('paid_at')->nullable();
            $table->datetime('expired_at')->nullable();
            $table->text('metadata')->nullable()->comment('Response lengkap dari aggregator (JSON)');
            $table->timestamps();

            $table->index('invoice_number');
            $table->index('transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};