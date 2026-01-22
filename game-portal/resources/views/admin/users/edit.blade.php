@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            Edit <span class="gradient-text">User</span>
        </h1>
        <p class="dashboard-subtitle">Update user information</p>
    </div>

   
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="admin-form-container">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                   
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               placeholder="Enter full name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               placeholder="Enter email address"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                   
                    <div class="form-group">
                        <label class="form-label">User Information</label>
                        <div class="activity-panel">
                            <div class="panel-body">
                                <div style="display: grid; gap: 15px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: rgba(255,255,255,0.02); border-radius: 6px;">
                                        <span style="color: rgba(255,255,255,0.6); font-size: 0.9rem;">Role</span>
                                        @if($user->role_id === 1)
                                            <span class="table-badge table-badge-admin">Admin</span>
                                        @else
                                            <span class="table-badge table-badge-user">User</span>
                                        @endif
                                    </div>
                                    
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: rgba(255,255,255,0.02); border-radius: 6px;">
                                        <span style="color: rgba(255,255,255,0.6); font-size: 0.9rem;">Registered</span>
                                        <span style="color: #fff; font-weight: 500;">{{ $user->created_at->format('M d, Y') }}</span>
                                    </div>
                                    
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background: rgba(255,255,255,0.02); border-radius: 6px;">
                                        <span style="color: rgba(255,255,255,0.6); font-size: 0.9rem;">Last Updated</span>
                                        <span style="color: #fff; font-weight: 500;">{{ $user->updated_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                   
                    <div style="display: flex; justify-content: space-between; gap: 15px; margin-top: 40px;">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection