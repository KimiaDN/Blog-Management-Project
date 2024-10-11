<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class PostPolicy
{

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether a user limitation is exceeded or not
     */
    public function rateLimit(User $user): bool
    {
        if(RateLimiter::tooManyAttempts('post-publish:'.$user->id, $perDay = 5))
        {
            return false;
        }
        RateLimiter::hit('post-publish:'.$user->id, 86400);
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id == $post->user_id;
    }
}
