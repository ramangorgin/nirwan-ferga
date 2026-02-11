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
        Schema::create('quiz_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('quiz_id')->constrained('quizzes')->restrictOnDelete();

            $table->unsignedInteger('attempt_number')->default(1);

            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();

            $table->unsignedInteger('total_score')->nullable();
            $table->boolean('passed')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_submissions');
    }
};
