<?php

namespace Database\Seeders;

use App\Models\ClassSession;
use App\Models\Course;
use Illuminate\Database\Seeder;

class ClassSessionSeeder extends Seeder
{
    public function run(): void
    {
        $course1 = Course::first();
        $course2 = Course::skip(1)->first();

        if ($course1) {
            ClassSession::create([
                'course_id' => $course1->id,
                'title' => 'وانەی یەکەم: مامەڵە و ناسینەوە',
                'session_number' => 1,
                'session_date' => now()->addDays(21),
                'start_time' => '18:00:00',
                'end_time' => '19:30:00',
                'meeting_link' => 'https://zoom.us/j/123456789',
                'status' => 'scheduled',
                'description' => 'لەم وانەیەدا فێری مامەڵەکردن بە ئینگلیزی دەبین.',
                'has_materials' => true,
            ]);

            ClassSession::create([
                'course_id' => $course1->id,
                'title' => 'وانەی دووەم: ژمارەکان و ڕەنگەکان',
                'session_number' => 2,
                'session_date' => now()->addDays(23),
                'start_time' => '18:00:00',
                'end_time' => '19:30:00',
                'status' => 'held',
                'description' => 'فێربوونی ژمارە و ڕەنگەکان بە ئینگلیزی.',
                'has_materials' => false,
            ]);
        }

        if ($course2) {
            ClassSession::create([
                'course_id' => $course2->id,
                'title' => 'جلسه اول: معرفی Laravel',
                'session_number' => 1,
                'session_date' => now()->addDays(15),
                'start_time' => '20:00:00',
                'end_time' => '22:00:00',
                'meeting_link' => 'https://meet.google.com/abc-defg-hij',
                'status' => 'cancelled',
                'description' => 'آشنایی با فریمورک لاراول و نصب آن.',
            ]);

            ClassSession::create([
                'course_id' => $course2->id,
                'title' => 'جلسه دوم: MVC و Routing',
                'session_number' => 2,
                'session_date' => now()->subDays(2),
                'start_time' => '20:00:00',
                'end_time' => '22:00:00',
                'status' => 'held',
                'description' => 'آموزش معماری MVC و مسیریابی در لاراول.',
                'has_materials' => true,
            ]);

            ClassSession::create([
                'course_id' => $course2->id,
                'title' => 'جلسه سوم: Eloquent ORM',
                'session_number' => 3,
                'session_date' => now()->addWeek(),
                'start_time' => '20:00:00',
                'end_time' => '22:00:00',
                'status' => 'postponed',
                'description' => 'کار با دیتابیس و Eloquent.',
            ]);
        }
    }
}
