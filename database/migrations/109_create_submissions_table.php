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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            // Core foreign keys
            $table->foreignId('assignment_id')->constrained('assignments')->restrictOnDelete();
            $table->foreignId('student_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->restrictOnDelete();

            // Attempt management
            $table->unsignedSmallInteger('attempt_number');

            // Submission lifecycle
            $table->enum('status', ['draft', 'submitted', 'graded', 'returned', 'cancelled', 'late_submitted'])->default('submitted');
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('graded_at')->nullable();

            // Grading
            $table->foreignId('graded_by')->constrained('users')->restrictOnDelete()->nullable();
            $table->boolean('auto_graded')->default(false);
            $table->unsignedInteger('score_obtained')->nullable();            
            $table->unsignedInteger('max_score_cached')->nullable();

            // Late submission
            $table->boolean('is_late')->default(false);

            // Answer content
            $table->text('answer_text')->nullable();
            $table->json('answer_json')->nullable();
            $table->string('file_path' , 500)->nullable();

            // Feedback
            $table->text('feedback_text')->nullable();

            // Timestamps
            $table->timestamps();

            // Unique constraint
            $table->unique(['assignment_id', 'student_id', 'attempt_number']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
