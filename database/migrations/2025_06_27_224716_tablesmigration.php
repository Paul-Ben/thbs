<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('school_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_name');
            $table->year('year');
            $table->boolean('is_current')->default(0);
            $table->timestamps();
        });

        Schema::create('application_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_name');
            $table->year('year');
            $table->boolean('is_current')->default(0);
            $table->timestamps();
        });

        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('semester_name');
            $table->foreignId('school_session_id')->constrained();
            $table->boolean('is_current')->default(false);
            $table->date('registration_start_date')->nullable();
            $table->date('registration_end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->constrained();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->foreignId('level_id')->constrained();
            $table->foreignId('department_id')->constrained();
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->integer('credit_units')->default(3);
            $table->foreignId('level_id')->constrained();
            $table->foreignId('programme_id')->constrained();
            $table->foreignId('semester_id')->constrained();
            $table->timestamps();
        });

        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('payment_reference');
            $table->foreignId('application_session_id')->constrained();
            $table->string('application_number')->unique()->nullable();
            $table->string('applicant_surname');
            $table->string('applicant_othernames');
            $table->string('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('state_of_origin')->nullable();
            $table->string('lga')->nullable();
            $table->string('nationality')->nullable();
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
            $table->foreignId('programme_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->boolean('declaration_check')->default(true)->nullable();
            $table->string('passport')->nullable();
            $table->string('credential')->nullable();
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
            $table->string('passport');
            $table->string('credential');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('application_id')->constrained();
            $table->foreignId('level_id')->constrained();
            $table->timestamps();
        });

        Schema::create('course_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('school_session_id')->constrained();
            $table->foreignId('semester_id')->constrained();
            $table->foreignId('level_id')->constrained();
            $table->enum('status', ['draft', 'submitted', 'approved', 'declined'])->default('draft');
            $table->timestamps();

            $table->unique(['student_id', 'course_id', 'semester_id']);
        });

        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('school_session_id')->constrained();
            $table->foreignId('semester_id')->constrained();
            $table->foreignId('level_id')->constrained();
            $table->decimal('score', 5, 2);
            $table->string('grade', 2);
            $table->decimal('grade_point', 3, 2)->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'course_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
        Schema::dropIfExists('course_registrations');
        Schema::dropIfExists('students');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('programmes');
        Schema::dropIfExists('levels');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('application_sessions');
        Schema::dropIfExists('school_sessions');
        Schema::dropIfExists('departments');
    }
};
