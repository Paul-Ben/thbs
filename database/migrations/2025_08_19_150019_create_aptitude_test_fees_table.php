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
        Schema::create('aptitude_test_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Aptitude Test Fee');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('NGN');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aptitude_test_fees');
    }
};
