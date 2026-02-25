<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $teachers = User::where('role', 'teacher')->get();
        $courses = Course::take(2)->get();

        if ($students->count() === 0 || $teachers->count() === 0) return;

        $student1 = $students->first();
        $student2 = $students->count() > 1 ? $students->get(1) : $student1;
        $teacher1 = $teachers->first();
        $teacher2 = $teachers->count() > 1 ? $teachers->get(1) : $teacher1;

        // Conversation 1
        Conversation::create([
            'student_id' => $student1->id,
            'teacher_id' => $teacher1->id,
            'course_id' => $courses->first()->id ?? null,
        ]);

        // Conversation 2
        Conversation::create([
            'student_id' => $student2->id,
            'teacher_id' => $teacher1->id,
            'course_id' => $courses->count() > 1 ? $courses->get(1)->id : null,
        ]);

        // Conversation 3
        Conversation::create([
            'student_id' => $student1->id,
            'teacher_id' => $teacher2->id,
            'course_id' => null,
        ]);
    }
}
