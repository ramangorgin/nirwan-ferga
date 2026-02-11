<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'title',
        'description',
        'type',
        'correct_answer',
        'options',
        'score',
        'deadline',
        'allow_late',
        'status'
    ];

    protected $casts = [
        'options' => 'array',
        'score' => 'integer',
        'deadline' => 'datetime',
        'allow_late' => 'boolean',
    ];


    public function session()
    {
        return $this->belongsTo(ClassSession::class, 'session_id');
    }

    public function personalized()
    {
        return $this->hasMany(AssignmentPersonalization::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    // Helper Methods

    /**
     * Check if assignment is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if assignment is closed
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Check if assignment is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if deadline has passed
     */
    public function isLate(): bool
    {
        return now()->greaterThan($this->deadline);
    }

    /**
     * Check if late submissions are allowed
     */
    public function allowsLate(): bool
    {
        return $this->allow_late === true;
    }

    /**
     * Check if assignment is multiple choice question
     */
    public function isMCQ(): bool
    {
        return $this->type === 'mcq';
    }

    /**
     * Check if assignment is text type
     */
    public function isText(): bool
    {
        return $this->type === 'text';
    }

    /**
     * Check if assignment is fill in blank
     */
    public function isFillBlank(): bool
    {
        return $this->type === 'fill_blank';
    }

    /**
     * Check if assignment is translation
     */
    public function isTranslation(): bool
    {
        return $this->type === 'translation';
    }

    /**
     * Check if assignment is file submission
     */
    public function isFile(): bool
    {
        return $this->type === 'file';
    }

}
