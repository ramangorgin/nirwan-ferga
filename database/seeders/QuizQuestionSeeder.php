<?php

namespace Database\Seeders;

use App\Models\QuizQuestion;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $quizzes = Quiz::where('visibility', 'published')->get();
        $teacher = User::where('role', 'teacher')->first();

        if ($quizzes->count() === 0 || !$teacher) return;

        $quiz = $quizzes->first();

        // MCQ Question
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'created_by' => $teacher->id,
            'question_type' => 'mcq',
            'question_text' => 'کامەیان دروستە؟',
            'options' => ['A' => 'I am student', 'B' => 'I is student', 'C' => 'I are student', 'D' => 'I be student'],
            'correct_answer' => 'A',
            'score' => 10,
            'order_index' => 1,
        ]);

        // True/False Question
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'created_by' => $teacher->id,
            'question_type' => 'true_false',
            'question_text' => 'Laravel یک فریمورک PHP است',
            'options' => ['true' => 'بەڵێ', 'false' => 'نەخێر'],
            'correct_answer' => 'true',
            'score' => 5,
            'order_index' => 2,
        ]);

        // Fill blank Question
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'created_by' => $teacher->id,
            'question_type' => 'fill_blank',
            'question_text' => 'من ____ دانشجو هستم (I ____ a student)',
            'correct_answer' => 'am',
            'score' => 10,
            'order_index' => 3,
        ]);

        // Text Question
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'created_by' => $teacher->id,
            'question_type' => 'text',
            'question_text' => 'ئەم ڕستەیە بنووسە بە ئینگلیزی: "من مامۆستام"',
            'correct_answer' => 'I am a teacher',
            'score' => 15,
            'order_index' => 4,
        ]);

        if ($quizzes->count() > 1) {
            $quiz2 = $quizzes->get(1);
            QuizQuestion::create([
                'quiz_id' => $quiz2->id,
                'created_by' => $teacher->id,
                'question_type' => 'mcq',
                'question_text' => 'MVC مخفف چیست؟',
                'options' => [
                    'A' => 'Model View Controller',
                    'B' => 'My Very Cool',
                    'C' => 'Multiple View Categories',
                    'D' => 'Modern Visual Container'
                ],
                'correct_answer' => 'A',
                'score' => 20,
                'order_index' => 1,
            ]);
        }
    }
}
