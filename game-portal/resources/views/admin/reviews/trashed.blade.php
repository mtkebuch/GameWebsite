@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <span class="gradient-text">Deleted Reviews</span>
        </h1>
        <p class="dashboard-subtitle">Restore or permanently delete reviews</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="section-header">
        <h2 class="section-title">Trashed Reviews</h2>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
            ← Back to Active Reviews
        </a>
    </div>

    <div class="admin-table-container">
        @if($reviews->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Game</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td class="table-cell-id">#{{ $review->id }}</td>
                            <td class="table-cell-text">{{ $review->user->name }}</td>
                            <td class="table-cell-title">{{ $review->game->title }}</td>
                            <td>
                                <span class="rating-badge">{{ $review->rating }}/5 ⭐</span>
                            </td>
                            <td class="table-cell-muted">{{ Str::limit($review->comment, 50) }}</td>
                            <td class="table-cell-text">
                                {{ $review->deleted_at->diffForHumans() }}
                            </td>
                            <td>
                                <form action="{{ route('admin.reviews.restore', $review->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="table-btn table-btn-success">
                                        ↻ Restore
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.reviews.force-destroy', $review->id) }}" method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Are you sure? This action is permanent!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="table-btn table-btn-delete">
                                        🗑 Delete Forever
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($reviews->hasPages())
                <div class="admin-pagination">
                    {{ $reviews->links() }}
                </div>
            @endif
        @else
            <div class="empty-state-small">
                <p>No deleted reviews found.</p>
            </div>
        @endif
    </div>
</div>
@endsection