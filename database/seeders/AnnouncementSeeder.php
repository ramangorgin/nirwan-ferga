<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $teacher = User::where('role', 'teacher')->first();
        $courses = Course::limit(3)->get();

        if (!$admin) return;

        // Public announcement
        $announcement1 = Announcement::create([
            'author_id' => $admin->id,
            'title' => 'ڕاگەیاندنی گشتی - پشووی نەورۆز',
            'body' => 'بەڕێز خوێندکاران، لە ١٨ تا ٢٥ ئادارا پلاتفۆرم داخراوە بە هۆی پشووی نەورۆز. نەوروزتان پیرۆز بێت!',
            'is_public' => true,
        ]);

        // Public announcement 2
        $announcement2 = Announcement::create([
            'author_id' => $admin->id,
            'title' => 'اعلان عمومی - به‌روزرسانی سیستم',
            'body' => 'کاربران گرامی، سیستم روز شنبه از ساعت ۲ تا ۴ صبح به دلیل به‌روزرسانی در دسترس نخواهد بود.',
            'is_public' => true,
        ]);

        // Course-specific announcement
        $announcement3 = Announcement::create([
            'author_id' => $teacher?->id ?? $admin->id,
            'title' => 'ڕاگەیاندن بۆ خوێندکارانی کۆرسی ئینگلیزی',
            'body' => 'تکایە ئەرکەکانتان پێش ڕۆژی شەممە بنێرن. دواکەوتن قبووڵ ناکرێت.',
            'is_public' => false,
        ]);

        // Attach announcement to courses
        if ($courses->count() > 0) {
            $announcement3->courses()->attach($courses->first()->id);
        }

        if ($courses->count() > 1) {
            $announcement4 = Announcement::create([
                'author_id' => $teacher?->id ?? $admin->id,
                'title' => 'اطلاعیه دوره برنامه‌نویسی',
                'body' => 'دانشجویان عزیز، جلسه بعدی به دلیل مشکل فنی یک روز به تعویق افتاد.',
                'is_public' => false,
            ]);

            $announcement4->courses()->attach($courses->get(1)->id);
        }

        // Another public announcement
        Announcement::create([
            'author_id' => $admin->id,
            'title' => 'تخفیف ویژه برای دانشجویان جدید',
            'body' => 'با کد تخفیف STUDENT50 می‌توانید ۵۰٪ تخفیف دریافت کنید. این تخفیف تا پایان ماه معتبر است.',
            'is_public' => true,
        ]);
    }
}
