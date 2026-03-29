@extends('layouts.app', ['title' => 'Register student'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Register student"
                pill="Admin"
                title="Register student"
                description="Add a new student. Fill in profile, academic, contact, and guardian details."
            >
                <x-slot name="above">
                    <a href="{{ route('admin.classes') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to students
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <form id="add-student-form" method="POST" action="{{ route('admin.students.store') }}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" id="formattedPhone" name="formattedPhone">
                <input type="hidden" id="formattedPhoneFather" name="formattedPhoneFather">
                <input type="hidden" id="formattedPhoneMother" name="formattedPhoneMother">
                <input type="hidden" id="formattedPhoneSponsor" name="formattedPhoneSponsor">
                <input type="hidden" id="subjects" name="subjects" value="{{ old('subjects') }}">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">

                    <div class="space-y-4 sm:space-y-6">
                        <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                                <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Profile Settings</h2>
                            </div>

                            <div class="p-4 sm:p-5 min-w-0">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 min-w-0">
                                    <div class="form-group">
                                        <label for="firstname" class="form-label">Surname <span style="color: var(--primary);">*</span></label>
                                        <input type="text" id="firstname" name="firstname" class="form-input" value="{{ old('firstname') }}" placeholder="Enter Surname">
                                        <p id="firstname-error" class="form-error {{ $errors->has('firstname') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('firstname') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="lastname" class="form-label">Firstname <span style="color: var(--primary);">*</span></label>
                                        <input type="text" id="lastname" name="lastname" class="form-input" value="{{ old('lastname') }}" placeholder="Enter Firstname">
                                        <p id="lastname-error" class="form-error {{ $errors->has('lastname') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('lastname') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="othername" class="form-label">Lastname <span style="color: var(--primary);">*</span></label>
                                        <input type="text" id="othername" name="othername" class="form-input" value="{{ old('othername') }}" placeholder="Enter Lastname">
                                        <p id="othername-error" class="form-error {{ $errors->has('othername') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('othername') }}</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Upload students' profile photo <span style="color: var(--primary);">*</span></label>
                                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                        <label class="relative cursor-pointer" for="uploadfile-3" title="Replace this pic">
                                            <img id="uploadfile-3-preview" class="w-16 h-16 rounded-full object-cover border-2 shadow-sm" style="border-color: var(--outline-variant);" src="{{ asset('storage/students/default.png') }}" alt="Student" onerror="this.src='https://ui-avatars.com/api/?name=Student&size=128'">
                                        </label>
                                        <label class="btn-secondary cursor-pointer inline-flex items-center px-3 py-2 text-sm mb-0" for="uploadfile-3">Select Image</label>
                                        <input type="file" id="uploadfile-3" name="image" class="hidden" accept="image/jpeg,image/png,image/jpg">
                                    </div>
                                    <p id="image-error" class="form-error {{ $errors->has('image') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('image') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="dob" class="form-label">Date Of Birth <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="dob" name="dob" class="form-input" value="{{ old('dob') }}" placeholder="e.g. 15 May 2010">
                                    <p id="dob-error" class="form-error {{ $errors->has('dob') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('dob') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="gender" class="form-label">Gender</label>
                                    <x-forms.md-select-native id="gender" name="gender" class="form-select">
                                        <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                    </x-forms.md-select-native>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="form-label">Mobile number <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="phone" name="contact_phone" class="form-input" value="{{ old('contact_phone') }}" placeholder="e.g. +234 800 000 0000" autocomplete="off">
                                    <p id="contact_phone-error" class="form-error {{ $errors->has('contact_phone') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('contact_phone') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                                <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Academic Profile</h2>
                            </div>

                            <div class="p-4 sm:p-5 min-w-0">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                    <div class="form-group">
                                        <label for="class" class="form-label">Student's Class <span style="color: var(--primary);">*</span></label>
                                        <x-forms.md-select-native id="class" name="class" class="form-select">
                                            <option value="">Select Class</option>
                                            @foreach($classes as $c)
                                                <option value="{{ e($c->class_name) }}" {{ old('class', $selectedClass ?? '') === $c->class_name ? 'selected' : '' }}>{{ e($c->class_name) }}</option>
                                            @endforeach
                                        </x-forms.md-select-native>
                                        <p id="class-error" class="form-error {{ $errors->has('class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('class') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="reg_number" class="form-label">ID Number</label>
                                        <input type="number" id="reg_number" name="reg_number" class="form-input" value="{{ old('reg_number', $nextRegNumber) }}" placeholder="Student's ID Number" readonly>
                                        <p id="reg_number-error" class="form-error {{ $errors->has('reg_number') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('reg_number') }}</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <span class="form-label mb-0">Subjects To Offer <span style="color: var(--primary);">*</span></span>
                                        <div class="flex gap-2 flex-shrink-0">
                                            <button type="button" id="subjects-select-all" class="text-xs font-medium px-2 py-1 rounded-lg transition-colors" style="color: var(--primary); background: var(--primary-container);">Select all</button>
                                            <button type="button" id="subjects-clear" class="text-xs font-medium px-2 py-1 rounded-lg transition-colors" style="color: var(--on-surface-variant); background: var(--surface-container-high);">Clear</button>
                                        </div>
                                    </div>
                                    <div id="subjects-checkbox-container" class="grid grid-cols-2 sm:grid-cols-3 gap-2 rounded-xl p-2 border max-h-52 overflow-y-auto min-h-[4rem]" style="border-color: var(--outline-variant); background: var(--surface-container-low);"></div>
                                    <p id="subjects-placeholder" class="text-xs mt-1.5 hidden" style="color: var(--on-surface-variant);">Select a class above to see subjects.</p>
                                    <p id="subjects-error" class="form-error {{ $errors->has('subjects') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('subjects') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 sm:space-y-6">
                        <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                                <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Contact Address</h2>
                            </div>

                            <div class="p-4 sm:p-5 min-w-0">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                    <div class="form-group">
                                        <label for="lga" class="form-label">LGA <span style="color: var(--primary);">*</span></label>
                                        <input type="text" id="lga" name="lga" class="form-input" value="{{ old('lga') }}" placeholder="Enter LGA">
                                        <p id="lga-error" class="form-error {{ $errors->has('lga') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('lga') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="state" class="form-label">State <span style="color: var(--primary);">*</span></label>
                                        <x-forms.md-select-native id="state" name="state" class="form-select">
                                            <option value="">Select State</option>
                                            @foreach($states as $st)
                                                <option value="{{ e($st) }}" {{ old('state') === $st ? 'selected' : '' }}>{{ e($st) }}</option>
                                            @endforeach
                                        </x-forms.md-select-native>
                                        <p id="state-error" class="form-error {{ $errors->has('state') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('state') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="city" class="form-label">City <span style="color: var(--primary);">*</span></label>
                                        <input type="text" id="city" name="city" class="form-input" value="{{ old('city') }}" placeholder="Enter City">
                                        <p id="city-error" class="form-error {{ $errors->has('city') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('city') }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="country" class="form-label">Country <span style="color: var(--primary);">*</span></label>
                                        <input type="text" id="country" name="nationality" class="form-input" value="{{ old('nationality', 'Nigeria') }}" placeholder="Enter Country" readonly>
                                        <p id="nationality-error" class="form-error {{ $errors->has('nationality') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('nationality') }}</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="address" class="form-label">Home Address <span style="color: var(--primary);">*</span></label>
                                    <textarea id="address" name="address" rows="3" class="form-input" placeholder="Enter full address">{{ old('address') }}</textarea>
                                    <p id="address-error" class="form-error {{ $errors->has('address') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('address') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                                <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Parents Information</h2>
                            </div>

                            <div class="p-4 sm:p-5 min-w-0">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                    <div class="form-group">
                                        <label for="father_name" class="form-label">Father's Name</label>
                                        <input type="text" id="father_name" name="father_name" class="form-input" value="{{ old('father_name') }}" placeholder="Enter Father's Name">
                                    </div>

                                    <div class="form-group">
                                        <label for="father_occupation" class="form-label">Father's Occupation</label>
                                        <input type="text" id="father_occupation" name="father_occupation" class="form-input" value="{{ old('father_occupation') }}" placeholder="Enter Father's Occupation">
                                    </div>

                                    <div class="form-group">
                                        <label for="father_phone" class="form-label">Father's Phone</label>
                                        <input type="text" id="father_phone" name="father_phone" class="form-input" value="{{ old('father_phone') }}" placeholder="e.g. +234 800 000 0000">
                                    </div>

                                    <div class="form-group">
                                        <label for="mother_name" class="form-label">Mother's Name</label>
                                        <input type="text" id="mother_name" name="mother_name" class="form-input" value="{{ old('mother_name') }}" placeholder="Enter Mother's Name">
                                    </div>

                                    <div class="form-group">
                                        <label for="mother_occupation" class="form-label">Mother's Occupation</label>
                                        <input type="text" id="mother_occupation" name="mother_occupation" class="form-input" value="{{ old('mother_occupation') }}" placeholder="Enter Mother's Occupation">
                                    </div>

                                    <div class="form-group">
                                        <label for="mother_phone" class="form-label">Mother's Phone</label>
                                        <input type="text" id="mother_phone" name="mother_phone" class="form-input" value="{{ old('mother_phone') }}" placeholder="e.g. +234 800 000 0000">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 sm:mt-6">
                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Sponsor's information</h2>
                        </div>

                        <div class="p-4 sm:p-5 min-w-0">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                <div class="form-group">
                                    <label for="sponsor_name" class="form-label">Sponsor's Name</label>
                                    <input type="text" id="sponsor_name" name="sponsor_name" class="form-input" value="{{ old('sponsor_name') }}" placeholder="Enter Sponsor's Name">
                                </div>

                                <div class="form-group">
                                    <label for="sponsor_occupation" class="form-label">Sponsor's Occupation</label>
                                    <input type="text" id="sponsor_occupation" name="sponsor_occupation" class="form-input" value="{{ old('sponsor_occupation') }}" placeholder="Enter Sponsor's Occupation">
                                </div>

                                <div class="form-group">
                                    <label for="sponsor_phone" class="form-label">Sponsor's Phone</label>
                                    <input type="text" id="sponsor_phone" name="sponsor_phone" class="form-input" value="{{ old('sponsor_phone') }}" placeholder="e.g. +234 800 000 0000">
                                </div>

                                <div class="form-group">
                                    <label for="relationship" class="form-label">Relationship With Sponsor</label>
                                    <input type="text" id="relationship" name="relationship" class="form-input" value="{{ old('relationship') }}" placeholder="Enter Relationship With Sponsor">
                                </div>

                                <div class="form-group sm:col-span-2">
                                    <label for="sponsor_address" class="form-label">Sponsor's Address</label>
                                    <textarea id="sponsor_address" name="sponsor_address" rows="3" class="form-input" placeholder="Enter sponsor's full address">{{ old('sponsor_address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 sm:mt-6">
                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Other Information</h2>
                        </div>
                        <div class="p-4 sm:p-5 min-w-0">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                <div class="form-group">
                                    <label for="house" class="form-label">House</label>
                                    <x-forms.md-select-native id="house" name="house" class="form-select">
                                        @foreach($houses as $h)
                                            <option value="{{ e($h) }}" {{ old('house') === $h ? 'selected' : '' }}>{{ e($h) }}</option>
                                        @endforeach
                                    </x-forms.md-select-native>
                                </div>

                                <div class="form-group">
                                    <label for="category" class="form-label">Category</label>
                                    <x-forms.md-select-native id="category" name="category" class="form-select">
                                        <option value="Boarding" {{ old('category', 'Boarding') === 'Boarding' ? 'selected' : '' }}>Boarding</option>
                                        <option value="Day" {{ old('category') === 'Day' ? 'selected' : '' }}>Day</option>
                                    </x-forms.md-select-native>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" id="studentAddBtn" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-full text-sm">Submit Form</button>
                </div>
            </form>
        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const addForm = document.getElementById('add-student-form');
                const subjectsContainer = document.getElementById('subjects-checkbox-container');
                const subjectsPlaceholder = document.getElementById('subjects-placeholder');
                const subjectsHidden = document.getElementById('subjects');
                const classSelect = document.getElementById('class');
                let juniorSubjects = @json($juniorSubjects->map(fn($s) => ['value' => $s->subject_name, 'label' => $s->subject_name])->values());
                let seniorSubjects = @json($seniorSubjects->map(fn($s) => ['value' => $s->subject_name, 'label' => $s->subject_name])->values());
                let initialSubjectsStr = @json(old('subjects', ''));

                function getSubjectListForClass() {
                    const classVal = (classSelect && classSelect.value) || '';
                    if (classVal.indexOf('JSS') === 0) return juniorSubjects;
                    if (classVal.indexOf('SSS') === 0) return seniorSubjects;
                    return [];
                }

                function updateSubjectsCheckboxes(initialCheckStr) {
                    const list = getSubjectListForClass();
                    if (!subjectsContainer) return;
                    subjectsContainer.innerHTML = '';
                    if (list.length === 0) {
                        if (subjectsPlaceholder) subjectsPlaceholder.classList.remove('hidden');
                        return;
                    }
                    if (subjectsPlaceholder) subjectsPlaceholder.classList.add('hidden');
                    const preCheck = (initialCheckStr || '').split(',').map(function (s) {
                        return s.trim();
                    }).filter(Boolean);
                    list.forEach(function(s) {
                        const label = document.createElement('label');
                        label.className = 'subject-option flex items-center gap-2 cursor-pointer px-3 py-2.5 rounded-lg border transition-colors min-h-[2.5rem] w-full text-left hover:bg-[var(--surface-container-high)] focus-within:bg-[var(--surface-container-high)]';
                        label.style.borderColor = 'var(--outline-variant)';
                        label.style.color = 'var(--on-surface)';
                        const cb = document.createElement('input');
                        cb.type = 'checkbox';
                        cb.className = 'subject-checkbox w-4 h-4 sm:w-5 sm:h-5 rounded border-2 flex-shrink-0 cursor-pointer focus:ring-2 focus:ring-offset-0 focus:outline-none';
                        cb.style.borderColor = 'var(--outline)';
                        cb.style.accentColor = 'var(--primary)';
                        cb.value = s.value;
                        if (preCheck.indexOf(s.value) !== -1) cb.checked = true;
                        const span = document.createElement('span');
                        span.className = 'text-xs sm:text-sm font-medium truncate min-w-0';
                        span.textContent = s.label;
                        label.appendChild(cb);
                        label.appendChild(span);
                        subjectsContainer.appendChild(label);
                    });
                }

                if (classSelect) {
                    classSelect.addEventListener('change', function() { updateSubjectsCheckboxes(null); });
                }
                updateSubjectsCheckboxes(initialSubjectsStr);

                document.getElementById('subjects-select-all') && document.getElementById('subjects-select-all').addEventListener('click', function() {
                    if (subjectsContainer) subjectsContainer.querySelectorAll('.subject-checkbox').forEach(function(c) { c.checked = true; });
                });
                document.getElementById('subjects-clear') && document.getElementById('subjects-clear').addEventListener('click', function() {
                    if (subjectsContainer) subjectsContainer.querySelectorAll('.subject-checkbox').forEach(function(c) { c.checked = false; });
                });

                if (addForm) {
                    addForm.addEventListener('submit', function(ev) {
                        ev.preventDefault();
                        if (subjectsHidden && subjectsContainer) {
                            const checked = [].slice.call(subjectsContainer.querySelectorAll('.subject-checkbox:checked')).map(function (c) { return c.value; });
                            subjectsHidden.value = checked.join(',');
                        }

                        const btn = document.getElementById('studentAddBtn');
                        let csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        if (!csrf) csrf = addForm.querySelector('input[name="_token"]') && addForm.querySelector('input[name="_token"]').value;
                        clearFieldErrors(['firstname', 'lastname', 'othername', 'image', 'dob', 'contact_phone', 'class', 'reg_number', 'subjects', 'lga', 'state', 'city', 'nationality', 'address']);
                        setButtonLoading(btn, true);

                        fetch(addForm.action, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: new FormData(addForm)
                        })
                        .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, status: r.status, data: data }; }); })
                        .then(function(res) {
                            const data = res.data;
                            if (res.ok && data.status === 'success') {
                                flashSuccess(data.message || 'Student registered successfully.');
                                setTimeout(function() {
                                    if (data.redirect) {
                                        window.location.href = data.redirect;
                                    } else {
                                        window.location.reload();
                                    }
                                }, window.RELOAD_DELAY_MS);
                            } else if (data.errors) {
                                showLaravelErrors(data.errors);
                            } else {
                                flashError(Array.isArray(data.message) ? data.message.join(' ') : (data.message || 'Submission failed.'));
                            }
                        })
                        .catch(function() { flashError('An error occurred. Please try again.'); })
                        .finally(function() { setButtonLoading(btn, false); });
                    });
                }

                const uploadInput = document.getElementById('uploadfile-3');
                const preview = document.getElementById('uploadfile-3-preview');
                if (uploadInput && preview) {
                    uploadInput.addEventListener('change', function() {
                        const file = this.files[0];
                        if (file && file.type.indexOf('image') === 0) {
                            const r = new FileReader();
                            r.onload = function() { preview.src = r.result; };
                            r.readAsDataURL(file);
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
