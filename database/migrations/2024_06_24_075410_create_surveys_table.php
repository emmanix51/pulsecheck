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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_public')->default(false); // For public accessibility
            // $table->boolean('is_restricted')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image', 255)->nullable();
            $table->string('title', 1000);
            $table->string('slug', 1000)->unique();
            // $table->longText('information_field')->nullable();
            $table->tinyInteger('status');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->timestamp('expire_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
