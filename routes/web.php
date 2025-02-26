<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimeLineController;
use App\Http\Controllers\Post\ShowPostController;
use App\Http\Controllers\Post\StorePostController;
use App\Http\Controllers\Post\DeleteCommentController;
use App\Http\Controllers\Post\StoreCommentController;
use App\Http\Controllers\Post\EditCommentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return redirect()->route('login'); // Redirect ke halaman login
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Halaman utama setelah login
Route::get('/dashboard', [TimeLineController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route untuk postingan
Route::middleware('auth')->group(function () {
    Route::get('/posts', [AdminController::class, 'posts'])->name('posts.index');
    Route::post('/post', [StorePostController::class, 'store'])->name('post.store');
    Route::get('/create', [StorePostController::class, 'create'])->name('post.create');
    Route::get('/posts/{post}/edit', [StorePostController::class, 'edit'])->name('post.edit');
    Route::put('/posts/{post}', [StorePostController::class, 'update'])->name('post.update');
    Route::delete('/posts/{post}', [StorePostController::class, 'destroy'])->name('post.destroy');
    Route::post('/post/{post}/like', [StorePostController::class, 'like']);

    // Route untuk komentar
    Route::post('/post/{post}/comments', StoreCommentController::class)->name('post.comments.store');
    Route::delete('/post/{post}/comments/{comment}', DeleteCommentController::class)->name('post.comments.destroy');

    // Route untuk edit komentar (hanya pemilik komentar)
    Route::get('/post/{post}/comments/{comment}/edit', [EditCommentController::class, 'edit'])->name('post.comments.edit');
    Route::put('/post/{post}/comments/{comment}', [EditCommentController::class, 'update'])->name('post.comments.update');
});

// Route untuk menampilkan detail postingan
Route::get('/post/{post}', ShowPostController::class)->name('post.show');

// Route untuk profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.update.photo');
    Route::post('/profile/update-cover', [ProfileController::class, 'updateCoverPhoto'])->name('profile.update.cover');
});

// Auth routes
require __DIR__.'/auth.php';
