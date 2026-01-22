@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="dashboard-header">
        <h1 class="dashboard-title">
            Admin <span class="gradient-text">Notifications</span>
        </h1>
        <p class="dashboard-subtitle">Daily Reports & System Alerts</p>
    </div>

    <div class="admin-section mt-4">
        <div class="section-header">
            <h2 class="section-title">All Notifications</h2>
        </div>

        <div class="activity-panel mt-3">
            <div class="panel-body">

                @forelse($notifications as $notification)
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