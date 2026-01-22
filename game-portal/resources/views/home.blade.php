@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">

            
            <div class="col-lg-6">
                <div class="hero-content">

                    <div class="sale-banner">
                        <i class="fas fa-snowflake"></i>
                        Winter Sale – Up to 70% OFF
                        <i class="fas fa-snowflake"></i>
                    </div>

                    <h1 class="hero-title">
                        YOUR GAMING <span class="gradient-text">UNIVERSE</span> AWAITS!
                    </h1>

                    <p class="hero-description">
                        Discover, play, and review the latest games. Join a community of passionate gamers.
                    </p>

                    <div class="hero-actions">
                        <a href="#featured" class="btn-primary-hero">
                            <i class="fas fa-gamepad"></i>
                            Browse Games
                        </a>

                        @guest
                        <a href="{{ route('register') }}" class="btn-secondary-hero">
                            <i class="fas fa-user-plus"></i>
                            Sign Up Free
                        </a>
                        @endguest
                    </div>

                    
                    <div class="hero-stats">
                        <div>
                            <strong>12K+</strong>
                            <span>Games</span>
                        </div>
                        <div>
                            <strong>48K+</strong>
                            <span>Players</span>
                        </div>
                        <div>
                            <strong>6K+</strong>
                            <span>Reviews</span>
                        </div>
                    </div>

                </div>
            </div>

            
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-visual">
                    <img src="{{ asset('storage/games/videogames.gif') }}" alt="Gaming animation">
                </div>
            </div>

        </div>
    </div>
</section>

<section class="offers-banner">
    <div class="container">
        <div class="offers-grid">
            <div class="offer-card">
                <div class="offer-icon">
                    <i class="fas fa-gift"></i>
                </div>
                <div class="offer-text">
                    <h4>Free Weekend</h4>
                    <p>Try premium games</p>
                </div>
            </div>
            <div class="offer-card special">
                <div class="offer-icon">
                    <i class="fas fa-fire"></i>
                </div>
                <div class="offer-text">
                    <h4>New Releases</h4>
                    <p>Fresh this week</p>
                </div>
            </div>
            <div class="offer-card">
                <div class="offer-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="offer-text">
                    <h4>Top Rated</h4>
                    <p>Best of the best</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-layer-group"></i>
                Browse by Genre
            </h2>
        </div>
        <div class="categories-slider">
            @foreach($categories as $category)
            <a href="{{ route('categories.show', $category) }}" class="category-card">
                <div class="category-bg"></div>
                <div class="category-icon">
                    <i class="fas fa-{{ $loop->index % 8 == 0 ? 'fist-raised' : ($loop->index % 8 == 1 ? 'hat-wizard' : ($loop->index % 8 == 2 ? 'dice-d20' : ($loop->index % 8 == 3 ? 'chess' : ($loop->index % 8 == 4 ? 'futbol' : ($loop->index % 8 == 5 ? 'puzzle-piece' : ($loop->index % 8 == 6 ? 'ghost' : 'plane')))))) }}"></i>
                </div>
                <h3 class="category-name">{{ $category->name }}</h3>
                <span class="category-count">{{ $category->games_count }} titles</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<section class="featured-games" id="featured">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">
                    <i class="fas fa-star"></i>
                    Featured & Recommended
                </h2>
                <p class="section-subtitle">Handpicked games based on quality and reviews</p>
            </div>
        </div>
        <div class="games-grid">
            @forelse($featuredGames as $game)
                @include('partials.game-card', ['game' => $game])
            @empty
            <div class="empty-state">
                <i class="fas fa-gamepad"></i>
                <h3>No Games Available</h3>
                <p>Check back soon for new releases!</p>
            </div>
            @endforelse
        </div>

        {{-- Load More Button - shows only if more than 6 games exist --}}
        @php
            $totalGames = \App\Models\Game::count();
        @endphp
        
        @if($totalGames > 6)
        <div class="load-more-section">
            <button class="load-more-btn">
                <span>Load More Games</span>
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        @endif
    </div>
</section>

@if($topRatedGames->count() > 0)
<section class="top-rated-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-crown"></i>
                Highest Rated
            </h2>
            <p class="section-subtitle">Community favorites with 4.5+ ratings</p>
        </div>
        <div class="top-rated-grid">
            @foreach($topRatedGames as $game)
            <a href="{{ route('games.show', $game) }}" style="text-decoration: none;">
                <div class="rated-card">
                    <div class="rank-number">#{{ $loop->iteration }}</div>
                    <div class="rated-image">
                        @if($game->image)
                            <img src="{{ asset('storage/' . $game->image) }}" alt="{{ $game->title }}">
                        @else
                            <img src="https://images.unsplash.com/photo-{{ 1511512578047 + $loop->index * 50 }}?w=300&h=200&fit=crop" alt="{{ $game->title }}">
                        @endif
                    </div>
                    <div class="rated-info">
                        <h4>{{ $game->title }}</h4>
                        <div class="rated-stats">
                            <div class="rating-display">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= (isset($game->reviews_avg_rating) ? round($game->reviews_avg_rating) : 0) ? 'filled' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="rating-value">{{ number_format($game->reviews_avg_rating ?? 0, 1) }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($latestReviews->count() > 0)
<section class="reviews-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-comments"></i>
                Recent Reviews
            </h2>
            <p class="section-subtitle">What the community is saying</p>
        </div>
        <div class="reviews-grid">
            @foreach($latestReviews as $review)
            <div class="review-card">
                <div class="review-header">
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ $review->user ? substr($review->user->name, 0, 1) : '?' }}
                        </div>
                        <div class="user-details">
                            <h4>{{ $review->user->name ?? 'Unknown User' }}</h4>
                            <span class="review-time">{{ $review->created_at?->diffForHumans() ?? 'Recently' }}</span>
                        </div>
                    </div>
                    <div class="review-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? 'filled' : '' }}"></i>
                        @endfor
                    </div>
                </div>
                <div class="review-game-info">
                    <i class="fas fa-gamepad"></i>
                    @if($review->game)
                    <a href="{{ route('games.show', $review->game) }}" style="text-decoration: none; color: inherit;">
                        <span>{{ $review->game->title }}</span>
                    </a>
                    @else
                    <span>Game not found</span>
                    @endif
                </div>
                <p class="review-text">{{ $review->comment }}</p>
                <div class="review-actions">
                    <button class="review-btn">
                        <i class="far fa-thumbs-up"></i> Helpful
                    </button>
                    <button class="review-btn">
                        <i class="far fa-comment"></i> Reply
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-box">
            <div class="newsletter-content">
                <div class="newsletter-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h2>Stay Updated</h2>
                <p>Get notified about new releases, sales, and exclusive gaming content</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email" class="newsletter-input">
                    <button type="submit" class="newsletter-btn">
                        Subscribe <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
                <span class="newsletter-note">No spam, unsubscribe anytime</span>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});


let currentPage = 1;
let loading = false;

const loadMoreBtn = document.querySelector('.load-more-btn');
if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', async function() {
       
        if (loading) return;
        
        loading = true;
        const originalHTML = this.innerHTML;
        this.innerHTML = '<span>Loading...</span><i class="fas fa-spinner fa-spin"></i>';
        this.disabled = true;
        
        try {
            currentPage++;
            
           
            const response = await fetch(`/load-more-games?page=${currentPage}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            
            console.log('Loaded games:', data.loadedGames);
            
            if (data.html && data.html.trim() !== '') {
                const gamesGrid = document.querySelector('.games-grid');
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                
                
                const newCards = tempDiv.querySelectorAll('.game-card');
                
                
                newCards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    gamesGrid.appendChild(card);
                    
                    
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
                
               
                if (!data.hasMore) {
                    this.style.display = 'none';
                    
                   
                    const loadMoreSection = document.querySelector('.load-more-section');
                    const endMessage = document.createElement('p');
                    endMessage.textContent = 'All games loaded';
                    endMessage.style.cssText = `
                        text-align: center; 
                        color: #94a3b8; 
                        margin-top: 20px; 
                        font-size: 16px;
                        animation: fadeIn 0.5s ease;
                    `;
                    loadMoreSection.appendChild(endMessage);
                } else {
                    
                    this.innerHTML = originalHTML;
                    this.disabled = false;
                }
                
            } else {
                console.log('No more games to load');
                this.style.display = 'none';
                
                const loadMoreSection = document.querySelector('.load-more-section');
                const endMessage = document.createElement('p');
                endMessage.textContent = 'All games loaded 🎮';
                endMessage.style.cssText = 'text-align: center; color: #94a3b8; margin-top: 20px; font-size: 16px;';
                loadMoreSection.appendChild(endMessage);
            }
            
        } catch (error) {
            console.error('Error loading games:', error);
            alert('An error occurred while loading games. Please try again.');
            
           
            currentPage--;
            this.innerHTML = originalHTML;
            this.disabled = false;
        } finally {
            loading = false;
        }
    });
}


const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);
</script>
@endpush