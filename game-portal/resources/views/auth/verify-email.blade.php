{{-- resources/views/auth/verify-email.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="auth-card">
               
                <div class="auth-header">
                    <div class="auth-icon register-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h2 class="auth-title">Verify Email</h2>
                    <p class="auth-subtitle">Thanks for signing up! Please verify your email address by clicking the link we sent to you.</p>
                </div>

               
                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success custom-alert">
                        <i class="fas fa-check-circle"></i>
                        A new verification link has been sent to your email address!
                    </div>
                @endif

                
                <div class="verify-info-box">
                    <div class="info-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <p class="info-text">
                        If you didn't receive the email, we'll gladly send you another one.
                    </p>
                </div>

               
                <div class="verify-actions">
                    
                    <form method="POST" action="{{ route('verification.send') }}" class="auth-form">
                        @csrf
                        <button type="submit" class="btn game-btn-primary register-btn-glow">
                            <span class="btn-text">
                                <i class="fas fa-paper-plane"></i>
                                Resend Verification Email
                            </span>
                            <div class="btn-glow"></div>
                        </button>
                    </form>

                    
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn game-btn-secondary">
                            <span class="btn-text">
                                <i class="fas fa-sign-out-alt"></i>
                                Log Out
                            </span>
                        </button>
                    </form>
                </div>
            </div>

           
            <div class="auth-decoration auth-decoration-1"></div>
            <div class="auth-decoration auth-decoration-2"></div>
        </div>
    </div>
</div>
@endsection