<?php

namespace App\Http\Controllers\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Illuminate\Support\Facades\Storage;

class StorePostController extends Controller
{
    use AuthorizesRequests; 
    
    public function index()
    {
        $posts = Post::latest()->get();  // Get all posts in descending order of creation
        return view('post.index', compact('posts'));  // Pass the posts to the Blade view
    }
    
    

    public function create()
    {

        return view('post.index');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:140'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Tambahkan validasi foto
        ]);
    
        $photoPath = null;
    
        // Cek apakah ada file yang diunggah
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('posts', 'public'); // Simpan di storage/app/public/posts
        }
    
        Post::create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
            'photo' => $photoPath, // Simpan path foto ke database
        ]);
    
        return redirect()->route('dashboard')->with('success', 'Post berhasil dibuat!');
    }
    

    /**
     * Menampilkan form edit postingan
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post); // Pastikan hanya pemilik yang bisa edit
        return view('post.edit', compact('post')); // Harus sesuai dengan lokasi Blade
    }
    

    /**
     * Memperbarui postingan
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
    
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:140'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
    
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($post->photo) {
                Storage::disk('public')->delete($post->photo);
            }
            // Simpan foto baru
            $validated['photo'] = $request->file('photo')->store('posts', 'public');
        }
    
        $post->update($validated);
    
        return redirect()->route('dashboard')->with('success', 'Post berhasil diperbarui!');
    }
    

    /**
     * Menghapus postingan
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post); // ✅ Pastikan hanya pemilik bisa hapus

        $post->delete();

        return redirect()->route('dashboard')->with('success', 'Post berhasil dihapus!');
    }

    public function like(Post $post)
{
    $user = auth()->user();

    // Cek apakah user sudah like
    $existingLike = $post->likes()->where('user_id', $user->id)->first();

    if ($existingLike) {
        // Jika sudah like, maka unlike
        $existingLike->delete();
    } else {
        // Jika belum, maka like
        $post->likes()->create(['user_id' => $user->id]);
    }

    return response()->json(['likes' => $post->likes()->count()]);
}

}
