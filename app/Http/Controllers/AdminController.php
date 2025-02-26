<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display the admin's posts.
     */
    public function posts(Request $request)
    {
        if ($request->ajax()) {
            $posts = Post::with('user')->select(['id', 'user_id', 'body', 'photo', 'created_at']);
    
            return DataTables::of($posts)
                ->addIndexColumn()
                ->addColumn('user_name', function ($post) {
                    return $post->user ? $post->user->name : 'Unknown';
                })
                ->addColumn('photo', function ($post) {
                    return $post->photo ? asset('storage/' . $post->photo) : null;
                })
                ->make(true);
        }
    
        return view('admin.posts.index');
    }
    
    

    /**
     * Show a single post.
     */
    public function show($id)
    {
        $post = Post::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('admin.posts.show', compact('post'));
    }
}
