<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $teacher = User::where('role', 'teacher')->first();

        if (!$admin) return;

        // Published post 1
        Post::create([
            'user_id' => $admin->id,
            'title' => 'چۆن دەست بە فێربوونی زمانێکی نوێ بکەیت',
            'slug' => 'چۆن-دەست-بە-فێربوونی-زمانێکی-نوێ-بکەیت',
            'excerpt' => 'باشترین ڕێگاکان بۆ فێربوونی زمانێکی بیانی لە ماڵەوە.',
            'content' => 'فێربوونی زمانێکی نوێ پێویستی بە بەردەوامی و مەشق هەیە. لێرەدا چەند ڕێگایەکمان بۆ ئێوە ئامادەکردووە...',
            'featured_image' => 'posts/featured-1.jpg',
            'seo_title' => 'چۆن زمانێکی نوێ فێربین',
            'seo_description' => 'ڕێنمایی تەواو بۆ فێربوونی زمانی بیانی',
            'status' => 'published',
            'published_at' => now()->subDays(10),
            'is_indexable' => true,
            'is_followable' => true,
            'view_count' => 150,
            'reading_time' => 5,
        ]);

        // Published post 2
        Post::create([
            'user_id' => $teacher?->id ?? $admin->id,
            'title' => 'مزایای یادگیری برنامه‌نویسی در سال ۲۰۲۶',
            'slug' => 'benefits-of-learning-programming-2026',
            'excerpt' => 'چرا برنامه‌نویسی یکی از مهارت‌های کلیدی آینده است؟',
            'content' => 'با رشد سریع تکنولوژی، برنامه‌نویسی به یکی از مهارت‌های ضروری تبدیل شده است. در این مقاله به بررسی مزایای یادگیری برنامه‌نویسی می‌پردازیم...',
            'featured_image' => 'posts/featured-2.jpg',
            'seo_title' => 'مزایای برنامه‌نویسی در ۲۰۲۶',
            'seo_keywords' => 'برنامه‌نویسی، یادگیری، ۲۰۲۶، مهارت',
            'canonical_url' => 'https://example.com/blog/benefits-of-learning-programming-2026',
            'status' => 'published',
            'published_at' => now()->subWeek(),
            'is_indexable' => true,
            'is_followable' => true,
            'view_count' => 320,
            'reading_time' => 8,
        ]);

        // Draft post
        Post::create([
            'user_id' => $admin->id,
            'title' => 'ئەم بابەتە هێشتا تەواو نەکراوە',
            'slug' => 'draft-post-not-complete',
            'excerpt' => 'ئەم بابەتە هێشتا لە ژێر کارکردنە',
            'content' => 'ناوەڕۆک هێشتا نانووسراوە...',
            'status' => 'draft',
            'published_at' => null,
            'is_indexable' => false,
            'is_followable' => false,
            'view_count' => 0,
        ]);

        // Published post with no SEO
        Post::create([
            'user_id' => $teacher?->id ?? $admin->id,
            'title' => '۵ نکته برای موفقیت در امتحانات',
            'slug' => '5-tips-for-exam-success',
            'excerpt' => 'با این نکات ساده، امتحاناتتان را با موفقیت پشت سر بگذارید.',
            'content' => 'امتحانات می‌توانند استرس‌زا باشند، اما با آماده‌سازی مناسب می‌توانید نتایج عالی بگیرید. در اینجا ۵ نکته مهم را بررسی می‌کنیم...',
            'status' => 'published',
            'published_at' => now()->subDays(3),
            'is_indexable' => true,
            'is_followable' => true,
            'view_count' => 89,
            'reading_time' => 4,
        ]);

        // Soft deleted post
        $deletedPost = Post::create([
            'user_id' => $admin->id,
            'title' => 'پست حذف شده',
            'slug' => 'deleted-post',
            'excerpt' => 'این پست حذف شده است',
            'content' => 'محتوای پست حذف شده',
            'status' => 'published',
            'published_at' => now()->subMonth(),
            'view_count' => 50,
        ]);

        $deletedPost->delete(); // Soft delete
    }
}
