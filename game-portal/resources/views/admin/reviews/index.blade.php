@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            Review <span class="gradient-text">Management</span>
        </h1>
        <p class="dashboard-subtitle">Monitor and manage user reviews</p>
    </div>

   
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

   
    <div class="admin-stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Reviews</div>
            <div class="stat-value">{{ $reviews->total() }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Average Rating</div>
            <div class="stat-value">{{ number_format($reviews->avg('rating'), 1) }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">This Month</div>
            <div class="stat-value">{{ $reviews->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
        </div>
    </div>

    
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">All Reviews</h2>
        </div>

        <div class="admin-table-container">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Game</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td class="table-cell-id">#{{ $review->id }}</td>
                            <td>
                                <div>
                                    <span class="table-cell-title">{{ $review->user->name ?? 'Unknown' }}</span><br>
                                    <small class="table-cell-muted">{{ $review->user->email ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                @if($review->game)
                                    <span class="table-cell-title">{{ $review->game->title }}</span>
                                @else
                                    <span class="table-cell-muted">[Deleted Game]</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="display: flex; gap: 2px;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#fbbf24' : 'rgba(255,255,255,0.2)' }}; font-size: 0.85rem;"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-badge">{{ $review->rating }}/5</span>
                                </div>
                            </td>
                            <td>
                                @if($review->comment)
                                    <div class="table-cell-text" style="max-width: 300px;">
                                        {{ Str::limit($review->comment, 80) }}
                                    </div>
                                @else
                                    <span class="table-cell-muted">No comment</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <span class="table-cell-text">{{ $review->created_at?->format('M d, Y') ?? 'N/A' }}</span><br>
                                    <small class="table-cell-muted">{{ $review->created_at?->diffForHumans() ?? 'Recently' }}</small>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <button type="button" 
                                            class="btn btn-sm table-btn table-btn-edit" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#reviewModal{{ $review->id }}"
                                            style="background: rgba(96, 165, 250, 0.1); color: #60a5fa; border-color: rgba(96, 165, 250, 0.3);">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this review?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm table-btn table-btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                               
                                <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content" style="background: rgba(20, 20, 40, 0.98); border: 1px solid rgba(96, 165, 250, 0.2); color: #fff;">
                                            <div class="modal-header" style="border-bottom: 1px solid rgba(96, 165, 250, 0.1);">
                                                <h5 class="modal-title" style="color: #fff; font-weight: 600;">Review Details</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div style="display: grid; gap: 20px;">
                                                    <div>
                                                        <label style="color: rgba(255,255,255,0.5); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">User</label>
                                                        <p style="color: #fff; font-weight: 600; margin: 5px 0 0 0;">{{ $review->user->name ?? 'Unknown' }}</p>
                                                    </div>
                                                    
                                                    <div>
                                                        <label style="color: rgba(255,255,255,0.5); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Game</label>
                                                        <p style="color: #fff; font-weight: 600; margin: 5px 0 0 0;">
                                                            @if($review->game)
                                                                {{ $review->game->title }}
                                                            @else
                                                                <span style="color: rgba(255,255,255,0.5);">[Deleted Game]</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    
                                                    <div>
                                                        <label style="color: rgba(255,255,255,0.5); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Rating</label>
                                                        <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#fbbf24' : 'rgba(255,255,255,0.2)' }};"></i>
                                                            @endfor
                                                            <span class="rating-badge">{{ $review->rating }}/5</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div>
                                                        <label style="color: rgba(255,255,255,0.5); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Date</label>
                                                        <p style="color: #fff; margin: 5px 0 0 0;">{{ $review->created_at?->format('F d, Y H:i') ?? 'N/A' }}</p>
                                                    </div>
                                                    
                                                    <div style="border-top: 1px solid rgba(96, 165, 250, 0.1); padding-top: 20px;">
                                                        <label style="color: rgba(255,255,255,0.5); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Comment</label>
                                                        <p style="color: rgba(255,255,255,0.8); margin: 10px 0 0 0; line-height: 1.6;">
                                                            {{ $review->comment ?: 'No comment provided' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer" style="border-top: 1px solid rgba(96, 165, 250, 0.1);">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="padding: 60px 20px; text-align: center;">
                                <div class="empty-state-small">
                                    <p>No reviews found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
            @if($reviews->hasPages())
            <div class="admin-pagination">
                {{ $reviews->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection