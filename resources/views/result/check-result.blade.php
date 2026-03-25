@extends('layouts.result')

@section('content')
    @php
        $termValue = old('term', $settings['term'] ?? 'First Term');
        $sessionValue = old('session', $settings['session'] ?? '');
        $classValue = old('class', $settings['class'] ?? 'JSS 1');
    @endphp

    <x-auth-split-card
        heading="{{ __('Check result') }}"
        :intro="__('View your published report for the selected term.')"
        container-max-class="lg:max-w-6xl"
        :image-src="asset('assets/img/forgot-pass.svg')"
        image-alt="Check result">

        <form id="check-result-form" action="{{ route('result.check') }}" method="GET" class="auth-form mt-6 sm:mt-7" novalidate>
            <div class="auth-form-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-0">
                    <x-forms.md-select
                        id="term"
                        name="term"
                        :label="__('Term')"
                        icon="fas fa-calendar-alt"
                        :error="$errors->first('term')"
                        required
                    >
                        <option value="First Term" {{ $termValue === 'First Term' ? 'selected' : '' }}>{{ __('First Term') }}</option>
                        <option value="Second Term" {{ $termValue === 'Second Term' ? 'selected' : '' }}>{{ __('Second Term') }}</option>
                        <option value="Third Term" {{ $termValue === 'Third Term' ? 'selected' : '' }}>{{ __('Third Term') }}</option>
                    </x-forms.md-select>

                    <x-forms.md-select
                        id="session"
                        name="session"
                        :label="__('Session')"
                        icon="fas fa-calendar"
                        :error="$errors->first('session')"
                        required
                    >
                        @foreach (range((int) date('Y') - 5, (int) date('Y') + 5) as $y)
                            @php $opt = $y . '/' . ($y + 1); @endphp
                            <option value="{{ $opt }}" {{ $sessionValue === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </x-forms.md-select>
                </div>

                <x-forms.md-select
                    id="class"
                    name="class"
                    :label="__('Class')"
                    icon="fas fa-graduation-cap"
                    :error="$errors->first('class')"
                    required
                >
                    @foreach (['JSS 1', 'JSS 2', 'JSS 3', 'SSS 1', 'SSS 2', 'SSS 3'] as $c)
                        <option value="{{ $c }}" {{ $classValue === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </x-forms.md-select>

                <div class="form-group">
                    <label for="reg_number" class="form-label">{{ __('Student ID') }}</label>
                    <div class="auth-glass-field">
                        <i class="fas fa-id-card auth-glass-field__icon" aria-hidden="true"></i>
                        <input type="text" id="reg_number" name="reg_number" value="{{ old('reg_number') }}" class="auth-glass-input @error('reg_number') form-input--error @enderror" autocomplete="off" placeholder="{{ __('Enter your student ID') }}" @if ($errors->has('reg_number')) aria-invalid="true" aria-describedby="reg_number-error" @else aria-invalid="false" @endif>
                    </div>
                    <p id="reg_number-error" class="form-error {{ $errors->has('reg_number') ? '' : 'hidden' }}" role="alert" aria-live="polite">{{ $errors->first('reg_number') }}</p>
                </div>

                @if ($scratchRequired)
                    <div class="form-group">
                        <label for="scratch_card" class="form-label">{{ __('Scratch card number') }}</label>
                        <div class="auth-glass-field">
                            <i class="fas fa-ticket auth-glass-field__icon" aria-hidden="true"></i>
                            <input type="text" id="scratch_card" name="scratch_card" value="{{ old('scratch_card') }}" class="auth-glass-input @error('scratch_card') form-input--error @enderror" autocomplete="off" placeholder="{{ __('Enter scratch card number') }}" @if ($errors->has('scratch_card')) aria-invalid="true" aria-describedby="scratch_card-error" @else aria-invalid="false" @endif>
                        </div>
                        <p id="scratch_card-error" class="form-error {{ $errors->has('scratch_card') ? '' : 'hidden' }}" role="alert" aria-live="polite">{{ $errors->first('scratch_card') }}</p>
                    </div>
                @endif
            </div>

            <div class="auth-form-submit mt-6">
                <button type="submit" class="btn-primary inline-flex w-full items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">{{ __('Check result') }}</button>
            </div>
        </form>
    </x-auth-split-card>
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
