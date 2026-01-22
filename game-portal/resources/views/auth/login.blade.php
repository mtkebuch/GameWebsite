{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="auth-card">
                
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <h2 class="auth-title">Welcome Back</h2>
                    <p class="auth-subtitle">Login to continue your gaming journey</p>
                </div>

                {{-- Success Message --}}
                @if (session('status'))
                    <div class="alert alert-success custom-alert">
                        <i class="fas fa-check-circle"></i>
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Error Message --}}
                @error('email')
                    <div class="alert alert-danger custom-alert mb-4" id="errorAlert" style="background: linear-gradient(135deg, #ff4444 0%, #cc0000 100%); color: white; border: 2px solid rgba(255, 255, 255, 0.2); box-shadow: 0 4px 15px rgba(255, 68, 68, 0.3); padding: 15px 20px; border-radius: 10px; display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 20px;"></i>
                        <span style="flex: 1;" id="errorMessage">{{ $message }}</span>
                    </div>
                @enderror

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" class="auth-form" id="loginForm">
                    @csrf

                    {{-- Email Field --}}
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </label>
                        <input 
                            id="email" 
                            type="email" 
                            class="form-control game-input @error('email') is-invalid @enderror" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="Enter your email"
                            @if(session('throttled')) disabled @endif
                        >
                    </div>

                    {{-- Password Field --}}
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password
                        </label>
                        <div class="password-wrapper">
                            <input 
                                id="password" 
                                type="password" 
                                class="form-control game-input @error('password') is-invalid @enderror" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                @if(session('throttled')) disabled @endif
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword()" @if(session('throttled')) disabled @endif>
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="form-options">
                        <div class="custom-checkbox">
                            <input 
                                type="checkbox" 
                                id="remember_me" 
                                name="remember"
                                class="checkbox-input"
                                @if(session('throttled')) disabled @endif
                            >
                            <label for="remember_me" class="checkbox-label">
                                Remember me
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    {{-- Login Button --}}
                    <button type="submit" class="btn game-btn-primary" id="loginButton" @if(session('throttled')) disabled @endif>
                        <span class="btn-text">
                            <i class="fas fa-sign-in-alt"></i>
                            <span id="buttonText">Login</span>
                        </span>
                        <div class="btn-glow"></div>
                    </button>

                    {{-- Register Link --}}
                    <div class="auth-footer">
                        <p class="footer-text">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="register-link">
                                Register Now
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            {{-- Decorations --}}
            <div class="auth-decoration auth-decoration-1"></div>
            <div class="auth-decoration auth-decoration-2"></div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }


    @if(session('throttled') && session('retry_after'))
        let secondsRemaining = {{ session('retry_after') }};
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const loginButton = document.getElementById('loginButton');
        const rememberCheckbox = document.getElementById('remember_me');
        const errorMessage = document.getElementById('errorMessage');
        const buttonText = document.getElementById('buttonText');

        function updateCountdown() {
            if (secondsRemaining > 0) {
                const minutes = Math.floor(secondsRemaining / 60);
                const seconds = secondsRemaining % 60;
                
                let timeText;
                if (minutes > 0) {
                    if (seconds > 0) {
                        timeText = `in ${minutes} minute${minutes > 1 ? 's' : ''} ${seconds} second${seconds !== 1 ? 's' : ''}`;
                    } else {
                        timeText = `in ${minutes} minute${minutes > 1 ? 's' : ''}`;
                    }
                } else {
                    timeText = `in ${seconds} second${seconds !== 1 ? 's' : ''}`;
                }
                
                errorMessage.textContent = `Too many login attempts. Please try again ${timeText}.`;
                buttonText.textContent = `Wait (${seconds}s)`;
                
                secondsRemaining--;
                setTimeout(updateCountdown, 1000);
            } else {
               
                emailInput.disabled = false;
                passwordInput.disabled = false;
                loginButton.disabled = false;
                rememberCheckbox.disabled = false;
                buttonText.textContent = 'Login';
                
             
                const alertBox = document.getElementById('errorAlert');
                if (alertBox) {
                    alertBox.style.transition = 'opacity 0.5s';
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.remove(), 500);
                }
            }
        }

      
        updateCountdown();
    @endif

    
    @error('email')
        @if(!session('throttled'))
            setTimeout(function() {
                const alertBox = document.getElementById('errorAlert');
                if (alertBox) {
                    alertBox.style.transition = 'opacity 0.5s';
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.remove(), 500);
                }
            }, 10000);
        @endif
    @enderror
</script>
@endsection