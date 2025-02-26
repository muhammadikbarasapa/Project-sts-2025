<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Notifications\PostCommented;

class StoreCommentController extends Controller
{
    public function __invoke(Request $request, Post $post)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:140'],
        ]);
        
        // Gunakan $request->user()->id, bukan $request->user()->id()
        $data['user_id'] = $request->user()->id;

        // Simpan komentar
        $comment = $post->comments()->create($data);

        // Kirim notifikasi ke pemilik post
        $post->user->notify(new PostCommented($comment));

        return redirect()->back()->with('success', 'Komentar berhasil dibuat!');
    }
}
