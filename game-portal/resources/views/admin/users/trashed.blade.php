@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <span class="gradient-text">Deleted Users</span>
        </h1>
        <p class="dashboard-subtitle">Restore or permanently delete user accounts</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="section-header">
        <h2 class="section-title">Trashed Users</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            ← Back to Active Users
        </a>
    </div>

    <div class="admin-table-container">
        @if($users->count() > 0)
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="table-cell-id">#{{ $user->id }}</td>
                            <td class="table-cell-title">{{ $user->name }}</td>
                            <td class="table-cell-text">{{ $user->email }}</td>
                            <td>
                                @if($user->role->name === 'admin')
                                    <span class="table-badge table-badge-admin">Admin</span>
                                @else
                                    <span class="table-badge table-badge-user">User</span>
                                @endif
                            </td>
                            <td class="table-cell-text">
                                {{ $user->deleted_at->diffForHumans() }}
                            </td>
                            <td>
                                <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="table-btn table-btn-success">
                                        ↻ Restore
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.users.force-destroy', $user->id) }}" method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Are you sure? This will permanently delete all user data!')">
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

            @if($users->hasPages())
                <div class="admin-pagination">
                    {{ $users->links() }}
                </div>
            @endif
        @else
            <div class="empty-state-small">
                <p>No deleted users found.</p>
            </div>
        @endif
    </div>
</div>
@endsection