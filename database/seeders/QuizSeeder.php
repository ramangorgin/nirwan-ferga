<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::limit(3)->get();
        $teacher = User::where('role', 'teacher')->first();

        if ($courses->count() === 0 || !$teacher) return;

        // Published quiz - normal quiz
        Quiz::create([
            'title' => 'تاقیکردنەوەی یەکەم - بنەماکانی زمان',
            'description' => 'ئەم تاقیکردنەوەیە دەربارەی وانەکانی یەکەم و دووەمە',
            'quiz_type' => 'normal_quiz',
            'course_id' => $courses->first()->id,
            'created_by' => $teacher->id,
            'start_at' => now()->addDays(7),
            'end_at' => now()->addDays(10),
            'duration_minutes' => 30,
            'attempt_limit' => 2,
            'shuffle_questions' => true,
            'shuffle_options' => true,
            'auto_grade' => true,
            'show_results_after_submissions' => true,
            'show_correct_answers' => false,
            'passing_score' => 60,
            'total_score_cached' => 100,
            'syllabus_tags' => ['grammar', 'vocabulary'],
            'visibility' => 'published',
        ]);

        // Draft quiz
        Quiz::create([
            'title' => 'تاقیکردنەوەی دووەم',
            'description' => 'هێشتا ئامادە نەکراوە',
            'quiz_type' => 'normal_quiz',
            'course_id' => $courses->first()->id,
            'created_by' => $teacher->id,
            'start_at' => now()->addWeeks(2),
            'end_at' => now()->addWeeks(2)->addDays(2),
            'duration_minutes' => 45,
            'attempt_limit' => 1,
            'auto_grade' => true,
            'show_results_after_submissions' => false,
            'visibility' => 'draft',
        ]);

        if ($courses->count() > 1) {
            // Midterm exam
            Quiz::create([
                'title' => 'امتحان میان‌ترم برنامه‌نویسی',
                'description' => 'امتحان جامع از مباحث هفته‌های اول تا پنجم',
                'quiz_type' => 'midterm',
                'course_id' => $courses->get(1)->id,
                'created_by' => $teacher->id,
                'start_at' => now()->addWeek(),
                'end_at' => now()->addWeek()->addHours(3),
                'duration_minutes' => 120,
                'attempt_limit' => 1,
                'shuffle_questions' => false,
                'shuffle_options' => false,
                'auto_grade' => false,
                'show_results_after_submissions' => false,
                'show_correct_answers' => false,
                'passing_score' => 70,
                'visibility' => 'published',
            ]);

            // Final exam - closed
            Quiz::create([
                'title' => 'امتحان پایانی',
                'description' => 'امتحان نهایی دوره',
                'quiz_type' => 'final_exam',
                'course_id' => $courses->get(1)->id,
                'created_by' => $teacher->id,
                'start_at' => now()->subDays(3),
                'end_at' => now()->subDay(),
                'duration_minutes' => 180,
                'attempt_limit' => 1,
                'auto_grade' => false,
                'show_results_after_submissions' => false,
                'show_correct_answers' => true,
                'passing_score' => 50,
                'total_score_cached' => 200,
                'visibility' => 'closed',
            ]);
        }

        if ($courses->count() > 2) {
            // Placement test
            Quiz::create([
                'title' => 'تاقیکردنەوەی پلەبەندی',
                'description' => 'بۆ دیاریکردنی ئاستی خوێندکار',
                'quiz_type' => 'placement_test',
                'course_id' => $courses->get(2)->id,
                'created_by' => $teacher->id,
                'start_at' => now(),
                'end_at' => now()->addMonth(),
                'duration_minutes' => 60,
                'attempt_limit' => 1,
                'shuffle_questions' => true,
                'auto_grade' => true,
                'show_results_after_submissions' => true,
                'show_correct_answers' => false,
                'visibility' => 'published',
            ]);
        }
    }
}
