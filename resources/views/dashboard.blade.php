<x-app-layout>
    <div class="py-5 bg-light">
        <div class="container">
            <!-- Post creation card -->
            <div class="card shadow-sm mb-4 border-0 rounded-lg">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">Timeline</h5>
                    <form action="{{ route('post.create') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm px-3">
                            <i class="fas fa-plus-circle me-2"></i>Buat Post Baru
                        </button>
                    </form>
                </div>

                <div class="card-body p-4">
                    <form id="postForm" action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data"
                        class="mb-0">
                        @csrf
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-3"
                                    style="width: 40px; height: 40px; font-weight: 600;">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <textarea name="body"
                                        class="form-control border-0 bg-light @error('body') is-invalid @enderror"
                                        rows="2" placeholder="Apa yang Anda pikirkan?"
                                        required>{{ old('body') }}</textarea>
                                    @error('body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="imagePreview" class="my-3 position-relative d-none">
                                <img src="" class="img-fluid rounded-3" alt="Preview">
                                <button type="button" id="removeImage"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle"
                                    style="width: 30px; height: 30px; padding: 0;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                <div>
                                    <label for="postImage"
                                        class="btn btn-light btn-sm d-inline-flex align-items-center me-2">
                                        <i class="fas fa-image text-success me-2"></i>Foto
                                    </label>
                                    <input type="file" id="postImage" name="photo" class="d-none" accept="image/*">
                                    @error('photo')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" id="submitPost" class="btn btn-primary px-4">Posting</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Posts feed -->
            <div class="posts-container">
                @foreach ($posts as $post)
                    <div class="card shadow-sm mb-4 post-card border-0 rounded-lg" id="post-{{ $post->id }}"
                        data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="card-header bg-white py-3">
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

                                @if(Gate::allows('update', $post) || Gate::allows('delete', $post))
                                    <div class="dropdown ms-auto">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false" style="width: 35px; height: 35px;">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            @can('update', $post)
                                                <li>
                                                    <a class="dropdown-item py-2" href="{{ route('post.edit', $post) }}">
                                                        <i class="fas fa-edit me-2 text-primary"></i>Edit
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('delete', $post)
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <button class="dropdown-item py-2 text-danger"
                                                        onclick="confirmDelete({{ $post->id }})">
                                                        <i class="fas fa-trash-alt me-2"></i>Delete
                                                    </button>
                                                    <form id="delete-form-{{ $post->id }}"
                                                        action="{{ route('post.destroy', $post) }}" method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <p class="card-text mb-4">{{ $post->body }}</p>

                            @if ($post->photo)
                                <div class="post-image mb-3 text-center">
                                    <img src="{{ asset('storage/' . $post->photo) }}" class="img-fluid rounded-3 shadow-sm"
                                        alt="Post image" onclick="openImageModal(this.src)">
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="small">
                                    <span id="likes-count-badge-{{ $post->id }}"
                                        class="bg-light px-2 py-1 rounded {{ $post->likes_count > 0 ? '' : 'd-none' }}">
                                        <i class="fas fa-thumbs-up text-primary"></i> <span
                                            id="likes-count-text-{{ $post->id }}">{{ $post->likes_count }}</span>
                                    </span>
                                </div>
                                <div class="small text-muted">
                                    <span id="comments-count-{{ $post->id }}"
                                        class="{{ $post->comments_count > 0 ? '' : 'd-none' }}">
                                        <span id="comments-count-text-{{ $post->id }}">{{ $post->comments_count }}</span>
                                        komentar
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white py-3">
                            <div class="d-flex justify-content-between">
                                <button onclick="likePost({{ $post->id }})" id="like-btn-{{ $post->id }}"
                                    class="btn btn-light flex-grow-1 mx-1 d-flex align-items-center justify-content-center {{ $post->user_liked ? 'text-primary active' : '' }}">
                                    <i class="{{ $post->user_liked ? 'fas' : 'far' }} fa-thumbs-up me-2"
                                        id="like-icon-{{ $post->id }}"></i>
                                    <span>Suka</span>
                                </button>

                                <a href="{{ route('post.show', $post->id) }}"
                                    class="btn btn-light flex-grow-1 mx-1 d-flex align-items-center justify-content-center">
                                    <i class="far fa-comment me-2"></i>
                                    <span>Komentar</span>
                                </a>

                                <button
                                    class="btn btn-light flex-grow-1 mx-1 d-flex align-items-center justify-content-center">
                                    <i class="far fa-share-square me-2"></i>
                                    <span>Bagikan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Load more button -->
            <div class="text-center mb-4">
                <button class="btn btn-outline-primary px-4" id="loadMoreBtn">
                    <i class="fas fa-sync-alt me-2"></i>Muat Lebih Banyak
                </button>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header border-0">
                            <h5 class="modal-title fw-bold">Hapus Post</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body py-4">
                            <div class="text-center mb-3">
                                <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                                <p class="mb-0">Apakah Anda yakin ingin menghapus post ini? Tindakan ini tidak dapat
                                    dibatalkan.</p>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Modal -->
            <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-body p-0 text-center">
                            <img src="" id="modalImage" class="img-fluid rounded" alt="Post image">
                            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap, FontAwesome & AOS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 680px;
        }

        .post-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .post-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .avatar {
            font-weight: bold;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .card-footer .btn {
            transition: all 0.2s ease;
            border-radius: 8px;
            font-weight: 500;
        }

        .card-footer .btn:hover {
            background-color: #e2e6ea;
            transform: translateY(-1px);
        }

        .card-footer .btn.active {
            background-color: rgba(24, 119, 242, 0.1);
        }

        .liked {
            color: #1877f2 !important;
        }

        #loadMoreBtn {
            transition: all 0.2s ease;
        }

        #loadMoreBtn:hover {
            background-color: #1877f2;
            color: white;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #1877f2;
        }

        .post-image img {
            cursor: pointer;
            transition: opacity 0.2s ease;
        }

        .post-image img:hover {
            opacity: 0.95;
        }

        /* For dark mode toggle (optional) */
        .dark-mode-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1877f2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 999;
        }
    </style>

    <script>
        // Initialize AOS animation
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true
            });
            
            // Form submission handling
            const postForm = document.getElementById('postForm');
            const submitBtn = document.getElementById('submitPost');

            if (postForm) {
                postForm.addEventListener('submit', function (e) {
                    const textarea = this.querySelector('textarea[name="body"]');
                    const fileInput = this.querySelector('input[name="photo"]');

                    // Basic validation
                    if (textarea.value.trim() === '') {
                        e.preventDefault();
                        showToast('Silakan masukkan teks untuk postingan Anda');
                        textarea.classList.add('is-invalid');
                        return false;
                    }

                    // Set loading state
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Posting...';
                    submitBtn.disabled = true;
                });
            }

            // Image preview functionality
            const postImage = document.getElementById('postImage');
            const imagePreview = document.getElementById('imagePreview');

            if (postImage && imagePreview) {
                const previewImg = imagePreview.querySelector('img');
                const removeImageBtn = document.getElementById('removeImage');

                postImage.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            previewImg.src = e.target.result;
                            imagePreview.classList.remove('d-none');
                        }

                        reader.readAsDataURL(this.files[0]);
                    }
                });

                if (removeImageBtn) {
                    removeImageBtn.addEventListener('click', function () {
                        postImage.value = '';
                        imagePreview.classList.add('d-none');
                        previewImg.src = '';
                    });
                }
            }
        });

        // Toast notification function
        function showToast(message, type = 'primary') {
            // Check if a toast container exists, if not create one
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }

            // Create the toast
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            const toast = new bootstrap.Toast(document.getElementById(toastId), {
                delay: 3000
            });
            toast.show();
        }

        // Like post functionality
        function likePost(postId) {
            const likeBtn = document.getElementById(`like-btn-${postId}`);
            const likeIcon = document.getElementById(`like-icon-${postId}`);
            const likesCountBadge = document.getElementById(`likes-count-badge-${postId}`);
            const likesCountText = document.getElementById(`likes-count-text-${postId}`);

            // Add loading state
            likeBtn.disabled = true;
            likeIcon.className = 'fas fa-spinner fa-spin me-2';

            fetch(`/post/${postId}/like`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({}),
            })
                .then(response => response.json())
                .then(data => {
                    // Update like count
                    likesCountText.innerText = data.likes;

                    // Show or hide the likes badge
                    if (data.likes > 0) {
                        likesCountBadge.classList.remove('d-none');
                    } else {
                        likesCountBadge.classList.add('d-none');
                    }

                    // Toggle liked state UI
                    if (data.liked) {
                        likeIcon.className = 'fas fa-thumbs-up me-2 liked';
                        likeBtn.classList.add('text-primary');
                        likeBtn.classList.add('active');

                        // Add like animation
                        const heart = document.createElement('div');
                        heart.innerHTML = '<i class="fas fa-heart text-danger"></i>';
                        heart.style.position = 'absolute';
                        heart.style.left = '50%';
                        heart.style.top = '50%';
                        heart.style.transform = 'translate(-50%, -50%)';
                        heart.style.fontSize = '50px';
                        heart.style.opacity = '0';
                        heart.style.transition = 'all 0.5s ease';
                        heart.style.zIndex = '1000';

                        likeBtn.style.position = 'relative';
                        likeBtn.appendChild(heart);

                        setTimeout(() => {
                            heart.style.opacity = '0.8';
                            heart.style.transform = 'translate(-50%, -50%) scale(1.5)';
                        }, 50);

                        setTimeout(() => {
                            heart.style.opacity = '0';
                            heart.style.transform = 'translate(-50%, -150%) scale(0.5)';
                        }, 500);

                        setTimeout(() => {
                            heart.remove();
                        }, 1000);
                    } else {
                        likeIcon.className = 'far fa-thumbs-up me-2';
                        likeBtn.classList.remove('text-primary');
                        likeBtn.classList.remove('active');
                    }

                    // Remove loading state
                    likeBtn.disabled = false;

                    // Show toast notification
                    showToast(data.liked ? 'You liked this post' : 'You unliked this post');
                })
                .catch(error => {
                    console.error('Error liking post:', error);
                    // Restore default state
                    likeIcon.className = 'far fa-thumbs-up me-2';
                    likeBtn.disabled = false;
                    showToast('Something went wrong. Please try again.');
                });
        }

        // Delete post functionality with confirmation
        let postIdToDelete = null;

        function confirmDelete(postId) {
            postIdToDelete = postId;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            if (postIdToDelete) {
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Deleting...';
                this.disabled = true;

                document.getElementById(`delete-form-${postIdToDelete}`).submit();
            }
        });

        // Open image modal
        function openImageModal(src) {
            const modalImage = document.getElementById('modalImage');
            modalImage.src = src;

            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        }
        
        // Load more functionality
        document.getElementById('loadMoreBtn').addEventListener('click', function() {
            const btn = this;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Loading...';
            btn.disabled = true;
            
            // Simulate loading delay (replace with actual AJAX call to load more posts)
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Muat Lebih Banyak';
                btn.disabled = false;
                
                // If no more posts, you could hide the button
                // btn.classList.add('d-none');
                // showToast('Tidak ada lagi postingan untuk dimuat', 'info');
            }, 1500);
        });
    </script>

    <!-- Optional floating-action-button for new post (mobile friendly) -->
    <div class="d-block d-md-none">
        <a href="{{ route('post.create') }}" class="btn btn-primary rounded-circle shadow position-fixed"
            style="bottom: 20px; right: 20px; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center; z-index: 999;">
            <i class="fas fa-plus fa-lg"></i>
        </a>
    </div>
</x-app-layout>