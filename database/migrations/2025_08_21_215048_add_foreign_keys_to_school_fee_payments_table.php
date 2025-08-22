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
        Schema::table('school_fee_payments', function (Blueprint $table) {
            $table->foreignId('school_fee_id')->after('id')->constrained('school_fees')->onDelete('cascade');
            $table->foreignId('student_id')->after('school_fee_id')->nullable()->constrained('students')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_fee_payments', function (Blueprint $table) {
            $table->dropForeign(['school_fee_id']);
            $table->dropForeign(['student_id']);
            $table->dropColumn(['school_fee_id', 'student_id']);
        });
    }
};
