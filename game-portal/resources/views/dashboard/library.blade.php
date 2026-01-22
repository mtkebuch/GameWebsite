@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="container py-5">
    
  
    <div class="dashboard-header mb-5">
        <h1 class="dashboard-title">
            <span class="gradient-text">My Library</span>
        </h1>
        <p class="dashboard-subtitle">Games in your library</p>
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
                <a href="{{ route('user.library') }}" class="nav-item" style="background: rgba(255, 255, 255, 0.05); border-color: rgba(0, 247, 255, 0.3); color: var(--primary-cyan);">
                    <span class="nav-label">My Library</span>
                    <span class="nav-count" id="libraryCount">{{ $myGames->total() }}</span>
                </a>
                
                <a href="{{ route('user.wishlist') }}" class="nav-item">
                    <span class="nav-label">Wishlist</span>
                </a>
                
                <a href="{{ route('user.reviews') }}" class="nav-item">
                    <span class="nav-label">My Reviews</span>
                </a>
                
                <a href="{{ route('games.index') }}" class="nav-item">
                    <span class="nav-label">Browse Games</span>
                </a>
            </nav>
        </aside>

        
        <main class="dashboard-content">
            
            <div class="content-section">
                @if($myGames->count() > 0)
                    <div class="games-grid" id="gamesGrid">
                        @foreach($myGames as $game)
                        <div class="game-card-wrapper" id="game-{{ $game->id }}">
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
                            <button type="button" class="btn-remove" data-game-id="{{ $game->id }}" data-game-title="{{ $game->title }}" title="Remove from library">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    
                   
                    <div class="mt-4">
                        {{ $myGames->links() }}
                    </div>
                @else
                    <div id="emptyState" style="text-align: center; padding: 4rem 2rem;">
                        <h2 style="color: #fff; font-size: 1.5rem; margin-bottom: 1rem;">Your Library is Empty</h2>
                        <p style="color: rgba(255, 255, 255, 0.6); margin-bottom: 2rem;">Add games to your library to get started!</p>
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
            
            const gameId = this.dataset.gameId;
            const gameTitle = this.dataset.gameTitle;
            const gameCard = document.getElementById('game-' + gameId);
            
            
            if (!confirm(`Remove "${gameTitle}" from your library?`)) {
                return;
            }
            
           
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
           
            fetch('{{ route("library.remove") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    game_id: gameId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    
                    gameCard.style.transition = 'all 0.3s ease';
                    gameCard.style.opacity = '0';
                    gameCard.style.transform = 'scale(0.8)';
                    
                    setTimeout(() => {
                        gameCard.remove();
                        
                       
                        const countElement = document.getElementById('libraryCount');
                        if (countElement) {
                            const currentCount = parseInt(countElement.textContent);
                            countElement.textContent = currentCount - 1;
                        }
                        
                       
                        const gamesGrid = document.getElementById('gamesGrid');
                        if (gamesGrid && gamesGrid.children.length === 0) {
                            gamesGrid.innerHTML = `
                                <div id="emptyState" style="text-align: center; padding: 4rem 2rem;">
                                    <h2 style="color: #fff; font-size: 1.5rem; margin-bottom: 1rem;">Your Library is Empty</h2>
                                    <p style="color: rgba(255, 255, 255, 0.6); margin-bottom: 2rem;">Add games to your library to get started!</p>
                                    <a href="{{ route('games.index') }}" class="btn btn-primary">Browse Games</a>
                                </div>
                            `;
                        }
                        
                        
                        showNotification(data.message || 'Game removed from library', 'success');
                    }, 300);
                } else {
                    showNotification(data.message || 'Failed to remove game', 'error');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-times"></i>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-times"></i>';
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