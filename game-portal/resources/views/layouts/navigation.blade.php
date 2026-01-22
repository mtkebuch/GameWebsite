<nav class="navbar navbar-expand-lg game-navbar">
    <div class="container">
        <a class="navbar-brand game-logo" href="{{ url('/') }}">
            <div class="logo-icon">
                <i class="fas fa-gamepad"></i>
            </div>
            <span class="logo-text">{{ config('app.name', 'GAME ZONE') }}</span>
            <div class="logo-glow"></div>
        </a>

        <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                @guest
                    <li class="nav-item">
                        <a class="nav-link game-nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link game-nav-link register-btn" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i>
                            <span>Register</span>
                        </a>
                    </li>
                @else
                    {{-- ადმინისთვის - მხოლოდ Admin Panel --}}
                    @if(auth()->user()->role_id === 1)
                        <li class="nav-item">
                            <a class="nav-link game-nav-link admin-link" href="{{ route('admin.dashboard') }}">
                                <span>Admin Panel</span>
                            </a>
                        </li>
                    @endif
                    
                    {{-- ჩვეულებრივი იუზერისთვის - User Dashboard --}}
                    @if(auth()->user()->role_id !== 1)
                        <li class="nav-item">
                            <a class="nav-link game-nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-chart-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif
                    
                    <li class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle game-nav-link user-link" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end game-dropdown">
                            <li class="dropdown-header">
                                <i class="fas fa-user-ninja"></i> Player Menu
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item game-dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-cog"></i>
                                    <span>Settings</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item game-dropdown-item logout-item">
                                        <i class="fas fa-power-off"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>