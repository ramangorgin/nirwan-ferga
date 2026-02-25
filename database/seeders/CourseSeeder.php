<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $teacher1 = User::where('role', 'teacher')->first();
        $teacher2 = User::where('role', 'teacher')->skip(1)->first() ?? $teacher1;

        Course::create([
            'title' => 'کۆرسی زمانی ئینگلیزی بۆ دەستپێکەران',
            'description' => 'ئەم کۆرسە بۆ فێربوونی بنچینەکانی زمانی ئینگلیزییە. تێیدا فێری گرامەر، وشە و قسەکردن دەبیت.',
            'level' => 'beginner',
            'teaching_in_kurdish' => true,
            'capacity_min' => 5,
            'capacity_max' => 20,
            'teacher_id' => $teacher1?->id,
            'registration_deadline' => now()->addDays(15),
            'start_date' => now()->addDays(20),
            'end_date' => now()->addMonths(3),
            'days_of_week' => ['saturday', 'monday', 'wednesday'],
            'start_time' => '18:00:00',
            'session_duration' => 90,
            'sessions_count' => 36,
            'syllabus' => [
                'هەفتەی یەکەم: مامەڵە و دەستپێکردن',
                'هەفتەی دووەم: خێزان و هاوڕێکان',
                'هەفتەی سێیەم: کار و پیشەکان',
            ],
            'price' => 500000,
            'card_number' => '6037991234567890',
            'card_owner_name' => 'دلنیا محمدی',
            'bank_name' => 'بانک ملی',
            'is_active' => true,
            'status' => 'registration_open',
        ]);

        Course::create([
            'title' => 'دوره پیشرفته برنامه‌نویسی وب',
            'description' => 'آموزش کامل Laravel، Vue.js و معماری نرم‌افزار. این دوره شامل پروژه‌های عملی و کارگاهی است.',
            'level' => 'advanced',
            'teaching_in_kurdish' => false,
            'capacity_min' => 8,
            'capacity_max' => 25,
            'teacher_id' => $teacher2?->id,
            'registration_deadline' => now()->addDays(10),
            'start_date' => now()->addDays(14),
            'end_date' => now()->addMonths(4),
            'days_of_week' => ['sunday', 'tuesday', 'thursday'],
            'start_time' => '20:00:00',
            'session_duration' => 120,
            'sessions_count' => 48,
            'syllabus' => [
                'هفته اول: معماری MVC و Laravel',
                'هفته دوم: API RESTful و Authentication',
                'هفته سوم: Vue.js و Vuex',
            ],
            'price' => 1500000,
            'is_active' => true,
            'status' => 'ongoing',
        ]);

        Course::create([
            'title' => 'فێرکاری گرافیک دیزاین',
            'description' => 'فێرکاری تەواوی فۆتۆشۆپ و ئیلوستریتەر بە کوردی. بۆ کەسانێک کە دەیانەوێت دیزاینەر ببن.',
            'level' => 'intermediate',
            'teaching_in_kurdish' => true,
            'capacity_min' => 10,
            'capacity_max' => 30,
            'teacher_id' => $teacher1?->id,
            'registration_deadline' => now()->addDays(25),
            'start_date' => now()->addMonth(),
            'end_date' => now()->addMonths(5),
            'days_of_week' => ['friday', 'saturday'],
            'start_time' => '16:00:00',
            'session_duration' => 120,
            'sessions_count' => 40,
            'syllabus' => [
                'بەشی یەکەم: ناسینی فۆتۆشۆپ',
                'بەشی دووەم: کارکردن لەسەر پرۆژەی ڕاستەقینە',
            ],
            'price' => 800000,
            'is_active' => true,
            'status' => 'full',
        ]);

        Course::create([
            'title' => 'دوره رایگان شروع کسب و کار آنلاین',
            'description' => 'یک دوره کامل و رایگان برای یادگیری اصول راه‌اندازی کسب و کار اینترنتی.',
            'level' => 'free',
            'teaching_in_kurdish' => false,
            'capacity_min' => 0,
            'capacity_max' => 100,
            'teacher_id' => $teacher2?->id,
            'registration_deadline' => now()->addDays(5),
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeeks(6),
            'days_of_week' => ['wednesday'],
            'start_time' => '19:00:00',
            'session_duration' => 60,
            'sessions_count' => 6,
            'syllabus' => ['جلسه ۱: شناخت بازار', 'جلسه ۲: ایده‌یابی'],
            'price' => 0,
            'is_active' => true,
            'status' => 'finished',
        ]);

        Course::create([
            'title' => 'کۆرسی هەلپەیوین',
            'description' => 'کۆرسێک کە هەلوەشاوەتەوە و نایگرێتەوە.',
            'level' => 'beginner',
            'teaching_in_kurdish' => true,
            'capacity_min' => 5,
            'capacity_max' => 15,
            'teacher_id' => $teacher1?->id,
            'registration_deadline' => now()->subDays(10),
            'start_date' => now()->subDays(5),
            'end_date' => now()->addMonths(2),
            'days_of_week' => ['monday'],
            'start_time' => '17:00:00',
            'session_duration' => 90,
            'sessions_count' => 8,
            'syllabus' => ['بەشی یکەم'],
            'price' => 200000,
            'is_active' => false,
            'status' => 'cancelled',
        ]);
    }
}
