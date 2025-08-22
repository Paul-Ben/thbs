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
        Schema::table('aptitude_test_fees', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['programme_id']);
            // Drop the programme_id column
            $table->dropColumn('programme_id');
            // Add the name column
            $table->string('name')->default('Aptitude Test Fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aptitude_test_fees', function (Blueprint $table) {
            // Drop the name column
            $table->dropColumn('name');
            // Add back the programme_id column
            $table->unsignedBigInteger('programme_id');
            // Add back the foreign key constraint
            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade');
        });
    }
};
