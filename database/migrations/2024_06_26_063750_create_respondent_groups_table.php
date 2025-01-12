<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('respondent_groups', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['student', 'faculty', 'staff', 'stakeholder']);
            $table->text('category')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_respondent_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->foreignId('respondent_group_id')->constrained('respondent_groups')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_respondent_group');
        Schema::dropIfExists('respondent_groups');
    }
};
