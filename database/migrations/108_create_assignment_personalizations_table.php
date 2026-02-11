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
        Schema::create('assignment_personalizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->restrictOnDelete();
            $table->foreignId('student_id')->constrained('users')->restrictOnDelete();
            $table->string('custom_title')->nullable();
            $table->text('custom_description')->nullable();
            $table->enum('custom_type', ['text', 'mcq', 'fill_blank', 'translation', 'file'])->nullable();
            $table->json('custom_options')->nullable();
            $table->text('custom_correct_answer')->nullable();
            $table->dateTime('custom_deadline')->nullable();
            $table->unsignedInteger('custom_score')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->unique(['assignment_id', 'student_id']);        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_personalizations');
    }
};
