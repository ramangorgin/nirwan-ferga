<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'session_number',
        'session_date',
        'start_time',
        'end_time',
        'meeting_link',
        'status',
        'description',
        'has_materials'
    ];

    protected $casts = [
        'has_materials' => 'boolean',
        'session_date' => 'date',
        'session_number' => 'integer',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function sessionMaterials()
    {
        return $this->hasMany(SessionMaterial::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }

    // Helper Methods

    /**
     * Check if session is in the past
     */
    public function isPast(): bool
    {
        return now()->greaterThan($this->session_date->setTimeFromTimeString($this->end_time));
    }

    /**
     * Check if session is today
     */
    public function isToday(): bool
    {
        return $this->session_date->isToday();
    }

    /**
     * Check if session is upcoming
     */
    public function isUpcoming(): bool
    {
        return now()->lessThan($this->session_date->setTimeFromTimeString($this->start_time));
    }

    /**
     * Check if session has materials
     */
    public function hasMaterials(): bool
    {
        return $this->has_materials === true || $this->sessionMaterials()->exists();
    }

    /**
     * Get session duration in minutes
     */
    public function durationInMinutes(): int
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        return $end->diffInMinutes($start);
    }

    /**
     * Check if session has been held
     */
    public function isHeld(): bool
    {
        return $this->status === 'held';
    }

}
