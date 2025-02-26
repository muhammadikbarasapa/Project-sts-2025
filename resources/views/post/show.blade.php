<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Post Detail
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert untuk pesan sukses -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Post Detail -->
            <div class="card bg-base-100 w-full shadow-xl my-4">
                <div class="card-body">
                    <h2 class="card-title">{{ $post->user->name }}  
                        <span class="text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                    </h2>
                    <p>{{ $post->body }}</p>
                </div>
            </div>

            <!-- Form Komentar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('post.comments.store', $post) }}" method="POST">
                        @csrf
                        <textarea name="body" class="w-full block rounded textarea textarea-bordered @error('body')
                        textarea-error @enderror" placeholder="Write your comment"></textarea>
                        @error('body')
                            <span class="text-error">{{ $message }}</span>
                        @enderror
                        <input type="submit" value="Post Comment" class="btn mt-2">
                    </form>                    
                </div>               
            </div> 
        </div>

        <!-- Daftar Komentar -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($post->comments as $comment)
                <div class="card bg-base-100 w-full shadow-xl my-4">
                    <div class="card-body">
                        <h2 class="card-title flex justify-between items-center">
                            <span>
                                {{ $comment->user->name }}  
                                <span class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </span>

                            <div class="flex items-center gap-2">
                                <!-- Tombol Edit (Hanya pemilik komentar) -->
                                @can('update', $comment)
                                <a href="{{ route('post.comments.edit', [$post, $comment]) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                            @endcan
                            

                                <!-- Tombol Delete (Hanya pemilik komentar) -->
                                @can('delete', $comment)
                                    <form action="{{ route('post.comments.destroy', [$post, $comment]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus komentar ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-error btn-sm text-white">Delete</button>
                                    </form>    
                                @endcan
                            </div>
                        </h2>
                        <p>{{ $comment->body }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
