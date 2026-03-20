@extends('layouts.guest')

@section('content')
    <div class="auth-container auth-with-banner">
        <aside class="auth-banner" aria-label="Check result info">
            <div class="auth-banner-bg" aria-hidden="true"></div>
            <div class="auth-banner-inner">
                <a href="{{ route('home') }}" class="auth-banner-back">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span>Back to home</span>
                </a>

                <div class="auth-banner-brand">
                    <span class="auth-banner-logo-text">{{ config('app.name') }}</span>
                    <span class="auth-banner-pill">Results</span>
                </div>

                <div class="auth-banner-content">
                    <h2 class="auth-banner-title">Check your result</h2>
                    <p class="auth-banner-tagline">View your grades by term, session, and class. Enter your details to continue.</p>
                    <div class="auth-banner-features">
                        <div class="auth-banner-feature">
                            <span class="auth-banner-feature-icon" aria-hidden="true"><i class="fas fa-calendar-alt"></i></span>
                            <span class="auth-banner-feature-text">By term & session</span>
                        </div>
                        <div class="auth-banner-feature">
                            <span class="auth-banner-feature-icon" aria-hidden="true"><i class="fas fa-graduation-cap"></i></span>
                            <span class="auth-banner-feature-text">All classes</span>
                        </div>
                        <div class="auth-banner-feature">
                            <span class="auth-banner-feature-icon" aria-hidden="true"><i class="fas fa-shield-halved"></i></span>
                            <span class="auth-banner-feature-text">Secure</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="auth-form-wrap">
            <div class="auth-card auth-card--modern">
                <header class="auth-form-header">
                    <h1 class="auth-title">Check Result</h1>
                    <p class="auth-subtitle">Enter the details below to view your result</p>
                </header>

                <form action="{{ route('result.check') }}" method="GET" class="auth-form">
                    <div class="auth-form-body">
                        <div class="form-group">
                            <label for="term" class="form-label">Term</label>
                            <select id="term" name="term" class="form-select" required>
                                <option value="First Term" {{ ($settings['term'] ?? '') === 'First Term' ? 'selected' : '' }}>First Term</option>
                                <option value="Second Term" {{ ($settings['term'] ?? '') === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                <option value="Third Term" {{ ($settings['term'] ?? '') === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="session" class="form-label">Session</label>
                            <select id="session" name="session" class="form-select" required>
                                @foreach(range((int)date('Y') - 5, (int)date('Y') + 5) as $y)
                                    @php $opt = $y . '/' . ($y + 1); @endphp
                                    <option value="{{ $opt }}" {{ ($settings['session'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="class" class="form-label">Class</label>
                            <select id="class" name="class" class="form-select" required>
                                @foreach(['JSS 1','JSS 2','JSS 3','SSS 1','SSS 2','SSS 3'] as $c)
                                    <option value="{{ $c }}">{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="reg_number" class="form-label">Student ID</label>
                            <input type="text" id="reg_number" name="reg_number" value="{{ old('reg_number', request('reg_number')) }}" class="form-input" required autocomplete="off" placeholder="Enter your student ID">
                        </div>

                        @if($scratchRequired)
                            <div class="form-group">
                                <label for="scratch_card" class="form-label">Scratch card number</label>
                                <input type="text" id="scratch_card" name="scratch_card" value="{{ old('scratch_card', request('scratch_card')) }}" class="form-input" required autocomplete="off" placeholder="Enter scratch card number">
                            </div>
                        @endif
                    </div>

                    <div class="auth-form-submit">
                        <button type="submit" class="btn-primary" data-preloader>Check result</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
