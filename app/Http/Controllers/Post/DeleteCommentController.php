<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 🔹 Tambahkan ini

class DeleteCommentController extends Controller
{
    use AuthorizesRequests; // 🔹 Tambahkan ini agar bisa pakai $this->authorize()

    public function __invoke(Request $request, Post $post, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();
        
        session()->flash('success', 'Komentar berhasil dihapus');

        return redirect()->back();
    }
}
