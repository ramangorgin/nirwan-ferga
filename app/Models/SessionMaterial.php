<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'file_path',
        'file_type',
        'title',
        'description',
        'uploaded_by',
        'visibility'
    ];

    protected $casts = [
        'file_type' => 'string',
        'visibility' => 'string',
    ];

    public function session()
    {
        return $this->belongsTo(ClassSession::class, 'session_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Helper Methods

    /**
     * Check if material is video
     */
    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }

    /**
     * Check if material is audio
     */
    public function isAudio(): bool
    {
        return $this->file_type === 'audio';
    }

    /**
     * Check if material is PDF
     */
    public function isPDF(): bool
    {
        return $this->file_type === 'pdf';
    }

    /**
     * Check if material is image
     */
    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }

    /**
     * Check if material is slides
     */
    public function isSlides(): bool
    {
        return $this->file_type === 'slides';
    }

    /**
     * Check if material is public
     */
    public function isPublic(): bool
    {
        return $this->visibility === 'public';
    }

    /**
     * Check if material is for students only
     */
    public function isStudentsOnly(): bool
    {
        return $this->visibility === 'students_only';
    }

    /**
     * Check if material is hidden
     */
    public function isHidden(): bool
    {
        return $this->visibility === 'hidden';
    }

    /**
     * Check if user can view material
     */
    public function canViewBy(User $user): bool
    {
        if ($this->isPublic()) {
            return true;
        }

        if ($this->isHidden()) {
            return false;
        }

        if ($this->isStudentsOnly()) {
            return $this->session->course->isEnrolled($user);
        }

        return false;
    }
}

