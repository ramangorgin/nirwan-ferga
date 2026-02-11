<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_id',
        'status',
        'payment_status',
        'paid_amount',
        'payment_screenshot_path',
        'final_score',
        'certificate_issued',
        'discount_code_id',
        'enrolled_at'
    ];

    protected $casts = [
        'paid_amount' => 'integer',
        'final_score' => 'integer',
        'certificate_issued' => 'boolean',
        'enrolled_at' => 'datetime',
        'payment_status' => 'string',
        'status' => 'string',

    ];

    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class, 'discount_code_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Helper Methods

    /**
     * Check if enrollment belongs to user
     */
    public function belongsToUser(User $user): bool
    {
        return $this->student_id === $user->id;
    }

    /**
     * Check if payment is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is rejected
     */
    public function isRejected(): bool
    {
        return $this->payment_status === 'rejected';
    }

    /**
     * Check if enrollment has discount
     */
    public function hasDiscount(): bool
    {
        return $this->discount_code_id !== null;
    }

    /**
     * Calculate final price after discount
     */
    public function finalPrice(): int
    {
        $coursePrice = $this->course->price;
        
        if ($this->hasDiscount() && $this->discountCode) {
            $discount = ($coursePrice * $this->discountCode->percentage) / 100;
            return intval($coursePrice - $discount);
        }
        
        return $coursePrice;
    }

    /**
     * Check if enrollment is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if enrollment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if enrollment has certificate
     */
    public function hasCertificate(): bool
    {
        return $this->certificate_issued === true;
    }

}
