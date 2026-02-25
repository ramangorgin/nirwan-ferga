<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(?User $user): bool
    {
        // Public archive exists; admin listing exists too.
        return true;
    }

    public function view(?User $user, Post $post): bool
    {
        if ($post->isPublished()) return true;

        // Draft view: admin only
        return $user?->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Post $post): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->role === 'admin';
    }
}