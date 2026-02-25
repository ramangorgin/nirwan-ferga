<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        // Admin sees all; students see their own via student controller filter
        return auth()->check();
    }

    public function view(User $user, Payment $payment): bool
    {
        if ($user->role === 'admin') return true;

        return (int) $payment->student_id === (int) $user->id;
    }

    public function create(User $user, Enrollment $enrollment): bool
    {
        if ($user->role === 'admin') return true;

        // Students can upload payment for their own enrollment
        return (int) $enrollment->student_id === (int) $user->id;
    }

    public function review(User $user, Payment $payment): bool
    {
        return $user->role === 'admin';
    }
}