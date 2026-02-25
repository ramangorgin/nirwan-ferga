<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $students = User::where('role', 'student')->get();
        $teachers = User::where('role', 'teacher')->get();

        if (!$admin || $students->count() === 0) return;

        // Notification 1 - sent to all students
        $notification1 = Notification::create([
            'user_id' => $admin->id,
            'title' => 'پشووی نەورۆز',
            'body' => 'پلاتفۆرم لە ١٨ تا ٢٥ ئادار داخراوە. نەوروزتان پیرۆز بێت!',
            'link' => '/announcements',
        ]);

        // Attach to students (one read, others unread)
        foreach ($students as $index => $student) {
            $notification1->users()->attach($student->id, [
                'read_at' => $index === 0 ? now()->subHours(5) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Notification 2 - sent to specific students
        if ($students->count() > 1) {
            $notification2 = Notification::create([
                'user_id' => $admin->id,
                'title' => 'تایید پرداخت شما',
                'body' => 'پرداخت شما با موفقیت تایید شد و در دوره ثبت‌نام شدید.',
                'link' => '/enrollments',
            ]);

            $notification2->users()->attach($students->get(1)->id, [
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Notification 3 - sent to teachers
        if ($teachers->count() > 0) {
            $notification3 = Notification::create([
                'user_id' => $admin->id,
                'title' => 'دانشجوی جدید در دوره شما',
                'body' => 'یک دانشجوی جدید در دوره شما ثبت‌نام کرده است.',
                'link' => '/courses',
            ]);

            foreach ($teachers as $teacher) {
                $notification3->users()->attach($teacher->id, [
                    'read_at' => now()->subDay(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Notification 4 - general message
        $notification4 = Notification::create([
            'user_id' => $admin->id,
            'title' => 'بەروژبوونی سیستەم',
            'body' => 'سیستەم بەروژکرا. تایبەتمەندی نوێ زیادکرا.',
            'link' => null,
        ]);

        // Send to all users
        $allUsers = User::all();
        foreach ($allUsers as $user) {
            $notification4->users()->attach($user->id, [
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Notification 5 - Course-specific
        $notification5 = Notification::create([
            'user_id' => $teachers->first()?->id ?? $admin->id,
            'title' => 'تکلیف جدید',
            'body' => 'تکلیف جدیدی برای شما ارسال شده است. لطفا قبل از جمعه ارسال کنید.',
            'link' => '/assignments',
        ]);

        if ($students->count() > 0) {
            $notification5->users()->attach($students->first()->id, [
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
