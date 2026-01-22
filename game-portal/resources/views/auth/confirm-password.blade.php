{{-- resources/views/auth/confirm-password.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="auth-card">
               
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h2 class="auth-title">Secure Area</h2>
                    <p class="auth-subtitle">Please confirm your password before continuing</p>
                </div>

              
                <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
                    @csrf

                  
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
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword()">
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

                    
                    <button type="submit" class="btn game-btn-primary">
                        <span class="btn-text">
                            <i class="fas fa-check-circle"></i>
                            Confirm
                        </span>
                        <div class="btn-glow"></div>
                    </button>
                </form>
            </div>

           
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
</script>
@endsection