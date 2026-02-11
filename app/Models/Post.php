<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_alt',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'canonical_url',
        'status',
        'published_at',
        'is_indexable',
        'is_followable',
        'view_count',
        'reading_time'
    ];

    protected $casts = [
        'is_indexable' => 'boolean',
        'is_followable' => 'boolean',
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'reading_time' => 'integer',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper Methods

    /**
     * Check if post is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at !== null;
    }

    /**
     * Check if post is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if post is publicly visible
     */
    public function isVisible(): bool
    {
        return $this->isPublished() && !$this->trashed();
    }

    /**
     * Get the URL path for this post
     */
    public function getUrl(): string
    {
        return route('posts.show', $this->slug);
    }

    /**
     * Check if post has featured image
     */
    public function hasFeaturedImage(): bool
    {
        return $this->featured_image !== null;
    }

    /**
     * Check if post is indexable by search engines
     */
    public function isIndexable(): bool
    {
        return $this->is_indexable === true && $this->isPublished();
    }

    /**
     * Check if post is followable by search engines
     */
    public function isFollowable(): bool
    {
        return $this->is_followable === true;
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('view_count');
    }

    /**
     * Get the reading time in a human-readable format
     */
    public function getReadingTimeFormatted(): string
    {
        if (!$this->reading_time) {
            return 'Unknown';
        }

        if ($this->reading_time < 1) {
            return 'Less than a minute';
        }

        return "{$this->reading_time} min read";
    }

    /**
     * Check if post is authored by user
     */
    public function isAuthoredBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Get time since post was published
     */
    public function timeSincePublished(): ?string
    {
        if ($this->published_at) {
            return $this->published_at->diffForHumans();
        }

        return null;
    }

    /**
     * Check if post has SEO meta data
     */
    public function hasSeoData(): bool
    {
        return $this->seo_title !== null || 
               $this->seo_description !== null || 
               $this->seo_keywords !== null;
    }

}

