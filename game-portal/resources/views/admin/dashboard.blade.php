@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- Dashboard Header --}}
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            Admin <span class="gradient-text">Control Panel</span>
        </h1>
        <p class="dashboard-subtitle">Manage your gaming platform</p>
    </div>

    {{-- Stats Grid --}}
    <div class="admin-stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Games</div>
            <div class="stat-value">{{ $stats['total_games'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Reviews</div>
            <div class="stat-value">{{ $stats['total_reviews'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Categories</div>
            <div class="stat-value">{{ $stats['total_categories'] }}</div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">Quick Actions</h2>
        </div>

        <div class="action-cards-grid">
            <a href="{{ route('admin.games.create') }}" class="action-card">
                <div class="action-text">
                    <h3>Add Game</h3>
                    <p>Upload a new game</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <a href="{{ route('admin.categories.create') }}" class="action-card">
                <div class="action-text">
                    <h3>Add Category</h3>
                    <p>Create new category</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="action-card">
                <div class="action-text">
                    <h3>View Reviews</h3>
                    <p>Manage user reviews</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <a href="{{ route('admin.games.export') }}" class="action-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="action-text">
                    <h3>Export Games</h3>
                    <p>Download as Excel</p>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-download"></i>
                </div>
            </a>
        </div>
    </div>

    {{-- Notifications Section --}}
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">Notifications</h2>
            <a href="{{ route('admin.notifications') }}" class="panel-link">View All <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="activity-panel">
            <div class="panel-body">
                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                    <div class="activity-item notification-item {{ $notification->read_at ? '' : 'unread-notification' }}">
                        
                        <div class="notification-header">
                            <div class="notification-content">
                                <h4 class="activity-title">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                    @if(!$notification->read_at)
                                        <span class="notification-new-badge">NEW</span>
                                    @endif
                                </h4>
                                <p class="activity-time">
                                    {{ optional($notification->created_at)->diffForHumans() }}
                                </p>
                            </div>
                            
                            <form action="{{ route('admin.notifications.delete', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-notification-delete">
                                    Delete
                                </button>
                            </form>
                        </div>
                        
                        <p class="activity-description">
                            {{ $notification->data['message'] ?? '' }}
                        </p>

                        @if($notification->data['type'] ?? $notification->type === 'most_active_user')
                            <div class="notification-details">
                                <span class="detail-badge winner-badge">
                                    Winner: <strong>{{ $notification->data['winner_name'] ?? 'N/A' }}</strong>
                                </span>
                                <span class="detail-badge">
                                    Period: {{ $notification->data['period'] ?? 'N/A' }}
                                </span>
                                <span class="detail-badge">
                                    {{ $notification->data['date'] ?? 'N/A' }}
                                </span>
                            </div>
                        @elseif($notification->data['type'] ?? $notification->type === 'daily_active_users')
                            <div class="notification-details">
                                <span class="detail-badge">
                                    Active Users: <strong>{{ $notification->data['active_count'] ?? '0' }}</strong>
                                </span>
                                <span class="detail-badge">
                                    {{ $notification->data['date'] ?? 'N/A' }}
                                </span>
                            </div>
                        @elseif($notification->data['type'] ?? $notification->type === 'trash_cleanup_reminder')
                            <div class="notification-details">
                                <span class="detail-badge">
                                    Total: <strong>{{ $notification->data['total_trashed'] ?? '0' }}</strong>
                                </span>
                                <span class="detail-badge">
                                    Games: <strong>{{ $notification->data['trashed_games'] ?? '0' }}</strong>
                                </span>
                                <span class="detail-badge">
                                    Users: <strong>{{ $notification->data['trashed_users'] ?? '0' }}</strong>
                                </span>
                                <span class="detail-badge">
                                    Reviews: <strong>{{ $notification->data['trashed_reviews'] ?? '0' }}</strong>
                                </span>
                                <span class="detail-badge">
                                    Categories: <strong>{{ $notification->data['trashed_categories'] ?? '0' }}</strong>
                                </span>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="empty-state-small">
                        <i class="fas fa-bell-slash" style="font-size: 2rem; opacity: 0.3; margin-bottom: 8px;"></i>
                        <p>No notifications yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Games & Reviews --}}
    <div class="activity-grid">
        <div class="activity-panel">
            <div class="panel-header">
                <h3 class="panel-title">Recent Games</h3>
                <a href="{{ route('admin.games.index') }}" class="panel-link">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="panel-body">
                @forelse($stats['recent_games'] as $game)
                    <div class="activity-item">
                        <h4 class="activity-title">{{ $game->title }}</h4>
                        <p class="activity-description">
                            @foreach($game->categories->take(2) as $category)
                                <span style="background: rgba(96, 165, 250, 0.15); color: #60a5fa; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; margin-right: 4px;">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </p>
                        <p class="activity-time">{{ optional($game->created_at)->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="empty-state-small"><p>No games yet</p></div>
                @endforelse
            </div>
        </div>

        <div class="activity-panel">
            <div class="panel-header">
                <h3 class="panel-title">Recent Reviews</h3>
                <a href="{{ route('admin.reviews.index') }}" class="panel-link">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="panel-body">
                @forelse($stats['recent_reviews'] as $review)
                    <div class="activity-item">
                        <h4 class="activity-title">{{ $review->user->name }}</h4>
                        <p class="activity-description">
                            Rated <strong>{{ $review->game->title }}</strong>
                            <span class="rating-badge">{{ $review->rating }}/5</span>
                        </p>
                        <p class="activity-time">{{ optional($review->created_at)->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="empty-state-small"><p>No reviews yet</p></div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Management Section --}}
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">Management</h2>
        </div>
        <div class="management-grid">
            <a href="{{ route('admin.games.index') }}" class="management-card">
                <h3>Games Library</h3>
                <p>{{ $stats['total_games'] }} games</p>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="management-card">
                <h3>Categories</h3>
                <p>{{ $stats['total_categories'] }} categories</p>
            </a>
            <a href="{{ route('admin.users.index') }}" class="management-card">
                <h3>Users</h3>
                <p>{{ $stats['total_users'] }} users</p>
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="management-card">
                <h3>Reviews</h3>
                <p>{{ $stats['total_reviews'] }} reviews</p>
            </a>
        </div>
    </div>

    {{-- Trash Management --}}
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">Trash Management</h2>
        </div>
        <div class="management-grid">
            <a href="{{ route('admin.games.trashed') }}" class="management-card">
                <h3>Deleted Games</h3>
                <p>Restore or remove</p>
            </a>
            <a href="{{ route('admin.categories.trashed') }}" class="management-card">
                <h3>Deleted Categories</h3>
                <p>Restore or remove</p>
            </a>
            <a href="{{ route('admin.users.trashed') }}" class="management-card">
                <h3>Deleted Users</h3>
                <p>Restore or remove</p>
            </a>
            <a href="{{ route('admin.reviews.trashed') }}" class="management-card">
                <h3>Deleted Reviews</h3>
                <p>Restore or remove</p>
            </a>
        </div>
    </div>

</div>

<style>
.notification-item {
    position: relative;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 10px;
}

.notification-content {
    flex: 1;
}

.unread-notification {
    background: rgba(255, 255, 255, 0.02) !important;
    border-left: 2px solid rgba(255, 255, 255, 0.15) !important;
}

.notification-new-badge {
    display: inline-block;
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.7);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.65rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-left: 8px;
    vertical-align: middle;
}

.notification-details {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}

.detail-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(255, 255, 255, 0.05);
    color: rgba(255, 255, 255, 0.6);
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 0.8rem;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.winner-badge {
    background: rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    font-weight: 600;
    border: 1px solid rgba(255, 255, 255, 0.12);
}

.btn-notification-delete {
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.6);
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.85rem;
    font-weight: 500;
    flex-shrink: 0;
}

.btn-notification-delete:hover {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
    color: #ef4444;
}
</style>
@endsection