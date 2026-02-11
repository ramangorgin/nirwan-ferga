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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('class_sessions')->restrictOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['text', 'mcq', 'fill_blank', 'translation', 'file']);
            $table->text('correct_answer')->nullable();
            $table->json('options')->nullable();
            $table->unsignedInteger('score')->default(1);
            $table->dateTime('deadline');
            $table->boolean('allow_late')->default(false);
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->index('session_id');
            $table->index('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
