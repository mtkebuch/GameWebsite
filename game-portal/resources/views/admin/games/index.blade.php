@extends('layouts.app')

@section('content')
<div class="container py-5">
   
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">
                Game <span class="gradient-text">Management</span>
            </h1>
            <p class="dashboard-subtitle">Manage your game library</p>
        </div>
        <a href="{{ route('admin.games.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 24px; font-weight: 600;">
            <i class="fas fa-plus"></i> Add New Game
        </a>
    </div>

    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

   
    <div class="admin-stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Games</div>
            <div class="stat-value">{{ $games->total() }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Active Games</div>
            <div class="stat-value">{{ $games->count() }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">This Month</div>
            <div class="stat-value">{{ $games->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
        </div>
    </div>

    
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">All Games</h2>
        </div>

        <div class="admin-table-container">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Categories</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($games as $game)
                        <tr>
                            <td class="table-cell-id">#{{ $game->id }}</td>
                            <td>
                                @if($game->image)
                                    <img src="{{ asset('storage/' . $game->image) }}" 
                                         alt="{{ $game->title }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 12px; border: 2px solid rgba(96, 165, 250, 0.2);">
                                @else
                                    <div style="width: 60px; height: 60px; background: rgba(96, 165, 250, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(96, 165, 250, 0.2);">
                                        <i class="fas fa-image" style="color: rgba(255,255,255,0.3);"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <span class="table-cell-title">{{ $game->title }}</span><br>
                                    <small class="table-cell-muted">{{ Str::limit($game->description, 50) }}</small>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                    @foreach($game->categories as $category)
                                        <span style="background: rgba(96, 165, 250, 0.15); color: #60a5fa; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; border: 1px solid rgba(96, 165, 250, 0.3);">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div>
                                    <span class="table-cell-text">{{ $game->created_at->format('M d, Y') }}</span><br>
                                    <small class="table-cell-muted">{{ $game->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.games.edit', $game) }}" 
                                       class="btn btn-sm table-btn table-btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.games.destroy', $game) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this game?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm table-btn table-btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="padding: 60px 20px; text-align: center;">
                                <div class="empty-state-small">
                                    <p>No games found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

           
            @if($games->hasPages())
            <div class="admin-pagination">
                {{ $games->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection