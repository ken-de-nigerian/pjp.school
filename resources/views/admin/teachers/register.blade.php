@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Register teacher"
                pill="Admin"
                title="Register teacher"
                description="Add a new teacher. Fill in profile, employment, and contact details."
            >
                <x-slot name="above">
                    <a href="{{ route('admin.teachers.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to teachers
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <form id="register-teacher-form" method="POST" action="{{ route('admin.register_teacher.store') }}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="formattedPhone" id="formattedPhone" value="{{ old('formattedPhone') }}">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                    <div class="space-y-4 sm:space-y-6">
                        <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                                <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Profile</h2>
                            </div>
                            <div class="p-4 sm:p-5 min-w-0 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 min-w-0">
                                    <div class="form-group">
                                        <label for="firstname" class="form-label">First name <span style="color: var(--primary);">*</span></label>
                                        <input type="text" id="firstname" name="firstname" class="form-input w-full min-w-0" value="{{ old('firstname') }}" placeholder="First name" maxlength="255">
                                        <p id="firstname-error" class="form-error mt-1 text-sm {{ $errors->has('firstname') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('firstname') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="lastname" class="form-label">Last name <span style="color: var(--primary);">*</span></label>
                                        <input type="text" id="lastname" name="lastname" class="form-input w-full min-w-0" value="{{ old('lastname') }}" placeholder="Last name" maxlength="255">
                                        <p id="lastname-error" class="form-error mt-1 text-sm {{ $errors->has('lastname') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('lastname') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="othername" class="form-label">Other name</label>
                                        <input type="text" id="othername" name="othername" class="form-input w-full min-w-0" value="{{ old('othername') }}" placeholder="Other name" maxlength="255">
                                        <p id="othername-error" class="form-error mt-1 text-sm {{ $errors->has('othername') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('othername') }}</p>
                                    </div>
                                </div>

                                <div class="form-group min-w-0">
                                    <label for="photoimg" class="form-label">Profile photo</label>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <img id="photoimg-preview" class="w-16 h-16 rounded-full object-cover border-2" style="border-color: var(--outline-variant);" src="{{ asset('storage/teachers/default.png') }}" alt="Teacher" onerror="this.src='https://ui-avatars.com/api/?name=T&size=128'">
                                        <label class="btn-secondary cursor-pointer inline-flex items-center gap-2 px-3 py-2 text-sm mb-0" for="photoimg">Select image</label>
                                        <input type="file" id="photoimg" name="photoimg" class="hidden" accept="image/jpeg,image/png,image/jpg">
                                    </div>
                                    <p id="photoimg-error" class="form-error mt-1 text-sm {{ $errors->has('photoimg') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('photoimg') }}</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 min-w-0">
                                    <div class="form-group min-w-0">
                                        <label for="email" class="form-label">Email <span style="color: var(--primary);">*</span></label>
                                        <input type="email" id="email" name="email" class="form-input w-full min-w-0" value="{{ old('email') }}" placeholder="e.g. teacher@school.com" maxlength="255" autocomplete="email">
                                        <p id="email-error" class="form-error mt-1 text-sm {{ $errors->has('email') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('email') }}</p>
                                    </div>

                                    <div class="form-group min-w-0">
                                        <label for="password" class="form-label">Password <span style="color: var(--primary);">*</span></label>
                                        <div class="input-group">
                                            <input type="password" id="password" name="password" class="form-input w-full min-w-0" value="{{ old('password', 'password123') }}" placeholder="Min. 8 characters" minlength="8" autocomplete="new-password">
                                            <button type="button" class="password-toggle" onclick="togglePassword('password', this)" title="Toggle password visibility" aria-label="Toggle password visibility">
                                                <i class="fas fa-eye" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <p id="password-error" class="form-error mt-1 text-sm {{ $errors->has('password') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('password') }}</p>
                                    </div>

                                    <div class="form-group min-w-0">
                                        <label for="date_of_birth" class="form-label">Date of birth <span style="color: var(--primary);">*</span></label>
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-input w-full min-w-0" value="{{ old('date_of_birth') }}">
                                        <p id="date_of_birth-error" class="form-error mt-1 text-sm {{ $errors->has('date_of_birth') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('date_of_birth') }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                    <div class="form-group min-w-0">
                                        <label for="gender" class="form-label">Gender <span style="color: var(--primary);">*</span></label>
                                        <select id="gender" name="gender" class="form-select w-full min-w-0">
                                            <option value="">Select gender</option>
                                            <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        <p id="gender-error" class="form-error mt-1 text-sm {{ $errors->has('gender') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('gender') }}</p>
                                    </div>

                                    <div class="form-group min-w-0">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" id="phone" class="form-input w-full min-w-0" value="{{ old('formattedPhone') }}" placeholder="e.g. +234 800 000 0000" autocomplete="tel">
                                        <p id="phone-error" class="form-error mt-1 text-sm {{ $errors->has('formattedPhone') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('formattedPhone') }}</p>
                                    </div>
                                </div>

                                <div class="form-group min-w-0">
                                    <label for="employment_date" class="form-label">Employment date <span style="color: var(--primary);">*</span></label>
                                    <input type="date" id="employment_date" name="employment_date" class="form-input w-full min-w-0" value="{{ old('employment_date') }}">
                                    <p id="employment_date-error" class="form-error mt-1 text-sm {{ $errors->has('employment_date') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('employment_date') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 sm:space-y-6">
                        <div class="rounded-xl p-4 sm:p-5 border" style="background: var(--surface-container-low); border-color: var(--card-border);">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div class="min-w-0">
                                    <h3 class="text-sm sm:text-base font-semibold mb-1" style="color: var(--on-surface);">Form Teacher Status</h3>
                                    <p class="text-sm mb-0" style="color: var(--on-surface-variant);">Enable form teacher status to activate specific tools and responsibilities to support students and manage the classroom effectively.</p>
                                </div>
                                <label class="settings-switch flex-shrink-0 self-start md:self-center">
                                    <input type="checkbox" name="form_teacher" value="1" class="settings-switch-input" {{ old('form_teacher') ? 'checked' : '' }}>
                                    <span class="settings-switch-track"></span>
                                </label>
                            </div>
                        </div>

                        <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                                <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Contact & address</h2>
                            </div>
                            <div class="p-4 sm:p-5 min-w-0 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                    <div class="form-group min-w-0">
                                        <label for="lga" class="form-label">LGA</label>
                                        <input type="text" id="lga" name="lga" class="form-input w-full min-w-0" value="{{ old('lga') }}" placeholder="LGA" maxlength="100">
                                        <p id="lga-error" class="form-error mt-1 text-sm {{ $errors->has('lga') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('lga') }}</p>
                                    </div>
                                    <div class="form-group min-w-0">
                                        <label for="state" class="form-label">State</label>
                                        <select id="state" name="state" class="form-select w-full min-w-0">
                                            <option value="">Select State</option>
                                            @foreach($states ?? [] as $st)
                                                <option value="{{ e($st) }}" {{ old('state') === $st ? 'selected' : '' }}>{{ e($st) }}</option>
                                            @endforeach
                                        </select>
                                        <p id="state-error" class="form-error mt-1 text-sm {{ $errors->has('state') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('state') }}</p>
                                    </div>

                                    <div class="form-group min-w-0">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" id="city" name="city" class="form-input w-full min-w-0" value="{{ old('city') }}" placeholder="City" maxlength="100">
                                        <p id="city-error" class="form-error mt-1 text-sm {{ $errors->has('city') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('city') }}</p>
                                    </div>

                                    <div class="form-group min-w-0">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" id="country" name="country" class="form-input w-full min-w-0" value="{{ old('country') }}" placeholder="Set from phone or enter" maxlength="100" readonly>
                                        <p id="country-error" class="form-error mt-1 text-sm {{ $errors->has('country') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('country') }}</p>
                                    </div>
                                </div>
                                <div class="form-group min-w-0">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea id="address" name="address" rows="3" class="form-input w-full min-w-0" placeholder="Full address" maxlength="500">{{ old('address') }}</textarea>
                                    <p id="address-error" class="form-error mt-1 text-sm {{ $errors->has('address') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('address') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 sm:mt-6">
                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Academics</h2>
                            <p class="text-xs sm:text-sm mt-1 mb-0" style="color: var(--on-surface-variant);">Choose the class(es) this teacher is assigned to and the subject(s) they will teach.</p>
                        </div>

                        <div class="p-4 sm:p-5 min-w-0">
                            @php
                                $oldAssignedClass = old('assigned_class', []);
                                $oldSubjectToTeach = old('subject_to_teach', []);
                            @endphp

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                                {{-- Assigned class(es) --}}
                                <div class="form-group min-w-0 flex flex-col">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                                        <div>
                                            <label class="form-label mb-0 block">Assigned class(es) <span style="color: var(--primary);">*</span></label>
                                            <p class="text-xs mt-0.5 mb-0" style="color: var(--on-surface-variant);">Classes this teacher will manage or teach.</p>
                                        </div>
                                        <div class="flex gap-2 flex-shrink-0">
                                            <button type="button" id="register-classes-select-all" class="text-xs font-medium px-3 py-2 rounded-lg transition-opacity hover:opacity-90 min-h-[36px]" style="color: var(--primary); background: var(--primary-container);">Select all</button>
                                            <button type="button" id="register-classes-clear" class="text-xs font-medium px-3 py-2 rounded-lg transition-opacity hover:opacity-90 min-h-[36px]" style="color: var(--on-surface-variant); background: var(--surface-container-high);">Clear</button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 rounded-xl p-3 border min-h-[4.5rem]" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                                        @foreach($getClasses as $c)
                                            <label class="flex items-center gap-2.5 cursor-pointer rounded-lg px-3 py-2.5 border transition-colors min-h-[44px] w-full" style="background: var(--surface-container); border-color: var(--outline-variant); color: var(--on-surface);">
                                                <input type="checkbox" name="assigned_class[]" value="{{ e($c->class_name) }}" class="register-class-cb w-4 h-4 sm:w-5 sm:h-5 rounded border-2 cursor-pointer flex-shrink-0" style="border-color: var(--outline); accent-color: var(--primary);" {{ in_array($c->class_name, $oldAssignedClass) ? 'checked' : '' }}>
                                                <span class="text-xs font-medium truncate">{{ e($c->class_name) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p id="assigned_class-error" class="form-error mt-2 text-sm {{ $errors->has('assigned_class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('assigned_class') }}</p>
                                </div>

                                {{-- Subject(s) to teach --}}
                                <div class="form-group min-w-0 flex flex-col">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                                        <div>
                                            <label class="form-label mb-0 block">Subject(s) to teach <span style="color: var(--primary);">*</span></label>
                                            <p class="text-xs mt-0.5 mb-0" style="color: var(--on-surface-variant);">Subjects this teacher will deliver.</p>
                                        </div>
                                        <div class="flex gap-2 flex-shrink-0">
                                            <button type="button" id="register-subjects-select-all" class="text-xs font-medium px-3 py-2 rounded-lg transition-opacity hover:opacity-90 min-h-[36px]" style="color: var(--primary); background: var(--primary-container);">Select all</button>
                                            <button type="button" id="register-subjects-clear" class="text-xs font-medium px-3 py-2 rounded-lg transition-opacity hover:opacity-90 min-h-[36px]" style="color: var(--on-surface-variant); background: var(--surface-container-high);">Clear</button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 rounded-xl p-3 border min-h-[4.5rem]" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                                        @foreach($getSubjects as $s)
                                            <label class="flex items-center gap-2.5 cursor-pointer rounded-lg px-3 py-2.5 border transition-colors min-h-[44px] w-full" style="background: var(--surface-container); border-color: var(--outline-variant); color: var(--on-surface);">
                                                <input type="checkbox" name="subject_to_teach[]" value="{{ e($s->subject_name) }}" class="register-subject-cb w-4 h-4 sm:w-5 sm:h-5 rounded border-2 cursor-pointer flex-shrink-0" style="border-color: var(--outline); accent-color: var(--primary);" {{ in_array($s->subject_name, $oldSubjectToTeach) ? 'checked' : '' }}>
                                                <span class="text-xs font-medium truncate">{{ e($s->subject_name) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p id="subject_to_teach-error" class="form-error mt-2 text-sm {{ $errors->has('subject_to_teach') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('subject_to_teach') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" id="register-teacher-submit" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-full text-sm" data-preloader>Register Teacher</button>
                </div>
            </form>
        </div>
    </main>

    @push('scripts')
        <script>
            (function() {
                const photoInput = document.getElementById('photoimg');
                const photoPreview = document.getElementById('photoimg-preview');
                if (photoInput && photoPreview) {
                    photoInput.addEventListener('change', function() {
                        const file = this.files[0];
                        if (file && file.type.indexOf('image') === 0) {
                            const r = new FileReader();
                            r.onload = function() { photoPreview.src = r.result; };
                            r.readAsDataURL(file);
                        }
                    });
                }

                document.getElementById('register-classes-select-all') && document.getElementById('register-classes-select-all').addEventListener('click', function() {
                    document.querySelectorAll('.register-class-cb').forEach(function(cb) { cb.checked = true; });
                });
                document.getElementById('register-classes-clear') && document.getElementById('register-classes-clear').addEventListener('click', function() {
                    document.querySelectorAll('.register-class-cb').forEach(function(cb) { cb.checked = false; });
                });
                document.getElementById('register-subjects-select-all') && document.getElementById('register-subjects-select-all').addEventListener('click', function() {
                    document.querySelectorAll('.register-subject-cb').forEach(function(cb) { cb.checked = true; });
                });
                document.getElementById('register-subjects-clear') && document.getElementById('register-subjects-clear').addEventListener('click', function() {
                    document.querySelectorAll('.register-subject-cb').forEach(function(cb) { cb.checked = false; });
                });
            })();
        </script>
    @endpush
@endsection
