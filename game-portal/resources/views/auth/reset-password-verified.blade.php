{{-- resources/views/auth/reset-password-verified.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="auth-card">

                <div class="auth-header">
                    <div class="auth-icon"><i class="fas fa-unlock-alt"></i></div>
                    <h2 class="auth-title">Reset Password</h2>
                    <p class="auth-subtitle">Your account email: <strong>{{ session('masked_email') }}</strong></p>
                </div>

                <form method="POST" action="{{ route('password.reset.custom') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> New Password</label>
                        <div class="password-wrapper">
                            <input 
                                id="password" 
                                type="password" 
                                class="form-control game-input @error('password') is-invalid @enderror" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                                placeholder="Enter new password"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label"><i class="fas fa-lock"></i> Confirm New Password</label>
                        <div class="password-wrapper">
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                class="form-control game-input @error('password_confirmation') is-invalid @enderror" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="Confirm new password"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                                <i class="fas fa-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn game-btn-primary">
                        <span class="btn-text"><i class="fas fa-save"></i> Reset Password</span>
                        <div class="btn-glow"></div>
                    </button>
                </form>

            </div>
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
