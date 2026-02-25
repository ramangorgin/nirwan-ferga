<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\ClassSession;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $sessions = ClassSession::limit(3)->get();

        if ($sessions->count() === 0) return;

        // MCQ Assignment
        Assignment::create([
            'session_id' => $sessions->first()->id,
            'title' => 'پرسیارەکانی چوارگۆشە - وانەی یەکەم',
            'description' => 'پرسیارە هەڵبژێردراوەکان سەبارەت بە مامەڵەکردن',
            'type' => 'mcq',
            'correct_answer' => 'B',
            'options' => ['A' => 'Hello', 'B' => 'Hi', 'C' => 'Hey', 'D' => 'Yo'],
            'score' => 10,
            'deadline' => now()->addDays(7),
            'allow_late' => true,
            'status' => 'published',
        ]);

        // Text Assignment (draft)
        Assignment::create([
            'session_id' => $sessions->first()->id,
            'title' => 'نووسینی پەراگرافێک',
            'description' => 'پەراگرافێک بە ئینگلیزی بنووسە دەربارەی خۆت',
            'type' => 'text',
            'score' => 20,
            'deadline' => now()->addDays(10),
            'allow_late' => false,
            'status' => 'draft',
        ]);

        if ($sessions->count() > 1) {
            // Fill blank Assignment
            Assignment::create([
                'session_id' => $sessions->get(1)->id,
                'title' => 'پڕکردنەوەی بۆشایی',
                'description' => 'بۆشاییەکان پڕبکەرەوە',
                'type' => 'fill_blank',
                'correct_answer' => 'am',
                'score' => 5,
                'deadline' => now()->addDays(5),
                'allow_late' => true,
                'status' => 'published',
            ]);

            // Translation Assignment
            Assignment::create([
                'session_id' => $sessions->get(1)->id,
                'title' => 'وەرگێڕان لە کوردییەوە بۆ ئینگلیزی',
                'description' => 'ئەم ڕستانە وەربگێڕە',
                'type' => 'translation',
                'score' => 15,
                'deadline' => now()->addWeek(),
                'allow_late' => false,
                'status' => 'closed',
            ]);
        }

        if ($sessions->count() > 2) {
            // File Upload Assignment
            Assignment::create([
                'session_id' => $sessions->get(2)->id,
                'title' => 'پرۆژەی کۆتایی',
                'description' => 'پرۆژەکەت وەک فایل بنێرە',
                'type' => 'file',
                'score' => 50,
                'deadline' => now()->addWeeks(2),
                'allow_late' => true,
                'status' => 'published',
            ]);
        }
    }
}
