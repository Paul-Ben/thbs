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
        Schema::create('academic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('level');
            $table->string('school_name')->nullable();
            $table->string('exam_type')->nullable();
            $table->string('exam_year')->nullable();
            $table->string('subject')->nullable();
            $table->string('grade')->nullable();
            $table->integer('number_of_sittings')->nullable();
            $table->string('other_qualification')->nullable();
            $table->string('graduation_year')->nullable();
            $table->string('certificate_obtained')->nullable();
            $table->string('alevel_grade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_records');
    }
};
