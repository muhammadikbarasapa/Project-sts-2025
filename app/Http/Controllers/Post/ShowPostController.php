<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowPostController extends Controller
{
    public function __invoke(Request $request, Post $post)
    {
        return view('post.show', [
            'post' => $post->load(['comments.user', 'user']) // Pastikan relasi di-load
        ]);
    }
}

