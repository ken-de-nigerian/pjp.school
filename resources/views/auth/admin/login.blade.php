@extends('layouts.app')

@section('content')
    <x-auth-split-card
        heading="{{ __('Admin sign in') }}"
        :intro="__('Sign in to the administrator dashboard.')"
        image-alt="">

        <form method="POST" action="{{ route('admin.login') }}" class="auth-form mt-6 sm:mt-7" id="admin-login-form">
            @csrf

            <div class="auth-form-body">
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <div class="auth-glass-field @error('email') auth-glass-field--error @enderror">
                        <i class="fas fa-envelope auth-glass-field__icon" aria-hidden="true"></i>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="auth-glass-input @error('email') form-input--error @enderror" placeholder="admin@school.edu" autocomplete="email" required>
                    </div>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="login-password" class="form-label">{{ __('Password') }}</label>
                    <div class="auth-glass-field @error('password') auth-glass-field--error @enderror">
                        <i class="fas fa-lock auth-glass-field__icon" aria-hidden="true"></i>
                        <input type="password" id="login-password" name="password" class="auth-glass-input @error('password') form-input--error @enderror" placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('login-password', this)" title="{{ __('Toggle password visibility') }}" aria-label="{{ __('Toggle password visibility') }}">
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
                    <input name="remember" type="checkbox" id="remember_me" class="form-checkbox-input" value="1">
                    <label for="remember_me" class="form-checkbox-label">{{ __('Remember me') }}</label>
                </div>
                <a href="mailto:{{ config('school.school_email') }}" class="form-link">{{ __('Forgot password?') }}</a>
            </div>

            <div class="auth-form-submit">
                <button type="submit" class="btn-primary inline-flex w-full items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">{{ __('Sign in') }}</button>
            </div>
        </form>
    </x-auth-split-card>
@endsection
