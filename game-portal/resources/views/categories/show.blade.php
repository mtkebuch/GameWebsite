@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="container py-5">
    
    
    <div class="category-header text-center mb-5">
        <h1 class="hero-title mb-3">
            <span class="gradient-text">{{ $category->name }}</span> Games
        </h1>
        <p class="hero-description mx-auto">
            Discover {{ $games->total() }} amazing {{ strtolower($category->name) }} games
        </p>
    </div>

   
    @if($games->count() > 0)
    <section class="featured-games">
        <div class="games-grid">
            @foreach($games as $game)
            <div class="game-card">
                <div class="game-thumbnail">
                    @if($game->image)
                        <img src="{{ asset('storage/' . $game->image) }}" alt="{{ $game->title }}">
                    @else
                        <img src="https://images.unsplash.com/photo-{{ 1511512578047 + $loop->index * 100 }}?w=400&h=250&fit=crop" alt="{{ $game->title }}">
                    @endif

                    <div class="game-overlay">
                        <div class="quick-actions">
                            <button class="action-btn" title="Add to Wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                            <a href="{{ route('games.show', $game) }}" class="action-btn" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="game-info">
                    <div class="game-tags">
                        @foreach($game->categories->take(2) as $cat)
                        <span class="tag">{{ $cat->name }}</span>
                        @endforeach
                    </div>
                    
                    <h3 class="game-title">{{ $game->title }}</h3>
                    
                    <p class="game-description">{{ Str::limit($game->description, 80) }}</p>
                    
                    <div class="game-footer">
                        <div class="rating-box">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= (isset($game->reviews_avg_rating) ? round($game->reviews_avg_rating) : 0) ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-text">
                                {{ isset($game->reviews_avg_rating) ? number_format($game->reviews_avg_rating, 1) : 'New' }}
                            </span>
                        </div>
                        
                        <a href="{{ route('games.show', $game) }}" class="view-btn">
                            Details <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

     
        @if($games->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $games->links() }}
        </div>
        @endif
    </section>
    @else
    <div class="empty-state">
        <i class="fas fa-gamepad empty-icon"></i>
        <h3>No Games Found</h3>
        <p>No games available in this category yet. Check back soon!</p>
        <a href="{{ route('home') }}" class="btn-primary-hero mt-4">
            Back to Home
        </a>
    </div>
    @endif

</div>
@endsection

@push('styles')
<style>
.category-header {
    position: relative;
    z-index: 1;
}

.hero-description {
    max-width: 600px;
}

.empty-icon {
    font-size: 4rem;
    opacity: 0.3;
}
</style>
@endpush