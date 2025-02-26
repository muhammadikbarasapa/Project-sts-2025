<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Edit Your Post</h3>
                    
                    <form action="{{ route('post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Input teks -->
                        <textarea name="body" class="w-full block rounded textarea textarea-bordered @error('body') textarea-error @enderror">{{ old('body', $post->body) }}</textarea>
                        @error('body')
                            <span class="text-error">{{ $message }}</span>
                        @enderror

                        <!-- Menampilkan gambar yang sudah ada -->
                        @if ($post->photo)
                            <div class="mt-4">
                                <p class="text-gray-600 text-sm">Current Image:</p>
                                <img src="{{ asset('storage/' . $post->photo) }}" class="rounded-lg w-full max-w-md">
                            </div>
                        @endif

                        <!-- Input file untuk mengganti gambar -->
                        <div class="mt-4">
                            <label class="block text-gray-700">Replace Image (optional)</label>
                            <input type="file" name="photo" class="file-input file-input-bordered w-full" accept="image/*">
                            @error('photo')
                                <span class="text-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-between mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Post</button>
                        </div>
                    </form>                    
                </div>               
            </div>   
        </div>
    </div>
</x-app-layout>
