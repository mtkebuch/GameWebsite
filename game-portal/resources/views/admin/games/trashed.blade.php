@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <span class="gradient-text">Deleted Games</span>
        </h1>
        <p class="dashboard-subtitle">Restore or permanently delete games</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="section-header">
        <h2 class="section-title">Trashed Games</h2>
        <a href="{{ route('admin.games.index') }}" class="btn btn-secondary">
            ← Back to Active Games
        </a>
    </div>

    <div class="admin-table-container">
        @if($games->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Categories</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($games as $game)
                        <tr>
                            <td class="table-cell-id">#{{ $game->id }}</td>
                            <td>
                                <div class="table-cell-title">{{ $game->title }}</div>
                                <div class="table-cell-muted">{{ Str::limit($game->description, 50) }}</div>
                            </td>
                            <td>
                                @foreach($game->categories as $category)
                                    <span class="table-badge table-badge-info">{{ $category->name }}</span>
                                @endforeach
                            </td>
                            <td class="table-cell-text">
                                {{ $game->deleted_at->diffForHumans() }}
                            </td>
                            <td>
                                <form action="{{ route('admin.games.restore', $game->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="table-btn table-btn-success">
                                        ↻ Restore
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.games.force-destroy', $game->id) }}" method="POST" style="display: inline;" 
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

            @if($games->hasPages())
                <div class="admin-pagination">
                    {{ $games->links() }}
                </div>
            @endif
        @else
            <div class="empty-state-small">
                <p>No deleted games found.</p>
            </div>
        @endif
    </div>
</div>
@endsection