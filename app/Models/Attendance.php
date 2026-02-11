<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'student_id',
        'status',
        'note'
    ];

    public function student()
    {
        return $this->belongsTo(User::class , 'student_id');
    }

    public function session()
    {
        return $this->belongsTo(ClassSession::class, 'session_id');
    }

    // Helper Methods

    /**
     * Check if student is marked present
     */
    public function isPresent(): bool
    {
        return $this->status === 'present';
    }

    /**
     * Check if student is marked absent
     */
    public function isAbsent(): bool
    {
        return $this->status === 'absent';
    }

    /**
     * Check if student is marked late
     */
    public function isLate(): bool
    {
        return $this->status === 'late';
    }

    /**
     * Check if student is excused
     */
    public function isExcused(): bool
    {
        return $this->status === 'excused';
    }

}
