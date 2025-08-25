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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->string('payment_reference')->unique()->nullable();
            $table->string('applicant_surname');
            $table->string('applicant_othernames');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('state_of_origin');
            $table->string('lga');
            $table->string('nationality');
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('home_town')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('correspondence_address');
            $table->string('employment_status')->nullable();
            $table->string('permanent_home_address');
            $table->string('parent_guardian_name');
            $table->string('parent_guardian_phone');
            $table->string('parent_guardian_address');
            $table->string('parent_guardian_occupation')->nullable();
            $table->foreignId('programme_id')->nullable()->constrained('programmes')->onDelete('set null');
            $table->foreignId('application_session_id')->nullable()->constrained('application_sessions')->onDelete('set null');
            $table->string('status')->default('pending');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
