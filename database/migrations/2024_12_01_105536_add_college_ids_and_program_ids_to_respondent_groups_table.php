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
        Schema::table('respondent_groups', function (Blueprint $table) {
            //
            $table->json('college_ids')->nullable();  // Store college IDs as a JSON array
            $table->json('program_ids')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('respondent_groups', function (Blueprint $table) {
            //
            $table->dropColumn('college_ids');
            $table->dropColumn('program_ids');
        });
    }
};
