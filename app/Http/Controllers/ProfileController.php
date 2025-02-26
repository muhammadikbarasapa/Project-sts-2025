<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
    
        // Ambil semua postingan user (termasuk yang tidak ada gambar)
        $posts = $user->posts()->latest()->get();
    
        return view('profile.edit', compact('user', 'posts'));
    }
    
    

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'Profile updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Hapus foto profil dan cover sebelum menghapus user
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        if ($user->cover_photo) {
            Storage::disk('public')->delete($user->cover_photo);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update Profile Photo
     */
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Simpan foto baru
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->profile_photo = $path;
        $user->save();

        return back()->with('success', 'Profile photo updated successfully.');
    }

    /**
     * Update Cover Photo
     */
    public function updateCoverPhoto(Request $request)
    {
        $request->validate([
            'cover_photo' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->cover_photo) {
            Storage::disk('public')->delete($user->cover_photo);
        }

        // Simpan foto baru
        $path = $request->file('cover_photo')->store('cover-photos', 'public');
        $user->cover_photo = $path;
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Cover photo updated successfully.');
    }
}

