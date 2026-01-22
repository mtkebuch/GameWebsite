@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">
                Category <span class="gradient-text">Management</span>
            </h1>
            <p class="dashboard-subtitle">Manage game categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 24px; font-weight: 600;">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>

    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    
    <div class="admin-stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Categories</div>
            <div class="stat-value">{{ $categories->total() }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Active Categories</div>
            <div class="stat-value">{{ $categories->count() }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Total Games</div>
            <div class="stat-value">{{ $categories->sum('games_count') }}</div>
        </div>
    </div>

    
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">All Categories</h2>
        </div>

        <div class="admin-table-container">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Games Count</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td class="table-cell-id">#{{ $category->id }}</td>
                            <td>
                                <span class="table-cell-title">{{ $category->name }}</span>
                            </td>
                            <td>
                                <span style="background: rgba(96, 165, 250, 0.15); color: #60a5fa; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; border: 1px solid rgba(96, 165, 250, 0.3);">
                                    {{ $category->games_count }} games
                                </span>
                            </td>
                            <td>
                                <div>
                                    <span class="table-cell-text">{{ $category->created_at->format('M d, Y') }}</span><br>
                                    <small class="table-cell-muted">{{ $category->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="btn btn-sm table-btn table-btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.categories.destroy', $category) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure? This will remove the category from all games.')">
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
                            <td colspan="5" style="padding: 60px 20px; text-align: center;">
                                <div class="empty-state-small">
                                    <p>No categories found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
            @if($categories->hasPages())
            <div class="admin-pagination">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection