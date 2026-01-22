{{-- resources/views/auth/forgot-password-verified.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-icon"><i class="fas fa-envelope"></i></div>
                    <h2 class="auth-title">Verify Email</h2>
                    <p class="auth-subtitle">Your account email: <strong>{{ $maskedEmail }}</strong></p>
                </div>

                <form method="POST" action="{{ route('password.verify') }}" class="auth-form">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> Enter Full Email</label>
                        <input
                            id="email"
                            type="email"
                            class="form-control game-input @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="Enter your full email"
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn game-btn-primary">
                        <span class="btn-text"><i class="fas fa-check"></i> Verify Email</span>
                        <div class="btn-glow"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection