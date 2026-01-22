{{-- resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="auth-card">

                <div class="auth-header">
                    <div class="auth-icon"><i class="fas fa-user"></i></div>
                    <h2 class="auth-title">Forgot Password</h2>
                    <p class="auth-subtitle">Enter your name to find your account</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('password.find') }}" class="auth-form">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i> Name
                        </label>
                        <input 
                            id="name" 
                            type="text" 
                            class="form-control game-input @error('name') is-invalid @enderror" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required
                            placeholder="Enter your name"
                        >
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn game-btn-primary">
                        <span class="btn-text"><i class="fas fa-search"></i> Find Account</span>
                        <div class="btn-glow"></div>
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
