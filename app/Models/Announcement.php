<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'body',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'announcement_course');
    }

    // Helper Methods

    /**
     * Check if announcement is public
     */
    public function isPublic(): bool
    {
        return $this->is_public === true;
    }

    /**
     * Check if announcement is course specific
     */
    public function isCourseSpecific(): bool
    {
        return $this->is_public === false;
    }

    /**
     * Check if announcement is authored by user
     */
    public function isAuthoredBy(User $user): bool
    {
        return $this->author_id === $user->id;
    }

    /**
     * Get the number of courses this announcement is associated with
     */
    public function courseCount(): int
    {
        return $this->courses()->count();
    }

    /**
     * Check if announcement is targeted to specific course
     */
    public function isTargetedToCourse(Course $course): bool
    {
        return $this->courses()->where('courses.id', $course->id)->exists();
    }

}
