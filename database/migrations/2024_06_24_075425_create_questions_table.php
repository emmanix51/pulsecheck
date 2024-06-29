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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->enum('question_type', ['text', 'radio']);
            $table->string('question', 2000);
            $table->longText('description')->nullable();
            $table->longText('data')->nullable(); // JSON data for options or other structured data
            $table->foreignIdFor(\App\Models\Survey::class, 'survey_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
