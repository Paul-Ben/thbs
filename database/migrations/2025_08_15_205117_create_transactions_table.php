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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('paymentable'); 
            $table->enum('type', ['Application fee payment', 'School fee payment', 'Departmental fee payment']);
            $table->string('status');
            $table->decimal('amount', 10, 2);
            $table->string('currency_code', 3)->default('NGN');
            $table->boolean('is_reconciled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
