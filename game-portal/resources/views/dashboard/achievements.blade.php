@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="container py-5">
    
    
    <div class="dashboard-header mb-5">
        <h1 class="dashboard-title">
            <span class="gradient-text">Achievements</span>
        </h1>
        <p class="dashboard-subtitle">Track your gaming accomplishments</p>
    </div>

    
    <div class="dashboard-layout">
        
        
        <aside class="dashboard-sidebar">
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item">
                    <span class="nav-label">Dashboard</span>
                </a>
                
                <a href="{{ route('user.library') }}" class="nav-item">
                    <span class="nav-label">My Library</span>
                </a>
                
                <a href="{{ route('user.wishlist') }}" class="nav-item">
                    <span class="nav-label">Wishlist</span>
                </a>
                
                <a href="{{ route('user.reviews') }}" class="nav-item">
                    <span class="nav-label">My Reviews</span>
                </a>
                
                <a href="{{ route('games.index') }}" class="nav-item">
                    <span class="nav-label">Browse Games</span>
                </a>
                
                <a href="{{ route('user.achievements') }}" class="nav-item" style="background: rgba(96, 165, 250, 0.1); border-color: rgba(96, 165, 250, 0.3); color: #60a5fa;">
                    <span class="nav-label">Achievements</span>
                </a>
                
                <a href="{{ route('user.friends') }}" class="nav-item">
                    <span class="nav-label">Friends</span>
                </a>
                
                <a href="{{ route('profile.edit') }}" class="nav-item">
                    <span class="nav-label">Settings</span>
                </a>
            </nav>
        </aside>

        
        <main class="dashboard-content">
            
            <div class="content-section">
                <div class="empty-state-simple">
                    <div class="empty-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h2>Achievements Coming Soon</h2>
                    <p>This feature is currently under development. Check back later!</p>
                </div>
            </div>
            
        </main>
        
    </div>

</div>
@endsection