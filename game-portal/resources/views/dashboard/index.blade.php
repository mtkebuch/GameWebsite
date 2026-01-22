@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="container py-5">

   
    <div class="dashboard-layout">
        
       
        <aside class="dashboard-sidebar">
            <nav class="sidebar-nav">
                <a href="{{ route('user.library') }}" class="nav-item">
                    <span class="nav-label">My Library</span>
                    <span class="nav-count">{{ $libraryCount ?? 0 }}</span>
                </a>
                
                <a href="{{ route('user.wishlist') }}" class="nav-item">
                    <span class="nav-label">Wishlist</span>
                    <span class="nav-count">{{ $wishlistCount ?? 0 }}</span>
                </a>
                
                <a href="{{ route('user.reviews') }}" class="nav-item">
                    <span class="nav-label">My Reviews</span>
                    <span class="nav-count">{{ $stats['total_reviews'] }}</span>
                </a>
                
                <a href="{{ route('games.index') }}" class="nav-item">
                    <span class="nav-label">Browse Games</span>
                </a>
            </nav>
            
            
            <div class="stats-card">
                <div class="stats-card-title">Your Stats</div>
                <div class="stats-grid">
                    <div class="stat-mini">
                        <span class="stat-mini-value">{{ $stats['total_reviews'] }}</span>
                        <span class="stat-mini-label">Reviews</span>
                    </div>
                    <div class="stat-mini">
                        <span class="stat-mini-value">{{ number_format($stats['average_rating'], 1) }}</span>
                        <span class="stat-mini-label">Avg Rating</span>
                    </div>
                </div>
            </div>
        </aside>

      
        <main class="dashboard-content">
            
           
            @if($stats['recent_reviews']->count() > 0)
            <div class="content-section mb-4">
                <div class="section-header">
                    <h2 class="section-title">Recent Activity</h2>
                    <a href="{{ route('user.reviews') }}" class="section-link">View All</a>
                </div>
                
                <div class="reviews-list">
                    @foreach($stats['recent_reviews'] as $review)
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
                                <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if($review->comment)
                            <p class="review-text">{{ Str::limit($review->comment, 100) }}</p>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            
            <div class="content-section">
                <div class="section-header">
                    <h2 class="section-title">Recommended For You</h2>
                    <a href="{{ route('games.index') }}" class="section-link">Browse All</a>
                </div>
                
                <div class="games-grid">
                    @foreach($recommendedGames as $game)
                    <a href="{{ route('games.show', $game) }}" class="game-card">
                        <div class="game-image">
                            @if($game->image)
                                <img src="{{ asset('storage/' . $game->image) }}" alt="{{ $game->title }}">
                            @else
                                <img src="https://images.unsplash.com/photo-{{ 1511512578047 + $loop->index * 100 }}?w=400&h=250&fit=crop" alt="{{ $game->title }}">
                            @endif
                        </div>
                        <div class="game-info">
                            <h4 class="game-title">{{ $game->title }}</h4>
                            <span class="game-rating">{{ $game->reviews_avg_rating ? number_format($game->reviews_avg_rating, 1) : 'New' }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            
        </main>
        
    </div>

</div>
@endsection