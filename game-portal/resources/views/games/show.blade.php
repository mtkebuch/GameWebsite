@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/game-detail.css') }}">
@endpush

@section('content')

<div class="game-detail-container">
    <div class="container">
        
       
        <nav class="game-breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">{{ $game->title }}</span>
        </nav>

        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

       
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        
        <div class="game-header-section">
            <div class="row">
                
                
                <div class="col-lg-5">
                    <div class="game-image-wrapper">
                        @if($game->image)
                            <img src="{{ asset('storage/' . $game->image) }}" alt="{{ $game->title }}" class="game-main-image">
                        @else
                            <div class="game-placeholder">
                                <i class="fas fa-gamepad"></i>
                                <p>No Image</p>
                            </div>
                        @endif
                    </div>
                </div>

              
                <div class="col-lg-7">
                    <div class="game-info-panel">
                        
                       
                        <h1 class="game-detail-title">{{ $game->title }}</h1>

                       
                        <div class="game-categories">
                            @foreach($game->categories as $category)
                                <a href="{{ route('categories.show', $category) }}" class="category-badge">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>

                       
                        <div class="game-rating-section">
                            <div class="rating-stars-large">
                                @php
                                    $avgRating = $game->reviews->avg('rating') ?? 0;
                                    $reviewCount = $game->reviews->count();
                                @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($avgRating) ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-value-large">{{ number_format($avgRating, 1) }}</span>
                            <span class="rating-count">({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})</span>
                        </div>

                      
                        <div class="game-price-section">
                            @if($game->price == 0 || $game->price === null)
                                <span class="price-free">FREE TO PLAY</span>
                            @else
                                <span class="price-value">${{ number_format($game->price, 2) }}</span>
                            @endif
                        </div>

                        
                        <div class="game-action-buttons">
                            @auth
                                <button class="btn-action btn-primary-action" id="addToLibraryBtn" data-game-id="{{ $game->id }}">
                                    <i class="fas fa-plus"></i>
                                    Add to Library
                                </button>
                                <button class="btn-action btn-secondary-action" id="addToWishlistBtn" data-game-id="{{ $game->id }}">
                                    <i class="far fa-heart"></i>
                                    Wishlist
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn-action btn-primary-action">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Login to Add
                                </a>
                            @endauth
                        </div>

                    </div>
                </div>

            </div>
        </div>

        
        <div class="game-description-section">
            <div class="section-header-detail">
                <h2 class="section-title-detail">About This Game</h2>
            </div>
            <div class="description-content">
                <p>{{ $game->description ?? 'No description available.' }}</p>
            </div>
        </div>

       
        <div class="game-reviews-section">
            <div class="section-header-detail">
                <h2 class="section-title-detail">Player Reviews</h2>
                @auth
                    <button class="btn-write-review" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        <i class="fas fa-edit"></i>
                        {{ $userReview ? 'Edit Your Review' : 'Write a Review' }}
                    </button>
                @endauth
            </div>

            <div class="reviews-list">
                @forelse($game->reviews as $review)
                    <div class="review-item-detail">
                        <div class="review-header-detail">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    {{ $review->user ? substr($review->user->name, 0, 1) : '?' }}
                                </div>
                                <div class="reviewer-details">
                                    <h4 class="reviewer-name">{{ $review->user->name ?? 'Unknown User' }}</h4>
                                    <span class="review-date">{{ $review->created_at?->diffForHumans() ?? 'Recently' }}</span>
                                </div>
                            </div>
                            <div class="review-rating-detail">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'filled' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="review-comment">{{ $review->comment }}</p>
                        
                        @auth
                            @if($review->user_id === auth()->id())
                                <div class="review-actions" style="margin-top: 10px;">
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                @empty
                    <div class="no-reviews">
                        <i class="fas fa-comments"></i>
                        <p>No reviews yet. Be the first to review this game!</p>
                    </div>
                @endforelse
            </div>
        </div>

       
        @if($relatedGames->count() > 0)
        <div class="related-games-section">
            <div class="section-header-detail">
                <h2 class="section-title-detail">You May Also Like</h2>
            </div>
            <div class="related-games-grid">
                @foreach($relatedGames as $relatedGame)
                    <a href="{{ route('games.show', $relatedGame) }}" class="related-game-card">
                        <div class="related-game-image">
                            @if($relatedGame->image)
                                <img src="{{ asset('storage/' . $relatedGame->image) }}" alt="{{ $relatedGame->title }}">
                            @else
                                <div class="related-placeholder">
                                    <i class="fas fa-gamepad"></i>
                                </div>
                            @endif
                        </div>
                        <div class="related-game-info">
                            <h4 class="related-game-title">{{ $relatedGame->title }}</h4>
                            <div class="related-game-rating">
                                @php
                                    $relatedAvg = $relatedGame->reviews_avg_rating ?? 0;
                                @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($relatedAvg) ? 'active' : '' }}"></i>
                                @endfor
                                <span>{{ number_format($relatedAvg, 1) }}</span>
                            </div>
                            @if($relatedGame->price == 0 || $relatedGame->price === null)
                                <span class="related-price-free">Free</span>
                            @else
                                <span class="related-price">${{ number_format($relatedGame->price, 2) }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>


@auth
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content review-modal-content">
            <div class="modal-header review-modal-header">
                <h5 class="modal-title" id="reviewModalLabel">
                    {{ $userReview ? 'Edit Your Review for' : 'Write a Review for' }} {{ $game->title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
           <form action="{{ $userReview ? route('reviews.update', $userReview) : route('reviews.store', $game) }}" method="POST" id="reviewForm">
    @csrf
    @if($userReview)
        @method('PATCH')
    @endif
    <input type="hidden" name="rating" id="ratingInput" value="{{ $userReview->rating ?? '' }}">
    
    <div class="modal-body review-modal-body">
        
        {{-- Rating stars --}}
        <div class="mb-4">
            <label class="form-label" style="color: rgba(255, 255, 255, 0.8); font-weight: 600; margin-bottom: 12px; display: block; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">
                Your Rating
            </label>
            <div class="star-rating-selector" id="starRating">
                <i class="fas fa-star" data-rating="1"></i>
                <i class="fas fa-star" data-rating="2"></i>
                <i class="fas fa-star" data-rating="3"></i>
                <i class="fas fa-star" data-rating="4"></i>
                <i class="fas fa-star" data-rating="5"></i>
            </div>
            <div id="ratingError" style="display: none; color: #ef4444; margin-top: 8px; font-size: 0.875rem;">
                Please select a rating
            </div>
        </div>

        {{-- Comment textarea --}}
        <div class="mb-4">
            <label for="comment" class="form-label" style="color: rgba(255, 255, 255, 0.8); font-weight: 600; margin-bottom: 12px; display: block; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">
                Your Review
            </label>
            <textarea name="comment" id="comment" class="form-control" rows="5" 
                      placeholder="Share your thoughts about this game..." required 
                      style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(96, 165, 250, 0.2); color: #fff; padding: 12px 16px; border-radius: 8px;">{{ $userReview->comment ?? '' }}</textarea>
        </div>

    </div>
    
    <div class="modal-footer review-modal-footer">
        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn-primary">
            {{ $userReview ? 'Update Review' : 'Submit Review' }}
        </button>
    </div>
</form>
        </div>
    </div>
</div>
@endauth

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @auth
    const addToLibraryBtn = document.getElementById('addToLibraryBtn');
    const addToWishlistBtn = document.getElementById('addToWishlistBtn');

    if (addToLibraryBtn) {
        addToLibraryBtn.addEventListener('click', function() {
            const gameId = this.dataset.gameId;
            const btn = this;
            
            fetch('{{ route("library.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ game_id: gameId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-check"></i> Added to Library';
                    btn.style.background = 'rgba(34, 197, 94, 0.2)';
                    btn.style.color = '#22c55e';
                    btn.disabled = true;
                    showNotification('Game added to your library!', 'success');
                } else {
                    showNotification(data.message, 'info');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            });
        });
    }

    if (addToWishlistBtn) {
        addToWishlistBtn.addEventListener('click', function() {
            const gameId = this.dataset.gameId;
            const btn = this;
            
            fetch('{{ route("wishlist.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ game_id: gameId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.innerHTML = '<i class="fas fa-heart"></i> In Wishlist';
                    btn.style.background = 'rgba(239, 68, 68, 0.2)';
                    btn.style.color = '#ef4444';
                    btn.disabled = true;
                    showNotification('Game added to your wishlist!', 'success');
                } else {
                    showNotification(data.message, 'info');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            });
        });
    }

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            background: ${type === 'success' ? 'rgba(34, 197, 94, 0.9)' : type === 'error' ? 'rgba(239, 68, 68, 0.9)' : 'rgba(96, 165, 250, 0.9)'};
            color: white;
            border-radius: 12px;
            font-weight: 600;
            z-index: 10000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    @endauth

   
    const modalElement = document.getElementById('reviewModal');
    
    if (modalElement) {
        modalElement.addEventListener('shown.bs.modal', function() {
            initStarRating();
        });
    }

    function initStarRating() {
        const stars = document.querySelectorAll('#starRating i');
        const ratingInput = document.getElementById('ratingInput');
        const ratingError = document.getElementById('ratingError');
        const reviewForm = document.getElementById('reviewForm');

        if (!stars.length || !ratingInput) return;

        
        const initialRating = parseInt(ratingInput.value) || 0;
        if (initialRating > 0) {
            updateStars(initialRating);
        }

       
        stars.forEach(function(star) {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                ratingInput.value = rating;
                updateStars(rating);
                if (ratingError) ratingError.style.display = 'none';
            });

            
            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                updateStars(rating, true);
            });
        });

       
        const starContainer = document.getElementById('starRating');
        if (starContainer) {
            starContainer.addEventListener('mouseleave', function() {
                const currentRating = parseInt(ratingInput.value) || 0;
                updateStars(currentRating);
            });
        }

        function updateStars(rating, isHover) {
            stars.forEach(function(star, index) {
                star.classList.remove('selected', 'hover');
                if (index < rating) {
                    star.classList.add(isHover ? 'hover' : 'selected');
                }
            });
        }

        
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                if (!ratingInput.value || ratingInput.value === '0') {
                    e.preventDefault();
                    if (ratingError) ratingError.style.display = 'block';
                    return false;
                }
            });
        }
    }

    
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
        
        .modal {
            background: rgba(0, 0, 0, 0.7) !important;
        }

        .star-rating-selector {
            display: flex;
            gap: 8px;
            font-size: 2.5rem;
        }

        .star-rating-selector i {
            color: rgba(96, 165, 250, 0.3);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .star-rating-selector i:hover,
        .star-rating-selector i.hover {
            color: #60a5fa;
            transform: scale(1.1);
        }

        .star-rating-selector i.selected {
            color: #60a5fa;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush