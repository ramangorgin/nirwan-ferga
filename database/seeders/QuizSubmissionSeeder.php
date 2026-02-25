<?php

namespace Database\Seeders;

use App\Models\QuizSubmission;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $quizzes = Quiz::where('visibility', 'published')->get();
        $students = User::where('role', 'student')->get();

        if ($quizzes->count() === 0 || $students->count() === 0) return;

        $quiz = $quizzes->first();

        // Completed submission - passed
        QuizSubmission::create([
            'student_id' => $students->first()->id,
            'quiz_id' => $quiz->id,
            'attempt_number' => 1,
            'started_at' => now()->subHours(2),
            'finished_at' => now()->subHours(1)->subMinutes(30),
            'total_score' => 75,
            'passed' => true,
        ]);

        // In-progress submission (not finished)
        if ($students->count() > 1) {
            QuizSubmission::create([
                'student_id' => $students->get(1)->id,
                'quiz_id' => $quiz->id,
                'attempt_number' => 1,
                'started_at' => now()->subMinutes(10),
                'finished_at' => null,
                'total_score' => null,
                'passed' => null,
            ]);
        }

        // Completed submission - failed
        if ($students->count() > 0 && $quizzes->count() > 1) {
            QuizSubmission::create([
                'student_id' => $students->first()->id,
                'quiz_id' => $quizzes->get(1)->id,
                'attempt_number' => 1,
                'started_at' => now()->subDays(3),
                'finished_at' => now()->subDays(3)->addHour(),
                'total_score' => 45,
                'passed' => false,
            ]);
        }

        // Second attempt - passed
        if ($students->count() > 0 && $quizzes->count() > 1) {
            QuizSubmission::create([
                'student_id' => $students->first()->id,
                'quiz_id' => $quizzes->get(1)->id,
                'attempt_number' => 2,
                'started_at' => now()->subDay(),
                'finished_at' => now()->subDay()->addMinutes(50),
                'total_score' => 85,
                'passed' => true,
            ]);
        }
    }
}
