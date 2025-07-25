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
        Schema::create('colleges', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('level'); // e.g., "100", "200"
            $table->foreignId('college_id')->constrained();
            $table->timestamps();
        });

        Schema::create('school_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_name'); // e.g., "2024/2025"
            $table->year('year');
            $table->timestamps();
        });

        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('semester_name'); // e.g., "First Semester"
            $table->foreignId('school_session_id')->constrained();
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->foreignId('programme_id')->constrained();
            $table->foreignId('semester_id')->constrained();
            $table->timestamps();
        });

        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->string('applicant_surname');
            $table->string('applicant_othernames');
            $table->string('date_of_birth');
            $table->string('gender');
            $table->string('state_of_origin');
            $table->string('lga');
            $table->string('nationality');
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('home_town')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('correspondence_address')->nullable();
            $table->string('employment_status')->nullable();
            $table->string('permanent_home_address')->nullable();
            $table->string('parent_guardian_name')->nullable();
            $table->string('parent_guardian_phone')->nullable();
            $table->string('parent_guardian_address')->nullable();
            $table->string('parent_guardian_occupation')->nullable();
            $table->foreignId('programme_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->boolean('declaration_check')->default(true);
            $table->string('passport');
            $table->string('credential');
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_name');
            $table->string('matric_number')->unique();
            $table->string('email')->unique();
            $table->string('gender');
            $table->string('phone');
            $table->string('address');
            $table->string('country');
            $table->string('state_of_origin');
            $table->string('lga');
            $table->foreignId('programme_id')->constrained();
            $table->string('date_of_birth');
            $table->string('passport'); // file path or filename
            $table->string('credential');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('application_id')->constrained();
            $table->timestamps();
        });

        Schema::create('course_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('semester_id')->constrained();
            $table->timestamps();

            $table->unique(['student_id', 'course_id', 'semester_id']);
        });

        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('semester_id')->constrained();
            $table->decimal('score', 5, 2);
            $table->string('grade', 2);
            $table->timestamps();

            $table->unique(['student_id', 'course_id', 'semester_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colleges');
        Schema::dropIfExists('programmes');
        Schema::dropIfExists('school_sessions');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('students');
        Schema::dropIfExists('course_registrations');
        Schema::dropIfExists('results');
    }
};
