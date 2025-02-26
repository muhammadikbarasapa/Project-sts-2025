<x-app-layout>

    <div class="py-12">
        <div class="max-w-5xl mx-auto bg-white shadow sm:rounded-lg">
            <!-- Cover Photo Section -->
            <div class="relative">
                @if(Auth::user()->cover_photo)
                    <img src="{{ asset('storage/' . Auth::user()->cover_photo) }}" class="w-full h-64 object-cover rounded-t-lg">
                @else
                    <div class="w-full h-64 bg-gray-300 flex items-center justify-center rounded-t-lg">
                        <span class="text-gray-500">No Cover Photo</span>
                    </div>
                @endif

                <!-- Upload Cover Photo Button -->
                <form action="{{ route('profile.update.cover') }}" method="POST" enctype="multipart/form-data" class="absolute top-4 right-4">
                    @csrf
                    <label class="bg-black bg-opacity-50 text-white px-3 py-2 rounded-lg cursor-pointer">
                        Change Cover
                        <input type="file" name="cover_photo" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>

                <!-- Profile Photo -->
                <div class="absolute left-1/2 transform -translate-x-1/2 -bottom-12 text-center">
                    @if(Auth::user()->profile_photo)
                        <div class="relative w-28 h-28">
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-full h-full rounded-full border-4 border-white shadow-md">
                            <!-- Upload Profile Photo Button (Icon) -->
                            <form action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data" class="absolute bottom-1 right-1">
                                @csrf
                                <label class="bg-black bg-opacity-50 text-white p-2 rounded-full cursor-pointer">
                                    <i class="fa-solid fa-camera"></i>
                                    <input type="file" name="profile_photo" class="hidden" onchange="this.form.submit()">
                                </label>
                            </form>
                        </div>
                    @else
                        <div class="relative w-28 h-28 rounded-full bg-gray-400 flex items-center justify-center border-4 border-white shadow-md">
                            <span class="text-white">No Profile</span>
                            <!-- Upload Profile Photo Button (Icon) -->
                            <form action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data" class="absolute bottom-1 right-1">
                                @csrf
                                <label class="bg-black bg-opacity-50 text-white p-2 rounded-full cursor-pointer">
                                    <i class="fa-solid fa-camera"></i>
                                    <input type="file" name="profile_photo" class="hidden" onchange="this.form.submit()">
                                </label>
                            </form>
                        </div>
                    @endif
                    <h3 class="text-lg font-semibold mt-2">{{ Auth::user()->name }}</h3>
                </div>
            </div>

            <div class="mt-10">
                <h3 class="text-xl font-semibold mb-5 ml-5">Postingan Anda</h3>
                
                @foreach ($posts as $post)
                    <div class="border rounded-lg p-4 shadow mb-4 relative">
                        <div class="flex items-center gap-2">
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="w-8 h-8 rounded-full">
                            <span class="font-bold">{{ Auth::user()->name }}</span>
                            <span class="text-gray-500 text-sm">{{ $post->created_at->diffForHumans() }}</span>
                        </div>

                        <p class="mt-2">{{ $post->body }}</p>

                        @if ($post->photo)
                            <img src="{{ asset('storage/' . $post->photo) }}" class="max-w-xs h-auto rounded-lg mt-2">
                        @endif

                        <!-- Edit and Delete Buttons -->
                        <div class="absolute top-2 right-2 flex gap-2">
                            <a href="{{ route('post.edit', $post->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded-lg text-sm">Edit</a>
                            <form action="{{ route('post.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg text-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach

                @if ($posts->isEmpty())
                    <p class="text-gray-500">Anda belum memiliki postingan.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
