@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

<div class="container py-5">
    
    
    <div class="dashboard-header mb-5">
        <h1 class="dashboard-title">
            <span class="gradient-text">Browse Games</span>
        </h1>
        <p class="dashboard-subtitle">Discover and explore all available games</p>
    </div>

    
    <div class="games-grid">
        @forelse($games as $game)
        <div class="game-card">
            <div class="game-thumbnail">
                @if($game->image)
                    <img src="{{ asset('storage/' . $game->image) }}" alt="{{ $game->title }}">
                @else
                    <img src="https://images.unsplash.com/photo-{{ 1511512578047 + $loop->index * 100 }}?w=400&h=250&fit=crop" alt="{{ $game->title }}">
                @endif
                
                <div class="game-overlay">
                    <div class="quick-actions">
                        @auth
                        <button class="action-btn" title="Add to Wishlist">
                            <i class="far fa-heart"></i>
                        </button>
                        @endauth
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
                
                <h3 class="game-title">
                    <a href="{{ route('games.show', $game) }}" style="text-decoration: none; color: inherit;">
                        {{ $game->title }}
                    </a>
                </h3>
                
                <p class="game-description">{{ Str::limit($game->description, 80) }}</p>
                
                <div class="game-footer">
                    <div class="rating-box">
                        <div class="stars">
                            @php
                                $avgRating = $game->reviews->avg('rating') ?? 0;
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= round($avgRating) ? 'active' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="rating-text">
                            {{ $avgRating > 0 ? number_format($avgRating, 1) : 'New' }}
                        </span>
                    </div>
                    
                    <div class="price-box">
                        @if($game->price == 0 || $game->price === null)
                            <span class="new-price" style="color: #22c55e;">FREE</span>
                        @else
                            <span class="new-price">${{ number_format($game->price, 2) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-gamepad"></i>
            <h3>No Games Available</h3>
            <p>Check back soon for new releases!</p>
        </div>
        @endforelse
    </div>

    
    @if($games->hasPages())
    <div class="mt-5 d-flex justify-content-center">
        {{ $games->links() }}
    </div>
    @endif

</div>

@endsection