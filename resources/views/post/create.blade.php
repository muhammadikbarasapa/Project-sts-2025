<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('post.store') }}" method="GET" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="body" class="block font-medium text-gray-700">Body:</label>
                            <textarea name="body"
                                class="w-full block rounded textarea textarea-bordered @error('body') textarea-error @enderror"
                                placeholder="Write your post"></textarea>

                            @error('body')
                                <span class="text-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="photo" class="block font-medium text-gray-700">Upload Photo:</label>
                            <input type="file" name="photo" class="w-full block rounded">
                        </div>

                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>

                    <!-- Daftar Komentar -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-3">Komentar</h3>

                        @if(isset($posts) && $posts->count() > 0)
                            @foreach ($posts as $post)
                                @foreach ($post->comments as $comment)
                                    <div class="card bg-base-100 w-full shadow-xl my-4">
                                        <div class="card-body">
                                            <h2 class="card-title flex justify-between items-center">
                                                <span>
                                                    {{ optional($comment->user)->name }}
                                                    <span class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                                </span>

                                                <div class="flex items-center gap-2">
                                                    @can('update', $comment)
                                                        <a href="{{ route('post.comments.edit', [$post, $comment]) }}"
                                                            class="btn btn-warning btn-sm">Edit</a>
                                                    @endcan

                                                    @can('delete', $comment)
                                                        <form action="{{ route('post.comments.destroy', [$post, $comment]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Yakin ingin menghapus komentar ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-error btn-sm text-white">Delete</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </h2>
                                            <p>{{ $comment->body }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        @else
                            <p class="text-gray-500">Belum ada komentar.</p>
                        @endif

                    </div>

                    <div class="flex justify-end mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>