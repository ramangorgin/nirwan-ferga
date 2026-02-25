<?php

namespace Database\Seeders;

use App\Models\AssignmentPersonalization;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class AssignmentPersonalizationSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = Assignment::limit(2)->get();
        $students = User::where('role', 'student')->get();
        $teacher = User::where('role', 'teacher')->first();

        if ($assignments->count() === 0 || $students->count() === 0 || !$teacher) return;

        $assignment = $assignments->first();

        // Personalization for first student with custom deadline
        if ($students->count() > 0) {
            AssignmentPersonalization::create([
                'assignment_id' => $assignment->id,
                'student_id' => $students->first()->id,
                'custom_title' => 'ئەرکی تایبەتی بۆ تۆ',
                'custom_description' => 'ئەم ئەرکە تایبەتە بۆ تۆ بە مەودای زیاتر',
                'custom_deadline' => now()->addDays(14),
                'custom_score' => 15,
                'created_by' => $teacher->id,
            ]);
        }

        // Personalization with different options
        if ($students->count() > 1 && $assignments->count() > 1) {
            AssignmentPersonalization::create([
                'assignment_id' => $assignments->get(1)->id,
                'student_id' => $students->get(1)->id,
                'custom_type' => 'text',
                'custom_options' => null,
                'custom_correct_answer' => 'This is a customized answer',
                'custom_score' => 25,
                'created_by' => $teacher->id,
            ]);
        }
    }
}
