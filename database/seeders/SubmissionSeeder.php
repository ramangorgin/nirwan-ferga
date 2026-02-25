<?php

namespace Database\Seeders;

use App\Models\Submission;
use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = Assignment::where('status', 'published')->get();
        $enrollments = Enrollment::where('status', 'confirmed')->get();
        $teacher = User::where('role', 'teacher')->first();

        if ($assignments->count() === 0 || $enrollments->count() === 0) return;

        $assignment = $assignments->first();
        $enrollment = $enrollments->first();

        // Draft submission
        Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $enrollment->student_id,
            'enrollment_id' => $enrollment->id,
            'attempt_number' => 1,
            'status' => 'draft',
            'answer_text' => 'ئەم وەڵامە هێشتا تەواو نەکراوە',
            'is_late' => false,
            'graded_by' => null,
        ]);

        // Submitted submission (waiting for grading)
        if ($enrollments->count() > 1) {
            Submission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $enrollments->get(1)->student_id,
                'enrollment_id' => $enrollments->get(1)->id,
                'attempt_number' => 1,
                'status' => 'submitted',
                'submitted_at' => now()->subHours(3),
                'answer_text' => 'من ناوم ئاکامە و قوتابیم',
                'max_score_cached' => $assignment->score,
                'is_late' => false,
                'graded_by' => null,
            ]);
        }

        // Graded submission
        if ($assignments->count() > 1 && $enrollments->count() > 0) {
            Submission::create([
                'assignment_id' => $assignments->get(1)->id,
                'student_id' => $enrollment->student_id,
                'enrollment_id' => $enrollment->id,
                'attempt_number' => 1,
                'status' => 'graded',
                'submitted_at' => now()->subDays(2),
                'graded_at' => now()->subDay(),
                'graded_by' => $teacher?->id,
                'auto_graded' => false,
                'score_obtained' => 17,
                'max_score_cached' => 20,
                'is_late' => false,
                'answer_text' => 'My name is Sara and I am a student',
                'feedback_text' => 'Very good! Minor grammar mistake.',
            ]);
        }

        // Late submission
        if ($enrollments->count() > 1 && $assignments->count() > 0) {
            Submission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $enrollments->get(1)->student_id,
                'enrollment_id' => $enrollments->get(1)->id,
                'attempt_number' => 2,
                'status' => 'late_submitted',
                'submitted_at' => now()->subHours(1),
                'is_late' => true,
                'answer_text' => 'ببورە، دواکەوتم',
                'graded_by' => null,
                'max_score_cached' => $assignment->score,
            ]);
        }

        // Auto-graded MCQ submission (use different student if first assignment is MCQ)
        if ($assignments->count() > 0 && $enrollments->count() > 1) {
            $mcqAssignment = $assignments->where('type', 'mcq')->first();
            $secondEnrollment = $enrollments->get(1);
            
            if ($mcqAssignment) {
                // If MCQ assignment is the first one, use attempt 3 for second student
                // (they already have attempt 1 for submitted and attempt 2 for late)
                $attemptNumber = ($mcqAssignment->id === $assignment->id) ? 3 : 1;
                
                Submission::create([
                    'assignment_id' => $mcqAssignment->id,
                    'student_id' => $secondEnrollment->student_id,
                    'enrollment_id' => $secondEnrollment->id,
                    'attempt_number' => $attemptNumber,
                    'status' => 'graded',
                    'submitted_at' => now()->subHours(5),
                    'graded_at' => now()->subHours(5),
                    'auto_graded' => true,
                    'score_obtained' => 10,
                    'max_score_cached' => 10,
                    'is_late' => false,
                    'answer_json' => ['answer' => 'B'],
                    'graded_by' => null,
                ]);
            }
        }
    }
}
