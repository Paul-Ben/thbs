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
        Schema::create('school_fee_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('NGN');
            $table->string('payment_method')->default('card');
            $table->string('transaction_id')->nullable()->unique();
            $table->string('reference')->unique();
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending');
            $table->timestamp('payment_date')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_fee_payments');
    }
};
