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
        Schema::create('school_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained('programmes')->onDelete('cascade');
            $table->foreignId('school_session_id')->constrained('school_sessions')->onDelete('cascade');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->onDelete('cascade');
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade');
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('NGN');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('fee_type', ['tuition', 'accommodation', 'library', 'laboratory', 'sports', 'medical', 'development', 'examination', 'other'])->default('tuition');
            $table->date('due_date')->nullable();
            $table->timestamps();
            
            // Ensure unique combination of programme, session, semester, level, and fee type
            $table->unique(['programme_id', 'school_session_id', 'semester_id', 'level_id', 'fee_type'], 'unique_school_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_fees');
    }
};
