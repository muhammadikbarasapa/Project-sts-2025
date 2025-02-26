<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class TimeLineController extends Controller
{
    /**
     * Menampilkan halaman timeline dengan daftar postingan.
     */
    public function index()
    {
        return view('dashboard', [
            'posts' => Post::with('user')
                ->withCount('comments')
                ->latest('id')
                ->whereNull('deleted_at') // Hanya ambil postingan yang belum dihapus
                ->get(),
        ]);
    }
    
}
