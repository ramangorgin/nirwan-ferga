<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\DiscountCode;
use App\Models\User;

class DiscountCodePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === 'admin' || $user->role === 'teacher') {
            return true;
        }

        return null;
    }

}
