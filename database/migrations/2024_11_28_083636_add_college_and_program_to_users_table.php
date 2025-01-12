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
        Schema::table('users', function (Blueprint $table) {
            //
              // Add nullable foreign key for college_id
              $table->foreignId('college_id')->nullable()->constrained()->onDelete('set null');
            
              // Add nullable foreign key for program_id
              $table->foreignId('program_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropForeign(['college_id']);
            $table->dropForeign(['program_id']);
            $table->dropColumn(['college_id', 'program_id']);
        });
    }
};
