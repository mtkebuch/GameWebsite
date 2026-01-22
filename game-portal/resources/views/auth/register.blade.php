{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="auth-card">
                
                <div class="auth-header">
                    <div class="auth-icon register-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2 class="auth-title">Create Account</h2>
                    <p class="auth-subtitle">Join the ultimate gaming community</p>
                </div>

                
                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                  
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i>
                            Full Name
                        </label>
                        <input 
                            id="name" 
                            type="text" 
                            class="form-control game-input @error('name') is-invalid @enderror" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                            placeholder="Enter your full name"
                        >
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    
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
                            autocomplete="username"
                            placeholder="Enter your email"
                        >
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                  
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
                                autocomplete="new-password"
                                placeholder="Create a strong password"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                   
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock"></i>
                            Confirm Password
                        </label>
                        <div class="password-wrapper">
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                class="form-control game-input @error('password_confirmation') is-invalid @enderror" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="Confirm your password"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                                <i class="fas fa-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                
                    <button type="submit" class="btn game-btn-primary register-btn-glow">
                        <span class="btn-text">
                            <i class="fas fa-user-plus"></i>
                            Create Account
                        </span>
                        <div class="btn-glow"></div>
                    </button>

                   
                    <div class="auth-footer">
                        <p class="footer-text">
                            Already have an account?
                            <a href="{{ route('login') }}" class="register-link">
                                Login Here
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </p>
                    </div>
                </form>
            </div>

           
            <div class="auth-decoration auth-decoration-1"></div>
            <div class="auth-decoration auth-decoration-2"></div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);
        
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
</script>
@endsection