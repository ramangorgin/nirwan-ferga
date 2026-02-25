<?php

namespace App\Services\Posts;

use App\Models\Post;
use App\Models\User;
use App\Services\DateTime\DateTimeService;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService
{
    public function __construct(
        protected DateTimeService $dateTimeService,
        protected NotificationService $notificationService,
        protected SmsService $smsService
    ) {}

    public function create(array $data, int $authorId, string $authorTimezone, ?UploadedFile $featuredImage): Post
    {
        return DB::transaction(function () use ($data, $authorId, $authorTimezone, $featuredImage) {

            $title = $data['title'];
            $slug = $this->generateUniqueSlug($title);

            $status = $data['status'] ?? 'draft';

            $publishedAtUtc = null;
            if (!empty($data['published_at'])) {
                // Treat as Jalali datetime string coming from blade
                $publishedAtUtc = $this->dateTimeService->jalaliToUtc($data['published_at'], $authorTimezone);
            } elseif ($status === 'published') {
                $publishedAtUtc = now('UTC');
            }

            $content = $data['content'];
            $readingTime = $this->estimateReadingTimeMinutes($content);

            $post = Post::create([
                'user_id' => $authorId,
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $data['excerpt'] ?? null,
                'content' => $content,

                'featured_image' => null,
                'featured_image_alt' => $data['featured_image_alt'] ?? null,

                'seo_title' => $data['seo_title'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
                'seo_keywords' => $data['seo_keywords'] ?? null,
                'canonical_url' => $data['canonical_url'] ?? null,

                'status' => $status,
                'published_at' => $publishedAtUtc,
                'is_indexable' => (bool) ($data['is_indexable'] ?? true),
                'is_followable' => (bool) ($data['is_followable'] ?? true),

                'view_count' => 0,
                'reading_time' => $readingTime,
            ]);

            if ($featuredImage) {
                $path = $this->storeFeaturedImage($post, $featuredImage);
                $post->update(['featured_image' => $path]);
            }

            // Optional: notify users when published (notification only by default)
            if ($post->isPublished()) {
                $this->notifyOnPublish($post);
            }

            return $post->fresh(['author']);
        });
    }

    public function update(Post $post, array $data, string $actorTimezone, ?UploadedFile $featuredImage): Post
    {
        return DB::transaction(function () use ($post, $data, $actorTimezone, $featuredImage) {

            $wasPublished = $post->isPublished();

            if (array_key_exists('title', $data)) {
                $post->title = $data['title'];

                // If slug is based on title and you want it to change, update it.
                // SEO-friendly approach: DO NOT change slug once published.
                // Production-fast: if draft -> allow slug change; if published -> keep slug.
                if (!$wasPublished) {
                    $post->slug = $this->generateUniqueSlug($post->title, $post->id);
                }
            }

            if (array_key_exists('excerpt', $data)) $post->excerpt = $data['excerpt'];
            if (array_key_exists('content', $data)) {
                $post->content = $data['content'];
                $post->reading_time = $this->estimateReadingTimeMinutes($post->content);
            }

            if (array_key_exists('featured_image_alt', $data)) $post->featured_image_alt = $data['featured_image_alt'];

            if (array_key_exists('seo_title', $data)) $post->seo_title = $data['seo_title'];
            if (array_key_exists('seo_description', $data)) $post->seo_description = $data['seo_description'];
            if (array_key_exists('seo_keywords', $data)) $post->seo_keywords = $data['seo_keywords'];
            if (array_key_exists('canonical_url', $data)) $post->canonical_url = $data['canonical_url'];

            if (array_key_exists('is_indexable', $data)) $post->is_indexable = (bool) $data['is_indexable'];
            if (array_key_exists('is_followable', $data)) $post->is_followable = (bool) $data['is_followable'];

            // Publish status and published_at
            if (array_key_exists('status', $data)) {
                $post->status = $data['status'];
            }

            if (array_key_exists('published_at', $data)) {
                if (!empty($data['published_at'])) {
                    $post->published_at = $this->dateTimeService->jalaliToUtc($data['published_at'], $actorTimezone);
                } else {
                    $post->published_at = null;
                }
            } else {
                // If status changed to published and published_at was empty -> set now UTC
                if (!$wasPublished && $post->status === 'published' && $post->published_at === null) {
                    $post->published_at = now('UTC');
                }
            }

            $post->save();

            if ($featuredImage) {
                // Delete old image if exists
                if ($post->featured_image) {
                    Storage::disk('public')->delete($post->featured_image);
                }
                $path = $this->storeFeaturedImage($post, $featuredImage);
                $post->update(['featured_image' => $path]);
            }

            // Notify on publish (only if it became published)
            if (!$wasPublished && $post->isPublished()) {
                $this->notifyOnPublish($post);
            }

            return $post->fresh(['author']);
        });
    }

    public function delete(Post $post): void
    {
        DB::transaction(function () use ($post) {
            // Optionally delete image files
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $post->delete();
        });
    }

    /**
     * SEO-friendly unique slug generation.
     */
    protected function generateUniqueSlug(string $title, ?int $ignorePostId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
            Post::query()
                ->where('slug', $slug)
                ->when($ignorePostId, fn($q) => $q->where('id', '!=', $ignorePostId))
                ->exists()
        ) {
            $i++;
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }

    /**
     * Store featured image under public disk.
     */
    protected function storeFeaturedImage(Post $post, UploadedFile $file): string
    {
        $dir = "posts/{$post->id}/featured";
        return $file->store($dir, 'public');
    }

    /**
     * Rough reading time estimation.
     * Uses ~200 words per minute.
     */
    protected function estimateReadingTimeMinutes(string $htmlOrText): int
    {
        // Strip tags to avoid counting HTML markup
        $text = trim(strip_tags($htmlOrText));

        if ($text === '') return 1;

        // Count words for Kurdish/Persian-ish text: split by whitespace
        $words = preg_split('/\s+/u', $text) ?: [];
        $count = count(array_filter($words, fn($w) => trim($w) !== ''));

        $minutes = (int) ceil($count / 200);
        return max(1, $minutes);
    }

    /**
     * Notify users when a post is published.
     * Production-fast: notify only students (can be adjusted).
     * SMS is disabled by default to prevent spam.
     */
    protected function notifyOnPublish(Post $post): void
    {
        // You can change the target audience later.
        User::query()
            ->select('id')
            ->where('role', 'student')
            ->chunkById(500, function ($users) use ($post) {
                foreach ($users as $u) {
                    $this->notificationService->notifyUser(
                        recipientUserId: (int) $u->id,
                        creatorUserId: (int) $post->user_id,
                        title: 'New blog post',
                        body: mb_substr($post->title, 0, 120),
                        link: route('posts.show', $post)
                    );

                    // SMS OFF by default (optional).
                    // $this->smsService->sendToUserId((int) $u->id, "New post: {$post->title}");
                }
            });
    }
}