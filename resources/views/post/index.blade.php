<x-app-layout>
    <div class="container py-4">
        <h3 class="fw-bold mb-4">Semua Post</h3>
        <a href="{{ route('post.create') }}" class="btn btn-primary mb-4">
            <i class="fas fa-plus me-2"></i>Buat Post Baru
        </a>

        @foreach ($posts as $post)
            <div class="card shadow-sm mb-4 border-0 rounded-lg post-card">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="avatar bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-3"
                            style="width: 45px; height: 45px; font-weight: 600;">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $post->user->name }}</h6>
                            <p class="text-muted small mb-0">
                                <i class="far fa-clock me-1"></i>{{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Edit</a></li>
                            <li><a class="dropdown-item text-danger" href="#">Hapus</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text mb-4">{{ $post->body }}</p>

                    @if ($post->photo)
                        <div class="post-image mb-3 text-center">
                            <img src="{{ asset('storage/' . $post->photo) }}" class="img-fluid rounded-3 shadow-sm"
                                alt="Post image" onclick="openImageModal('{{ asset('storage/' . $post->photo) }}')">
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <button onclick="likePost({{ $post->id }})" id="like-btn-{{ $post->id }}"
                            class="btn btn-light flex-grow-1 mx-1 d-flex align-items-center justify-content-center {{ $post->user_liked ? 'text-primary active' : '' }}">
                            <i class="{{ $post->user_liked ? 'fas' : 'far' }} fa-thumbs-up me-2"
                                id="like-icon-{{ $post->id }}"></i>
                            <span>Suka</span>
                            <span id="like-count-{{ $post->id }}" class="ms-2">{{ $post->likes_count }}</span>
                        </button>

                        <a href="{{ route('post.show', $post->id) }}"
                            class="btn btn-light flex-grow-1 mx-1 d-flex align-items-center justify-content-center">
                            <i class="far fa-comment me-2"></i>
                            <span>Komentar</span>
                        </a>

                        <button class="btn btn-light flex-grow-1 mx-1 d-flex align-items-center justify-content-center">
                            <i class="far fa-share me-2"></i>
                            <span>Bagikan</span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notifikasi</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .post-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .avatar {
            font-size: 1.2rem;
        }

        .btn-light:hover {
            background-color: #f8f9fa;
        }

        .toast {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
        }
    </style>

    <script>
        // Fungsi untuk membuka modal gambar
        function openImageModal(imageSrc) {
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        }

        // Fungsi untuk like post dengan AJAX
        async function likePost(postId) {
            const likeBtn = document.getElementById(`like-btn-${postId}`);
            const likeIcon = document.getElementById(`like-icon-${postId}`);
            const likeCount = document.getElementById(`like-count-${postId}`);

            likeBtn.disabled = true;
            likeIcon.classList.replace('far', 'fas');

            try {
                const response = await fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                });

                const data = await response.json();

                if (data.success) {
                    likeCount.textContent = data.likes_count;
                    likeBtn.classList.toggle('text-primary', data.liked);
                    showToast(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Terjadi kesalahan. Silakan coba lagi.');
            } finally {
                likeBtn.disabled = false;
            }
        }

        // Fungsi untuk menampilkan toast notification
        function showToast(message) {
            const toast = new bootstrap.Toast(document.getElementById('toast'));
            document.querySelector('.toast-body').textContent = message;
            toast.show();
        }
    </script>
</x-app-layout>