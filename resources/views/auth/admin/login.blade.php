@extends('layouts.app')

@section('content')
    <div class="auth-container auth-with-banner auth-banner--admin">
        <aside class="auth-banner auth-banner--admin" aria-label="Admin portal info">
            <div class="auth-banner-bg auth-banner-bg--admin" aria-hidden="true"></div>
            <div class="auth-banner-inner auth-banner-inner--admin">
                <a href="{{ route('home') }}" class="auth-banner-back auth-banner-back--admin">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span>Back to home</span>
                </a>

                <div class="auth-banner-brand auth-banner-brand--admin">
                    <span class="auth-banner-logo-text auth-banner-logo-text--admin">{{ config('app.name') }}</span>
                    <span class="auth-banner-pill auth-banner-pill--admin">Admin</span>
                </div>

                <div class="auth-banner-content">
                    <p class="auth-banner-eyebrow">Administrator portal</p>
                    <h2 class="auth-banner-title auth-banner-title--admin">Sign in to manage your school</h2>
                    <p class="auth-banner-tagline auth-banner-tagline--admin">Full access to settings, staff, attendance, and reporting.</p>
                    <div class="auth-banner-features-wrap auth-banner-features-wrap--admin">
                        <div class="auth-banner-feature auth-banner-feature--admin">
                            <span class="auth-banner-feature-num" aria-hidden="true">01</span>
                            <span class="auth-banner-feature-text auth-banner-feature-text--admin">Settings & security</span>
                        </div>
                        <div class="auth-banner-feature auth-banner-feature--admin">
                            <span class="auth-banner-feature-num" aria-hidden="true">02</span>
                            <span class="auth-banner-feature-text auth-banner-feature-text--admin">Staff & roster</span>
                        </div>
                        <div class="auth-banner-feature auth-banner-feature--admin">
                            <span class="auth-banner-feature-num" aria-hidden="true">03</span>
                            <span class="auth-banner-feature-text auth-banner-feature-text--admin">Reports & data</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="auth-form-wrap">
            <div class="auth-card auth-card--modern">
                <header class="auth-form-header">
                    <h1 class="auth-title">Admin sign in</h1>
                    <p class="auth-subtitle">Sign in to the administrator dashboard</p>
                </header>

                <form method="POST" action="{{ route('admin.login') }}" class="auth-form">
                    @csrf

                    <div class="auth-form-body">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <i class="fas fa-envelope input-icon" aria-hidden="true"></i>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-input @error('email') form-input--error @enderror" placeholder="admin@school.edu" autocomplete="email">
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
