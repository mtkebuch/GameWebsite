@extends('layouts.app')

@section('content')
<div class="container py-5">
   
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            User <span class="gradient-text">Management</span>
        </h1>
        <p class="dashboard-subtitle">Manage platform members and permissions</p>
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
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $users->total() }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Admins</div>
            <div class="stat-value">{{ $users->where('role_id', 1)->count() }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Regular Users</div>
            <div class="stat-value">{{ $users->where('role_id', '!=', 1)->count() }}</div>
        </div>
    </div>

    
    <div class="admin-section">
        <div class="section-header">
            <h2 class="section-title">All Users</h2>
        </div>

        <div class="admin-table-container">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="table-cell-id">#{{ $user->id }}</td>
                            <td>
                                <span class="table-cell-title">{{ $user->name }}</span>
                                @if($user->id === auth()->id())
                                    <span class="table-badge table-badge-info">You</span>
                                @endif
                            </td>
                            <td class="table-cell-text">{{ $user->email }}</td>
                            <td>
                                @if($user->role_id === 1)
                                    <span class="table-badge table-badge-admin">Admin</span>
                                @else
                                    <span class="table-badge table-badge-user">User</span>
                                @endif
                            </td>
                            <td class="table-cell-muted">{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                       class="btn btn-sm table-btn table-btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-admin', $user) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-sm table-btn {{ $user->role_id === 1 ? 'table-btn-secondary' : 'table-btn-success' }}"
                                                title="{{ $user->role_id === 1 ? 'Remove Admin' : 'Make Admin' }}">
                                            <i class="fas fa-{{ $user->role_id === 1 ? 'user-minus' : 'user-shield' }}"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.users.destroy', $user) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm table-btn table-btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="padding: 60px 20px; text-align: center;">
                                <div class="empty-state-small">
                                    <p>No users found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

           
            @if($users->hasPages())
            <div class="admin-pagination">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection