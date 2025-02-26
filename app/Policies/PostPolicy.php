<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Hanya pemilik postingan yang dapat mengedit
     */
    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }

    /**
     * Hanya pemilik postingan yang dapat menghapus
     */
    public function delete(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }
}
