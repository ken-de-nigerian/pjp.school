@extends('layouts.app')

@section('content')
    <div class="auth-container auth-with-banner auth-banner--admin">
        <aside class="auth-banner auth-banner--admin" aria-label="School administration sign-in">
            <div class="auth-banner-bg auth-banner-bg--admin" aria-hidden="true"></div>
            <div class="auth-banner-accent auth-banner-accent--admin" aria-hidden="true"></div>
            <div class="auth-banner-inner auth-banner-inner--admin">
                <a href="{{ route('home') }}" class="auth-banner-back auth-banner-back--admin">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span>Back to school site</span>
                </a>

                <div class="auth-banner-school-mark">
                    <div class="auth-banner-school-mark__seal" aria-hidden="true">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="auth-banner-school-mark__text">
                        <span class="auth-banner-logo-text auth-banner-logo-text--admin">{{ config('app.name') }}</span>
                        <span class="auth-banner-pill auth-banner-pill--admin">School office</span>
                    </div>
                </div>

                <div class="auth-banner-content">
                    <p class="auth-banner-eyebrow auth-banner-eyebrow--admin">Administrator sign-in</p>
                    <h2 class="auth-banner-title auth-banner-title--admin">Where registers, results, and bursary work meet</h2>
                    <p class="auth-banner-tagline auth-banner-tagline--admin">This desk handles the term’s paperwork: class lists, report cards, fee reminders, and the records parents and teachers rely on.</p>

                    <ul class="auth-banner-duties" aria-label="What you can do after signing in">
                        <li class="auth-banner-duty">
                            <span class="auth-banner-duty__icon" aria-hidden="true"><i class="fas fa-clipboard-list"></i></span>
                            <span class="auth-banner-duty__label">Registers, attendance, and class rolls</span>
                        </li>
                        <li class="auth-banner-duty">
                            <span class="auth-banner-duty__icon" aria-hidden="true"><i class="fas fa-file-signature"></i></span>
                            <span class="auth-banner-duty__label">Results, promotions, and report sheets</span>
                        </li>
                        <li class="auth-banner-duty">
                            <span class="auth-banner-duty__icon" aria-hidden="true"><i class="fas fa-coins"></i></span>
                            <span class="auth-banner-duty__label">Fees, checklists, and school-wide notices</span>
                        </li>
                    </ul>

                    <p class="auth-banner-foot auth-banner-foot--admin">For authorised staff only. If you need an account reset, contact the ICT lead or <a class="auth-banner-foot__link" href="mailto:{{ config('school.school_email') }}">{{ config('school.school_email') }}</a>.</p>
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
                </form>
            </div>
        </div>
    </div>
@endsection
