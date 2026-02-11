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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('quiz_type', ['normal_quiz', 'midterm', 'final_exam', 'placement_test']);
            $table->foreignId('course_id')->constrained('courses')->restrictOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');            
            $table->unsignedInteger('duration_minutes');
            $table->unsignedInteger('attempt_limit')->default(1);
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_options')->default(false);
            $table->boolean('auto_grade')->default(true);
            $table->boolean('show_results_after_submissions')->default(true);
            $table->boolean('show_correct_answers')->default(true);
            $table->unsignedInteger('passing_score')->nullable();
            $table->unsignedInteger('total_score_cached')->nullable();
            $table->json('syllabus_tags')->nullable();
            $table->text('requirements_text')->nullable();
            $table->enum('visibility', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
