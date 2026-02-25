<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $sessions = ClassSession::where('status', 'held')->get();
        $students = User::where('role', 'student')->get();

        if ($sessions->count() === 0 || $students->count() === 0) return;

        $session = $sessions->first();

        // Present attendance
        if ($students->count() > 0) {
            Attendance::create([
                'session_id' => $session->id,
                'student_id' => $students->first()->id,
                'status' => 'present',
                'note' => 'بەژداری کردوە لە وانەکەدا',
            ]);
        }

        // Late attendance
        if ($students->count() > 1) {
            Attendance::create([
                'session_id' => $session->id,
                'student_id' => $students->get(1)->id,
                'status' => 'late',
                'note' => '۱۵ خولەک دواکەوت',
            ]);
        }

        // Absent attendance
        if ($students->count() > 0 && $sessions->count() > 1) {
            Attendance::create([
                'session_id' => $sessions->get(1)->id ?? $session->id,
                'student_id' => $students->first()->id,
                'status' => 'absent',
                'note' => 'ئامادە نەبوو',
            ]);
        }

        // Excused attendance
        if ($students->count() > 1 && $sessions->count() > 1) {
            Attendance::create([
                'session_id' => $sessions->get(1)->id ?? $session->id,
                'student_id' => $students->get(1)->id,
                'status' => 'excused',
                'note' => 'بە هۆی نەخۆشی',
            ]);
        }
    }
}
