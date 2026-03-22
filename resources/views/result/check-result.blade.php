@extends('layouts.result')

@section('content')
    @php
        $termValue = old('term', $settings['term'] ?? 'First Term');
        $sessionValue = old('session', $settings['session'] ?? '');
        $classValue = old('class', $settings['class'] ?? 'JSS 1');
    @endphp
    <div class="auth-container auth-with-banner auth-banner--check-result">
        <aside class="auth-banner auth-banner--check-result" aria-label="Check school result">
            <div class="auth-banner-bg auth-banner-bg--check-result" aria-hidden="true"></div>
            <div class="auth-banner-accent auth-banner-accent--check-result" aria-hidden="true"></div>
            <div class="auth-banner-inner auth-banner-inner--check-result">
                <a href="{{ route('home') }}" class="auth-banner-back auth-banner-back--check-result">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span>Back to school site</span>
                </a>

                <div class="auth-banner-school-mark">
                    <div class="auth-banner-school-mark__seal" aria-hidden="true">
                        <i class="fas fa-file-lines"></i>
                    </div>
                    <div class="auth-banner-school-mark__text">
                        <span class="auth-banner-logo-text auth-banner-logo-text--check-result">{{ config('app.name') }}</span>
                        <span class="auth-banner-pill auth-banner-pill--check-result">Report cards</span>
                    </div>
                </div>

                <div class="auth-banner-content">
                    <p class="auth-banner-eyebrow auth-banner-eyebrow--check-result">Students &amp; parents</p>
                    <h2 class="auth-banner-title auth-banner-title--check-result">Look up a published term report</h2>
                    <p class="auth-banner-tagline auth-banner-tagline--check-result">Use the same term, session, class, and student ID that appear on the school register. A scratch card is only asked when your school has that policy turned on.</p>

                    <ul class="auth-banner-duties" aria-label="What you will need">
                        <li class="auth-banner-duty">
                            <span class="auth-banner-duty__icon" aria-hidden="true"><i class="fas fa-calendar-check"></i></span>
                            <span class="auth-banner-duty__label">Correct term and academic session</span>
                        </li>
                        <li class="auth-banner-duty">
                            <span class="auth-banner-duty__icon" aria-hidden="true"><i class="fas fa-id-card"></i></span>
                            <span class="auth-banner-duty__label">Class and student ID exactly as issued</span>
                        </li>
                        <li class="auth-banner-duty">
                            <span class="auth-banner-duty__icon" aria-hidden="true"><i class="fas fa-lock-open"></i></span>
                            <span class="auth-banner-duty__label">Results show only after the office publishes them</span>
                        </li>
                    </ul>

                    <p class="auth-banner-foot auth-banner-foot--check-result">Wrong class or ID usually means “not found.” For help, ask the school office or email <a class="auth-banner-foot__link" href="mailto:{{ config('school.school_email') }}">{{ config('school.school_email') }}</a>.</p>
                </div>
            </div>
        </aside>

        <div class="auth-form-wrap">
            <div class="auth-card auth-card--modern auth-card--check-result">
                <header class="auth-form-header">
                    <h1 class="auth-title">Check result</h1>
                    <p class="auth-subtitle">View your published report for the selected term</p>
                </header>

                <form id="check-result-form" action="{{ route('result.check') }}" method="GET" class="auth-form" novalidate>
                    <div class="auth-form-body">
                        <div class="form-group">
                            <label for="term" class="form-label">Term</label>
                            <div class="input-group">
                                <i class="fas fa-calendar-alt input-icon" aria-hidden="true"></i>
                                <select id="term" name="term" class="form-select @error('term') form-select--error @enderror" @if($errors->has('term')) aria-invalid="true" aria-describedby="term-error" @else aria-invalid="false" @endif>
                                    <option value="First Term" {{ $termValue === 'First Term' ? 'selected' : '' }}>First Term</option>
                                    <option value="Second Term" {{ $termValue === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                    <option value="Third Term" {{ $termValue === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                                </select>
                            </div>
                            <p id="term-error" class="form-error {{ $errors->has('term') ? '' : 'hidden' }}" role="alert" aria-live="polite">{{ $errors->first('term') }}</p>
                        </div>

                        <div class="form-group">
                            <label for="session" class="form-label">Session</label>
                            <div class="input-group">
                                <i class="fas fa-calendar input-icon" aria-hidden="true"></i>
                                <select id="session" name="session" class="form-select @error('session') form-select--error @enderror" @if($errors->has('session')) aria-invalid="true" aria-describedby="session-error" @else aria-invalid="false" @endif>
                                    @foreach(range((int)date('Y') - 5, (int)date('Y') + 5) as $y)
                                        @php $opt = $y . '/' . ($y + 1); @endphp
                                        <option value="{{ $opt }}" {{ $sessionValue === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p id="session-error" class="form-error {{ $errors->has('session') ? '' : 'hidden' }}" role="alert" aria-live="polite">{{ $errors->first('session') }}</p>
                        </div>

                        <div class="form-group">
                            <label for="class" class="form-label">Class</label>
                            <div class="input-group">
                                <i class="fas fa-graduation-cap input-icon" aria-hidden="true"></i>
                                <select id="class" name="class" class="form-select @error('class') form-select--error @enderror" @if($errors->has('class')) aria-invalid="true" aria-describedby="class-error" @else aria-invalid="false" @endif>
                                    @foreach(['JSS 1','JSS 2','JSS 3','SSS 1','SSS 2','SSS 3'] as $c)
                                        <option value="{{ $c }}" {{ $classValue === $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p id="class-error" class="form-error {{ $errors->has('class') ? '' : 'hidden' }}" role="alert" aria-live="polite">{{ $errors->first('class') }}</p>
                        </div>

                        <div class="form-group">
                            <label for="reg_number" class="form-label">Student ID</label>
                            <div class="input-group">
                                <i class="fas fa-id-card input-icon" aria-hidden="true"></i>
                                <input type="text" id="reg_number" name="reg_number" value="{{ old('reg_number') }}" class="form-input @error('reg_number') form-input--error @enderror" autocomplete="off" placeholder="Enter your student ID" @if($errors->has('reg_number')) aria-invalid="true" aria-describedby="reg_number-error" @else aria-invalid="false" @endif>
                            </div>
                            <p id="reg_number-error" class="form-error {{ $errors->has('reg_number') ? '' : 'hidden' }}" role="alert" aria-live="polite">{{ $errors->first('reg_number') }}</p>
                        </div>

                        @if($scratchRequired)
                            <div class="form-group">
                                <label for="scratch_card" class="form-label">Scratch card number</label>
                                <div class="input-group">
                                    <i class="fas fa-ticket input-icon" aria-hidden="true"></i>
                                    <input type="text" id="scratch_card" name="scratch_card" value="{{ old('scratch_card') }}" class="form-input @error('scratch_card') form-input--error @enderror" autocomplete="off" placeholder="Enter scratch card number" @if($errors->has('scratch_card')) aria-invalid="true" aria-describedby="scratch_card-error" @else aria-invalid="false" @endif>
                                </div>
                                <p id="scratch_card-error" class="form-error {{ $errors->has('scratch_card') ? '' : 'hidden' }}" role="alert" aria-live="polite">{{ $errors->first('scratch_card') }}</p>
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

@push('scripts')
    <script>
        (function () {
            const form = document.getElementById('check-result-form');
            if (!form) return;

            const scratchRequired = {{ $scratchRequired ? 'true' : 'false' }};

            const fieldIds = ['term', 'session', 'class', 'reg_number'].concat(scratchRequired ? ['scratch_card'] : []);

            function clearErrors() {
                fieldIds.forEach(function (name) {
                    const err = document.getElementById(name + '-error');
                    const el = document.getElementById(name);
                    if (err) {
                        err.textContent = '';
                        err.classList.add('hidden');
                    }
                    if (el) {
                        el.classList.remove('form-input--error', 'form-select--error');
                        el.setAttribute('aria-invalid', 'false');
                        el.removeAttribute('aria-describedby');
                    }
                });
            }

            function showFieldError(name, message) {
                const err = document.getElementById(name + '-error');
                const el = document.getElementById(name);
                if (err) {
                    err.textContent = message;
                    err.classList.remove('hidden');
                }
                if (el) {
                    el.classList.add(el.tagName === 'SELECT' ? 'form-select--error' : 'form-input--error');
                    el.setAttribute('aria-invalid', 'true');
                    el.setAttribute('aria-describedby', name + '-error');
                }
            }

            form.addEventListener('submit', function (e) {
                clearErrors();

                const term = (document.getElementById('term') && document.getElementById('term').value) || '';
                const session = (document.getElementById('session') && document.getElementById('session').value) || '';
                const klass = (document.getElementById('class') && document.getElementById('class').value) || '';
                const reg = (document.getElementById('reg_number') && document.getElementById('reg_number').value.trim()) || '';
                const scratchEl = document.getElementById('scratch_card');
                const scratch = scratchEl ? scratchEl.value.trim() : '';

                let invalid = false;

                if (!term) {
                    showFieldError('term', 'Please select a term.');
                    invalid = true;
                }
                if (!session) {
                    showFieldError('session', 'Please select a session.');
                    invalid = true;
                } else if (!/^\d{4}\/\d{4}$/.test(session)) {
                    showFieldError('session', 'Session must be in the format YYYY/YYYY.');
                    invalid = true;
                }
                if (!klass) {
                    showFieldError('class', 'Please select a class.');
                    invalid = true;
                }
                if (!reg) {
                    showFieldError('reg_number', 'Student ID is required.');
                    invalid = true;
                }
                if (scratchRequired && !scratch) {
                    showFieldError('scratch_card', 'Scratch card number is required.');
                    invalid = true;
                }

                if (invalid) {
                    e.preventDefault();
                }
            });
        })();
    </script>
@endpush
