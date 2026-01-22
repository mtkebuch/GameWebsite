<div class="game-card">
    <div class="game-thumbnail">
        @if($game->image)
            <img src="{{ asset('storage/' . $game->image) }}" alt="{{ $game->title }}">
        @else
            <img src="https://images.unsplash.com/photo-{{ 1511512578047 + rand(1, 1000) }}?w=400&h=250&fit=crop" alt="{{ $game->title }}">
        @endif
        
        @if(rand(0, 2) < 2)
        <div class="sale-badge">
            <span class="discount">-{{ rand(20, 70) }}%</span>
        </div>
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
        
        <h3 class="game-title">
            <a href="{{ route('games.show', $game) }}" style="text-decoration: none; color: inherit;">
                {{ $game->title }}
            </a>
        </h3>
        
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
            
            <div class="price-box">
                @if($game->price == 0 || $game->price === null)
                    <span class="new-price" style="color: #22c55e;">FREE</span>
                @else
                    <span class="new-price">${{ number_format($game->price, 2) }}</span>
                @endif
            </div>
            <a href="{{ route('games.show', $game) }}" class="view-btn">
                Details <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>