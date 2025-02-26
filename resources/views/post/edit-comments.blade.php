<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Komentar
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-md rounded">
            <h3 class="text-lg font-semibold mb-4">Edit Komentar</h3>

            <!-- Alert jika ada pesan sukses -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Edit Komentar -->
            <form action="{{ route('post.comments.update', [$comment->post_id, $comment]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="body" class="block font-medium text-gray-700">Komentar:</label>
                    <textarea name="body" class="w-full p-2 border rounded @error('body') border-red-500 @enderror">{{ old('body', $comment->body) }}</textarea>
                    @error('body')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('post.show', $comment->post_id) }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
