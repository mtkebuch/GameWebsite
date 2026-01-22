@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <span class="gradient-text">Deleted Categories</span>
        </h1>
        <p class="dashboard-subtitle">Restore or permanently delete categories</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="section-header">
        <h2 class="section-title">Trashed Categories</h2>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            ← Back to Active Categories
        </a>
    </div>

    <div class="admin-table-container">
        @if($categories->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Games Count</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td class="table-cell-id">#{{ $category->id }}</td>
                            <td class="table-cell-title">{{ $category->name }}</td>
                            <td class="table-cell-text">
                                <span class="table-badge table-badge-info">{{ $category->games_count }} games</span>
                            </td>
                            <td class="table-cell-text">
                                {{ $category->deleted_at->diffForHumans() }}
                            </td>
                            <td>
                                <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="table-btn table-btn-success">
                                        ↻ Restore
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.categories.force-destroy', $category->id) }}" method="POST" style="display: inline;" 
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

            @if($categories->hasPages())
                <div class="admin-pagination">
                    {{ $categories->links() }}
                </div>
            @endif
        @else
            <div class="empty-state-small">
                <p>No deleted categories found.</p>
            </div>
        @endif
    </div>
</div>
@endsection