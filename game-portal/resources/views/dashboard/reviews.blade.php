@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="container py-5">
    
  
    <div class="dashboard-header mb-5">
        <h1 class="dashboard-title">
            <span class="gradient-text">My Reviews</span>
        </h1>
        <p class="dashboard-subtitle" id="reviewsSubtitle">{{ $myReviews->total() }} total reviews</p>
    </div>

    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

  
    <div class="dashboard-layout">
        
       
        <aside class="dashboard-sidebar">
            <nav class="sidebar-nav">
                <a href="{{ route('user.library') }}" class="nav-item">
                    <span class="nav-label">My Library</span>
                </a>
                
                <a href="{{ route('user.wishlist') }}" class="nav-item">
                    <span class="nav-label">Wishlist</span>
                </a>
                
                <a href="{{ route('user.reviews') }}" class="nav-item" style="background: rgba(96, 165, 250, 0.1); border-color: rgba(96, 165, 250, 0.3); color: #60a5fa;">
                    <span class="nav-label">My Reviews</span>
                    <span class="nav-count" id="reviewsCount">{{ $myReviews->total() }}</span>
                </a>
                
                <a href="{{ route('games.index') }}" class="nav-item">
                    <span class="nav-label">Browse Games</span>
                </a>
            </nav>
        </aside>

      
        <main class="dashboard-content">
            
            <div class="content-section">
                @if($myReviews->count() > 0)
                    <div class="reviews-list" id="reviewsList">
                        @foreach($myReviews as $review)
                        @if($review->game)
                        <div class="review-card-wrapper" id="review-{{ $review->id }}">
                            <a href="{{ route('games.show', $review->game) }}" class="review-card">
                                <div class="review-thumb">
                                    @if($review->game->image)
                                        <img src="{{ asset('storage/' . $review->game->image) }}" alt="{{ $review->game->title }}">
                                    @else
                                        <img src="https://via.placeholder.com/80x80?text=Game" alt="{{ $review->game->title }}">
                                    @endif
                                </div>
                                <div class="review-content">
                                    <h4 class="review-title">{{ $review->game->title }}</h4>
                                    <div class="review-meta">
                                        <span class="review-rating">{{ $review->rating }}/5</span>
                                        <span class="review-date">{{ $review->created_at?->diffForHumans() ?? 'Recently' }}</span>
                                    </div>
                                    @if($review->comment)
                                    <p class="review-text">{{ Str::limit($review->comment, 150) }}</p>
                                    @endif
                                </div>
                            </a>
                            <button type="button" class="btn-remove" data-review-id="{{ $review->id }}" data-game-title="{{ $review->game->title }}" title="Delete review">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @else
                        <div class="review-card" style="opacity: 0.5; cursor: default;" id="review-{{ $review->id }}">
                            <div class="review-thumb">
                                <img src="https://via.placeholder.com/80x80?text=Deleted" alt="Deleted Game">
                            </div>
                            <div class="review-content">
                                <h4 class="review-title">[Deleted Game]</h4>
                                <div class="review-meta">
                                    <span class="review-rating">{{ $review->rating }}/5</span>
                                    <span class="review-date">{{ $review->created_at?->diffForHumans() ?? 'Recently' }}</span>
                                </div>
                                @if($review->comment)
                                <p class="review-text">{{ Str::limit($review->comment, 150) }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    
                    
                    <div class="mt-4">
                        {{ $myReviews->links() }}
                    </div>
                @else
                    <div style="text-align: center; padding: 4rem 2rem;" id="emptyState">
                        <div style="font-size: 4rem; margin-bottom: 1rem;"></div>
                        <h2 style="color: #fff; font-size: 1.5rem; margin-bottom: 1rem;">Your Reviews</h2>
                        <p style="color: rgba(255, 255, 255, 0.6); margin-bottom: 2rem;">You haven't written any reviews yet</p>
                        <a href="{{ route('games.index') }}" class="btn btn-primary">Browse Games</a>
                    </div>
                @endif
            </div>
            
        </main>
        
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  
    const removeButtons = document.querySelectorAll('.btn-remove');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const reviewId = this.dataset.reviewId;
            const gameTitle = this.dataset.gameTitle;
            const reviewCard = document.getElementById('review-' + reviewId);
            
            
            if (!confirm(`Are you sure you want to delete your review for "${gameTitle}"?`)) {
                return;
            }
            
           
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
           
            fetch(`/reviews/${reviewId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                  
                    reviewCard.style.transition = 'all 0.3s ease';
                    reviewCard.style.opacity = '0';
                    reviewCard.style.transform = 'scale(0.95)';
                    
                    setTimeout(() => {
                        reviewCard.remove();
                        
                        
                        const countElement = document.getElementById('reviewsCount');
                        const subtitleElement = document.getElementById('reviewsSubtitle');
                        
                        if (countElement) {
                            const currentCount = parseInt(countElement.textContent);
                            const newCount = currentCount - 1;
                            countElement.textContent = newCount;
                            
                            if (subtitleElement) {
                                subtitleElement.textContent = `${newCount} total review${newCount !== 1 ? 's' : ''}`;
                            }
                        }
                        
                       
                        const reviewsList = document.getElementById('reviewsList');
                        if (reviewsList && reviewsList.children.length === 0) {
                            reviewsList.innerHTML = `
                                <div style="text-align: center; padding: 4rem 2rem;" id="emptyState">
                                    <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
                                    <h2 style="color: #fff; font-size: 1.5rem; margin-bottom: 1rem;">Your Reviews</h2>
                                    <p style="color: rgba(255, 255, 255, 0.6); margin-bottom: 2rem;">You haven't written any reviews yet</p>
                                    <a href="{{ route('games.index') }}" class="btn btn-primary">Browse Games</a>
                                </div>
                            `;
                        }
                        
                       
                        showNotification(data.message || 'Review deleted successfully', 'success');
                    }, 300);
                } else {
                    showNotification(data.message || 'Failed to delete review', 'error');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-trash"></i>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-trash"></i>';
            });
        });
    });
    
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            background: ${type === 'success' ? 'rgba(34, 197, 94, 0.9)' : 'rgba(239, 68, 68, 0.9)'};
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
    `;
    document.head.appendChild(style);
});
</script>
@endpush