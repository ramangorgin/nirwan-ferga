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
        'reading_time',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_indexable' => 'boolean',
        'is_followable' => 'boolean',
        'view_count' => 'integer',
        'reading_time' => 'integer',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Use slug in routes (Route Model Binding).
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Helpers
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
}