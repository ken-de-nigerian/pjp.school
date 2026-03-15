@extends('layouts.app')

@section('content')
    <div class="auth-container auth-with-banner">
        <aside class="auth-banner" aria-label="Teacher portal info">
            <div class="auth-banner-bg" aria-hidden="true"></div>
            <div class="auth-banner-inner">
                <a href="{{ route('home') }}" class="auth-banner-back">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span>Back to home</span>
                </a>

                <div class="auth-banner-brand">
                    <span class="auth-banner-logo-text">{{ config('app.name') }}</span>
                    <span class="auth-banner-pill">Teacher Portal</span>
                </div>

                <div class="auth-banner-content">
                    <h2 class="auth-banner-title">Sign in to the Teacher Portal</h2>
                    <p class="auth-banner-tagline">Access your dashboard to manage classes, take attendance, and view reports.</p>
                    <div class="auth-banner-features">
                        <div class="auth-banner-feature">
                            <span class="auth-banner-feature-icon" aria-hidden="true"><i class="fas fa-chalkboard-user"></i></span>
                            <span class="auth-banner-feature-text">Class & attendance</span>
                        </div>
                        <div class="auth-banner-feature">
                            <span class="auth-banner-feature-icon" aria-hidden="true"><i class="fas fa-chart-line"></i></span>
                            <span class="auth-banner-feature-text">Grades & reports</span>
                        </div>
                        <div class="auth-banner-feature">
                            <span class="auth-banner-feature-icon" aria-hidden="true"><i class="fas fa-shield-halved"></i></span>
                            <span class="auth-banner-feature-text">Secure access</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="auth-form-wrap">
            <div class="auth-card auth-card--modern">
                <header class="auth-form-header">
                    <h1 class="auth-title">Welcome back</h1>
                    <p class="auth-subtitle">Sign in to your teacher account</p>
                </header>

                <form method="POST" action="{{ route('teacher.login') }}" class="auth-form">
                    @csrf

                    <div class="auth-form-body">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <i class="fas fa-envelope input-icon" aria-hidden="true"></i>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-input @error('email') form-input--error @enderror" placeholder="you@school.edu" autocomplete="email">
                            </div>
                            @error('email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="login-password" class="form-label">Password</label>
                            <div class="input-group">
                                <i class="fas fa-lock input-icon" aria-hidden="true"></i>
                                <input type="password" id="login-password" name="password" class="form-input @error('password') form-input--error @enderror" placeholder="••••••••" autocomplete="current-password">
                                <button type="button" class="password-toggle" onclick="togglePassword('login-password', this)" title="Toggle password visibility" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="auth-form-options">
                        <div class="form-checkbox">
                            <input name="remember" type="checkbox" id="remember_me" class="form-checkbox-input">
                            <label for="remember_me" class="form-checkbox-label">Remember me</label>
                        </div>
                        <a href="mailto:{{ config('school.school_email') }}" class="form-link">Forgot password?</a>
                    </div>

                    <div class="auth-form-submit">
                        <button type="submit" class="btn-primary" data-preloader>Sign in</button>
                    </div>

                    <p class="auth-form-footer">Protected by reCAPTCHA</p>
                </form>
            </div>
        </div>
    </div>
@endsection
