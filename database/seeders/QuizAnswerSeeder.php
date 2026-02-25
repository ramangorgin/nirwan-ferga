<?php

namespace Database\Seeders;

use App\Models\QuizAnswer;
use App\Models\QuizSubmission;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizAnswerSeeder extends Seeder
{
    public function run(): void
    {
        $submissions = QuizSubmission::whereNotNull('finished_at')->get();

        if ($submissions->count() === 0) return;

        foreach ($submissions->take(2) as $submission) {
            $questions = $submission->quiz->questions;

            if ($questions->count() === 0) continue;

            foreach ($questions as $question) {
                // Create answer for each question
                $isCorrect = rand(0, 1) === 1; // Random correct/incorrect

                QuizAnswer::create([
                    'submission_id' => $submission->id,
                    'question_id' => $question->id,
                    'student_answer' => $isCorrect ? $question->correct_answer : 'Wrong Answer',
                    'score_awarded' => $isCorrect ? $question->score : 0,
                ]);
            }
        }
    }
}
