<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class EditCommentController extends Controller
{
    public function edit(Post $post, Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'Anda tidak bisa mengedit komentar orang lain.');
        }

        return view('post.edit-comments', compact('post', 'comment'));
    }

    public function update(Request $request, Post $post, Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'Anda tidak bisa mengedit komentar orang lain.');
        }

        $request->validate([
            'body' => ['required', 'string', 'max:140'],
        ]);

        $comment->update(['body' => $request->body]);

        return redirect()->route('post.show', $post->id)->with('success', 'Komentar berhasil diperbarui!');
    }
}
