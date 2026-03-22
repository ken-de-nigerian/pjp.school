@extends('layouts.app')

@section('content')
    <div class="auth-container auth-with-banner auth-banner--teacher">
        <aside class="auth-banner auth-banner--teacher" aria-label="Teacher portal sign-in">
            <div class="auth-banner-bg auth-banner-bg--teacher" aria-hidden="true"></div>
            <div class="auth-banner-accent auth-banner-accent--teacher" aria-hidden="true"></div>
            <div class="auth-banner-inner auth-banner-inner--teacher">
                <a href="{{ route('home') }}" class="auth-banner-back auth-banner-back--teacher">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span>Back to school site</span>
                </a>

                <div class="auth-banner-school-mark">
                    <div class="auth-banner-school-mark__seal" aria-hidden="true">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>
                    <div class="auth-banner-school-mark__text">
                        <span class="auth-banner-logo-text auth-banner-logo-text--teacher">{{ config('app.name') }}</span>
                        <span class="auth-banner-pill auth-banner-pill--teacher">Teaching staff</span>
                    </div>
                </div>

                <div class="auth-banner-content">
                    <p class="auth-banner-eyebrow auth-banner-eyebrow--teacher">Teacher sign-in</p>
                    <h2 class="auth-banner-title auth-banner-title--teacher">Your classes, registers, and mark book</h2>
                    <p class="auth-banner-tagline auth-banner-tagline--teacher">Take attendance, record behaviour, upload scores for your subjects, and see only the groups the office has assigned to you.</p>

                    <ul class="auth-banner-duties" aria-label="Typical tasks in the teacher portal">
                        <li class="auth-banner-duty">
                            <span class="auth-banner-duty__icon" aria-hidden="true"><i class="fas fa-user-check"></i></span>
                            <span class="auth-banner-duty__label">Registers and attendance for your classes</span>
                        </li>
                        <li class="auth-banner-duty">
                            <span class="auth-banner-duty__icon" aria-hidden="true"><i class="fas fa-upload"></i></span>
                            <span class="auth-banner-duty__label">Continuous assessment and exam marks</span>
                        </li>
                        <li class="auth-banner-duty">
                            <span class="auth-banner-duty__icon" aria-hidden="true"><i class="fas fa-clipboard-check"></i></span>
                            <span class="auth-banner-duty__label">Behavioural notes and class context</span>
                        </li>
                    </ul>

                    <p class="auth-banner-foot auth-banner-foot--teacher">For staff accounts issued by the school only. If you cannot sign in, contact the office or <a class="auth-banner-foot__link" href="mailto:{{ config('school.school_email') }}">{{ config('school.school_email') }}</a>.</p>
                </div>
            </div>
        </aside>

        <div class="auth-form-wrap">
            <div class="auth-card auth-card--modern">
                <header class="auth-form-header">
                    <h1 class="auth-title">Teacher sign in</h1>
                    <p class="auth-subtitle">Sign in to the teacher dashboard</p>
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
                </form>
            </div>
        </div>
    </div>
@endsection
