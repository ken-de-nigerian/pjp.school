@extends('layouts.app', ['title' => 'Edit student'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Edit student"
                pill="Admin"
                title="Edit student"
                :description="e($student->firstname . ' ' . $student->lastname . ($student->othername ? ' ' . $student->othername : '')) . ' — ' . e($student->reg_number)"
            >
                <x-slot name="above">
                    <a href="{{ route('admin.classes') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to students
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                <div class="space-y-4 sm:space-y-6">
                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Profile Settings</h2>
                        </div>
                        <form id="edit-account-form" method="POST" action="{{ route('admin.students.update.account', $student) }}" class="p-4 sm:p-5 min-w-0">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="formattedPhone" name="contact_phone" value="{{ old('contact_phone', $student->contact_phone) }}">

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 min-w-0">
                                <div class="form-group">
                                    <label for="firstname" class="form-label">Surname <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="firstname" name="firstname" class="form-input" value="{{ old('firstname', $student->firstname) }}" placeholder="Enter Surname">
                                    <p id="firstname-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="lastname" class="form-label">Firstname <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="lastname" name="lastname" class="form-input" value="{{ old('lastname', $student->lastname) }}" placeholder="Enter Firstname">
                                    <p id="lastname-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="othername" class="form-label">Lastname <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="othername" name="othername" class="form-input" value="{{ old('othername', $student->othername) }}" placeholder="Enter Lastname">
                                    <p id="othername-error" class="form-error hidden" aria-live="polite"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Profile photo</label>
                                <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                    <label class="relative cursor-pointer" for="student-profile-image" title="Change photo">
                                        <img id="student-profile-preview" class="w-16 h-16 rounded-full object-cover border-2 shadow-sm" style="border-color: var(--outline-variant);" src="{{ $student->imagelocation ? (str_starts_with($student->imagelocation, 'students/') ? asset('storage/' . $student->imagelocation) : asset('storage/students/' . $student->imagelocation)) : asset('storage/students/default.png') }}" alt="Student" onerror="this.src='https://ui-avatars.com/api/?name=Student&size=128'">
                                    </label>

                                    <input type="file" id="student-profile-image" class="hidden" accept="image/jpeg,image/png,image/jpg" aria-label="Change profile photo">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <label class="btn-secondary cursor-pointer inline-flex items-center px-3 py-2 text-sm mb-0" for="student-profile-image">Change photo</label>
                                        <button type="button" id="student-profile-upload-btn" class="text-xs font-medium px-3 py-2 rounded-full cursor-pointer hidden" style="color: var(--on-surface-variant); background: var(--surface-container-high);">Update photo</button>
                                    </div>
                                </div>
                                <p id="photoimg-error" class="form-error hidden mt-1" aria-live="polite"></p>
                            </div>

                            <div class="form-group">
                                <label for="dob" class="form-label">Date Of Birth <span style="color: var(--primary);">*</span></label>
                                <input type="text" id="dob" name="dob" class="form-input" value="{{ old('dob', $student->dob) }}" placeholder="e.g. 15 May 2010">
                                <p id="dob-error" class="form-error hidden" aria-live="polite"></p>
                            </div>

                            <div class="form-group">
                                <label for="gender" class="form-label">Gender</label>
                                <x-forms.md-select-native id="gender" name="gender" class="form-select">
                                    <option value="Male" {{ old('gender', $student->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $student->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                                </x-forms.md-select-native>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">Mobile number <span style="color: var(--primary);">*</span></label>
                                <input type="text" id="phone" class="form-input" value="{{ old('contact_phone', $student->contact_phone) }}" placeholder="e.g. +234 800 000 0000" autocomplete="off" required aria-label="Mobile number">
                                <p id="contact_phone-error" class="form-error hidden" aria-live="polite"></p>
                            </div>
                            <button type="submit" id="edit-account-btn" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-full text-sm">Update account</button>
                        </form>
                    </div>

                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Academic Profile</h2>
                        </div>

                        <form method="POST" action="{{ route('admin.students.update.academic', $student) }}" id="edit-academic-form" class="p-4 sm:p-5 min-w-0">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="subjects" id="edit-subjects" value="{{ old('subjects', $student->subjects) }}">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                <div class="form-group">
                                    <label for="edit-class" class="form-label">Student's Class <span style="color: var(--primary);">*</span></label>
                                    <x-forms.md-select-native id="edit-class" name="class" class="form-select">
                                        <option value="">Select Class</option>
                                        @foreach($classes as $c)
                                            <option value="{{ e($c->class_name) }}" {{ old('class', $student->class ?? '') === $c->class_name ? 'selected' : '' }}>{{ e($c->class_name) }}</option>
                                        @endforeach
                                    </x-forms.md-select-native>
                                    <p id="class-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="reg_number" class="form-label">ID Number</label>
                                    <input type="number" id="reg_number" name="reg_number" class="form-input" value="{{ old('reg_number', $student->reg_number) }}" placeholder="Student's ID Number" readonly>
                                    <p id="reg_number-error" class="form-error hidden" aria-live="polite"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <span class="form-label mb-0">Subjects To Offer</span>
                                    <div class="flex gap-2 flex-shrink-0">
                                        <button type="button" id="edit-subjects-select-all" class="text-xs font-medium px-2 py-1 rounded-lg transition-colors" style="color: var(--primary); background: var(--primary-container);">Select all</button>
                                        <button type="button" id="edit-subjects-clear" class="text-xs font-medium px-2 py-1 rounded-lg transition-colors" style="color: var(--on-surface-variant); background: var(--surface-container-high);">Clear</button>
                                    </div>
                                </div>

                                <div id="edit-subjects-checkbox-container" class="grid grid-cols-2 sm:grid-cols-3 gap-2 rounded-xl p-2 border max-h-52 overflow-y-auto min-h-[4rem]" style="border-color: var(--outline-variant); background: var(--surface-container-low);"></div>
                                <p id="edit-subjects-placeholder" class="text-xs mt-1.5 hidden" style="color: var(--on-surface-variant);">Select a class above to see subjects.</p>
                                <p id="subjects-error" class="form-error hidden mt-1" aria-live="polite"></p>
                            </div>
                            <button type="submit" id="edit-academic-btn" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-full text-sm">Update academic</button>
                        </form>
                    </div>
                </div>

                <div class="space-y-4 sm:space-y-6">
                    <div class="rounded-xl p-4 sm:p-5 border" style="background: var(--surface-container-low); border-color: var(--card-border);">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-semibold mb-1" style="color: var(--on-surface);">Delete student</h3>
                                <p class="text-sm mb-0" style="color: var(--on-surface-variant);">Permanently remove this student. This action cannot be undone.</p>
                            </div>
                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" id="student-delete-form" class="flex-shrink-0 w-full sm:w-auto">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="student-delete-open-btn w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium transition-opacity hover:opacity-95" style="color: var(--on-error-container); background: var(--error-container);">Delete student</button>
                            </form>
                        </div>
                    </div>

                    <div class="rounded-xl p-4 sm:p-5 border" style="background: var(--surface-container-low); border-color: var(--card-border);">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-semibold mb-1" style="color: var(--on-surface);">Student status</h3>
                                <p class="text-sm mb-0" style="color: var(--on-surface-variant);">Please choose whether the student has <b>left school</b> or is still currently enrolled.</p>
                            </div>
                            <label class="settings-switch flex-shrink-0 self-start md:self-center">
                                <input
                                    type="checkbox"
                                    id="student-status-toggle"
                                    class="settings-switch-input student-status-toggle"
                                    data-status-url="{{ route('admin.students.toggle.status', $student) }}"
                                    data-class-arm="{{ e($student->class_arm ?? '') }}"
                                    {{ (int)($student->status ?? 0) === 1 ? 'checked' : '' }}
                                >
                                <span class="settings-switch-track"></span>
                            </label>
                        </div>
                        <p id="student-status-error" class="form-error hidden mt-2" aria-live="polite"></p>
                    </div>

                    <div class="rounded-xl p-4 sm:p-5 border" style="background: var(--surface-container-low); border-color: var(--card-border);">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-semibold mb-1" style="color: var(--on-surface);">Fee status</h3>
                                <p class="text-sm mb-0" style="color: var(--on-surface-variant);">Mark fee as paid or unpaid for this student.</p>
                            </div>
                            <label class="settings-switch flex-shrink-0 self-start md:self-center">
                                <input type="checkbox" id="fee-status-toggle" class="settings-switch-input fee-status-toggle" data-fee-url="{{ route('admin.students.toggle.fee', $student) }}" {{ (int)($student->fee_status ?? 0) === 1 ? 'checked' : '' }}>
                                <span class="settings-switch-track"></span>
                            </label>
                        </div>
                        <p id="fee-status-error" class="form-error hidden mt-2" aria-live="polite"></p>
                    </div>

                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Contact Address</h2>
                        </div>
                        <form id="edit-contact-form" method="POST" action="{{ route('admin.students.update.contact', $student) }}" class="p-4 sm:p-5 min-w-0">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                <div class="form-group">
                                    <label for="lga" class="form-label">LGA <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="lga" name="lga" class="form-input" value="{{ old('lga', $student->lga) }}" placeholder="Enter LGA">
                                    <p id="lga-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="state" class="form-label">State <span style="color: var(--primary);">*</span></label>
                                    <x-forms.md-select-native id="state" name="state" class="form-select">
                                        <option value="">Select State</option>
                                        @foreach($states as $st)
                                            <option value="{{ e($st) }}" {{ old('state', $student->state) === $st ? 'selected' : '' }}>{{ e($st) }}</option>
                                        @endforeach
                                    </x-forms.md-select-native>
                                    <p id="state-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="city" class="form-label">City <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="city" name="city" class="form-input" value="{{ old('city', $student->city) }}" placeholder="Enter City">
                                    <p id="city-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="country" class="form-label">Country <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="country" name="nationality" class="form-input" value="{{ old('nationality', $student->nationality ?? 'Nigeria') }}" placeholder="Enter Country" readonly>
                                    <p id="nationality-error" class="form-error hidden" aria-live="polite"></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address" class="form-label">Home Address <span style="color: var(--primary);">*</span></label>
                                <textarea id="address" name="address" rows="3" class="form-input" placeholder="Enter full address">{{ old('address', $student->address) }}</textarea>
                                <p id="address-error" class="form-error hidden" aria-live="polite"></p>
                            </div>
                            <button type="submit" id="edit-contact-btn" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-full text-sm">Update contact</button>
                        </form>
                    </div>

                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Parents Information</h2>
                        </div>

                        <form id="edit-parents-form" method="POST" action="{{ route('admin.students.update.parents', $student) }}" class="p-4 sm:p-5 min-w-0">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="formattedPhoneFather" name="father_phone" value="{{ old('father_phone', $student->father_phone) }}">
                            <input type="hidden" id="formattedPhoneMother" name="mother_phone" value="{{ old('mother_phone', $student->mother_phone) }}">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                <div class="form-group">
                                    <label for="father_name" class="form-label">Father's Name</label>
                                    <input type="text" id="father_name" name="father_name" class="form-input" value="{{ old('father_name', $student->father_name) }}" placeholder="Enter Father's Name">
                                    <p id="father_name-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="father_occupation" class="form-label">Father's Occupation</label>
                                    <input type="text" id="father_occupation" name="father_occupation" class="form-input" value="{{ old('father_occupation', $student->father_occupation) }}" placeholder="Enter Father's Occupation">
                                    <p id="father_occupation-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="father_phone" class="form-label">Father's Phone</label>
                                    <input type="text" id="father_phone" class="form-input" value="{{ old('father_phone', $student->father_phone) }}" placeholder="e.g. +234 800 000 0000" autocomplete="off" aria-label="Father's phone">
                                    <p id="father_phone-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="mother_name" class="form-label">Mother's Name</label>
                                    <input type="text" id="mother_name" name="mother_name" class="form-input" value="{{ old('mother_name', $student->mother_name) }}" placeholder="Enter Mother's Name">
                                    <p id="mother_name-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="mother_occupation" class="form-label">Mother's Occupation</label>
                                    <input type="text" id="mother_occupation" name="mother_occupation" class="form-input" value="{{ old('mother_occupation', $student->mother_occupation) }}" placeholder="Enter Mother's Occupation">
                                    <p id="mother_occupation-error" class="form-error hidden" aria-live="polite"></p>
                                </div>

                                <div class="form-group">
                                    <label for="mother_phone" class="form-label">Mother's Phone</label>
                                    <input type="text" id="mother_phone" class="form-input" value="{{ old('mother_phone', $student->mother_phone) }}" placeholder="e.g. +234 800 000 0000" autocomplete="off" aria-label="Mother's phone">
                                    <p id="mother_phone-error" class="form-error hidden" aria-live="polite"></p>
                                </div>
                            </div>
                            <button type="submit" id="edit-parents-btn" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-full text-sm">Update parents</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-4 sm:mt-6">
                <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                        <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Sponsor's information</h2>
                    </div>
                    <form id="edit-sponsors-form" method="POST" action="{{ route('admin.students.update.sponsors', $student) }}" class="p-4 sm:p-5 min-w-0">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="formattedPhoneSponsor" name="sponsor_phone" value="{{ old('sponsor_phone', $student->sponsor_phone) }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                            <div class="form-group">
                                <label for="sponsor_name" class="form-label">Sponsor's Name</label>
                                <input type="text" id="sponsor_name" name="sponsor_name" class="form-input" value="{{ old('sponsor_name', $student->sponsor_name) }}" placeholder="Enter Sponsor's Name">
                                <p id="sponsor_name-error" class="form-error hidden" aria-live="polite"></p>
                            </div>

                            <div class="form-group">
                                <label for="sponsor_occupation" class="form-label">Sponsor's Occupation</label>
                                <input type="text" id="sponsor_occupation" name="sponsor_occupation" class="form-input" value="{{ old('sponsor_occupation', $student->sponsor_occupation) }}" placeholder="Enter Sponsor's Occupation">
                                <p id="sponsor_occupation-error" class="form-error hidden" aria-live="polite"></p>
                            </div>

                            <div class="form-group">
                                <label for="sponsor_phone" class="form-label">Sponsor's Phone</label>
                                <input type="text" id="sponsor_phone" class="form-input" value="{{ old('sponsor_phone', $student->sponsor_phone) }}" placeholder="e.g. +234 800 000 0000" autocomplete="off" aria-label="Sponsor's phone">
                                <p id="sponsor_phone-error" class="form-error hidden" aria-live="polite"></p>
                            </div>

                            <div class="form-group">
                                <label for="relationship" class="form-label">Relationship With Sponsor</label>
                                <input type="text" id="relationship" name="relationship" class="form-input" value="{{ old('relationship', $student->relationship) }}" placeholder="Enter Relationship With Sponsor">
                                <p id="relationship-error" class="form-error hidden" aria-live="polite"></p>
                            </div>

                            <div class="form-group sm:col-span-2">
                                <label for="sponsor_address" class="form-label">Sponsor's Address</label>
                                <textarea id="sponsor_address" name="sponsor_address" rows="3" class="form-input" placeholder="Enter sponsor's full address">{{ old('sponsor_address', $student->sponsor_address) }}</textarea>
                                <p id="sponsor_address-error" class="form-error hidden" aria-live="polite"></p>
                            </div>
                        </div>
                        <button type="submit" id="edit-sponsors-btn" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-full text-sm">Update sponsors</button>
                    </form>
                </div>
            </div>

            <div class="mt-4 sm:mt-6">
                <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                        <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Other Information</h2>
                    </div>

                    <form id="edit-other-form" method="POST" action="{{ route('admin.students.update.other', $student) }}" class="p-4 sm:p-5 min-w-0">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                            <div class="form-group">
                                <label for="house" class="form-label">House</label>
                                <x-forms.md-select-native id="house" name="house" class="form-select">
                                    @foreach($houses as $h)
                                        <option value="{{ e($h) }}" {{ old('house', $student->house) === $h ? 'selected' : '' }}>{{ e($h) }}</option>
                                    @endforeach
                                </x-forms.md-select-native>
                                <p id="house-error" class="form-error hidden" aria-live="polite"></p>
                            </div>

                            <div class="form-group">
                                <label for="category" class="form-label">Category</label>
                                <x-forms.md-select-native id="category" name="category" class="form-select">
                                    <option value="Boarding" {{ old('category', $student->category ?? 'Boarding') === 'Boarding' ? 'selected' : '' }}>Boarding</option>
                                    <option value="Day" {{ old('category', $student->category) === 'Day' ? 'selected' : '' }}>Day</option>
                                </x-forms.md-select-native>
                                <p id="category-error" class="form-error hidden" aria-live="polite"></p>
                            </div>
                        </div>
                        <button type="submit" id="edit-other-btn" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-full text-sm">Update other</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <div id="student-delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="student-delete-modal-title">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="student-delete-modal" aria-hidden="true"></div>
        <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                <h3 id="student-delete-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);">Delete student</h3>
                <p id="student-delete-modal-message" class="text-sm mb-6" style="color: var(--on-surface-variant);">Are you sure you want to delete this student? This action cannot be undone.</p>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                    <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="student-delete-modal">Cancel</button>
                    <button type="button" id="student-delete-modal-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95" style="background: var(--error-container); color: var(--on-error-container);">Delete</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                (function() {
                    const deleteModal = document.getElementById('student-delete-modal');
                    const deleteModalConfirm = document.getElementById('student-delete-modal-confirm');
                    const deleteForm = document.getElementById('student-delete-form');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    if (!deleteModal || !deleteForm) return;
                    function closeDeleteModal() {
                        deleteModal.classList.add('hidden');
                    }
                    document.querySelectorAll('[data-close="student-delete-modal"]').forEach(function(el) {
                        el.addEventListener('click', closeDeleteModal);
                    });
                    document.querySelectorAll('.student-delete-open-btn').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            deleteModal.classList.remove('hidden');
                        });
                    });
                    if (deleteModalConfirm) {
                        deleteModalConfirm.addEventListener('click', function() {
                            const btn = this;
                            if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);
                            fetch(deleteForm.action, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                body: new FormData(deleteForm)
                            })
                            .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); })
                            .then(function(res) {
                                closeDeleteModal();
                                if (res.ok && res.data && res.data.status === 'success') {
                                    if (typeof flashSuccess === 'function') flashSuccess(res.data.message || 'Student deleted.');
                                    const nextUrl = res.data.redirect || @json(route('admin.classes'));
                                    setTimeout(function() { window.location.href = nextUrl; }, window.RELOAD_DELAY_MS);
                                } else {
                                    if (typeof flashError === 'function') flashError(res.data && res.data.message || 'Could not delete student.');
                                }
                            })
                            .catch(function() {
                                closeDeleteModal();
                                if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                            })
                            .finally(function() { if (typeof setButtonLoading === 'function') setButtonLoading(btn, false); });
                        });
                    }
                })();

                const editSubjectsContainer = document.getElementById('edit-subjects-checkbox-container');
                const editSubjectsPlaceholder = document.getElementById('edit-subjects-placeholder');
                const editSubjectsHidden = document.getElementById('edit-subjects');
                const editClassSelect = document.getElementById('edit-class');
                let juniorSubjects = @json($juniorSubjects->map(fn($s) => ['value' => $s->subject_name, 'label' => $s->subject_name])->values());
                let seniorSubjects = @json($seniorSubjects->map(fn($s) => ['value' => $s->subject_name, 'label' => $s->subject_name])->values());
                let initialSubjectsStr = @json(old('subjects', $student->subjects ?? ''));

                function getEditSubjectListForClass() {
                    const classVal = (editClassSelect && editClassSelect.value) || '';
                    if (classVal.indexOf('JSS') === 0) return juniorSubjects;
                    if (classVal.indexOf('SSS') === 0) return seniorSubjects;
                    return [];
                }

                function updateEditSubjectsCheckboxes(initialCheckStr) {
                    const list = getEditSubjectListForClass();
                    if (!editSubjectsContainer) return;
                    editSubjectsContainer.innerHTML = '';
                    if (list.length === 0) {
                        if (editSubjectsPlaceholder) editSubjectsPlaceholder.classList.remove('hidden');
                        return;
                    }
                    if (editSubjectsPlaceholder) editSubjectsPlaceholder.classList.add('hidden');
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
                        cb.className = 'edit-subject-checkbox w-4 h-4 sm:w-5 sm:h-5 rounded border-2 flex-shrink-0 cursor-pointer focus:ring-2 focus:ring-offset-0 focus:outline-none';
                        cb.style.borderColor = 'var(--outline)';
                        cb.style.accentColor = 'var(--primary)';
                        cb.value = s.value;
                        if (preCheck.indexOf(s.value) !== -1) cb.checked = true;
                        const span = document.createElement('span');
                        span.className = 'text-xs sm:text-sm font-medium truncate min-w-0';
                        span.textContent = s.label;
                        label.appendChild(cb);
                        label.appendChild(span);
                        editSubjectsContainer.appendChild(label);
                    });
                }

                if (editClassSelect) editClassSelect.addEventListener('change', function() { updateEditSubjectsCheckboxes(null); });
                updateEditSubjectsCheckboxes(initialSubjectsStr);

                document.getElementById('edit-subjects-select-all') && document.getElementById('edit-subjects-select-all').addEventListener('click', function() {
                    if (editSubjectsContainer) editSubjectsContainer.querySelectorAll('.edit-subject-checkbox').forEach(function(c) { c.checked = true; });
                });
                document.getElementById('edit-subjects-clear') && document.getElementById('edit-subjects-clear').addEventListener('click', function() {
                    if (editSubjectsContainer) editSubjectsContainer.querySelectorAll('.edit-subject-checkbox').forEach(function(c) { c.checked = false; });
                });

                const csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const studentId = @json($student->id);

                function submitFormAjax(form, btn, fieldIds, beforeSubmit) {
                    if (!form || !btn) return;
                    form.addEventListener('submit', function(ev) {
                        ev.preventDefault();
                        if (typeof clearFieldErrors === 'function') clearFieldErrors(fieldIds || []);
                        if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);
                        if (typeof beforeSubmit === 'function') beforeSubmit();
                        fetch(form.action, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: new FormData(form)
                        })
                        .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, status: r.status, data: data }; }); })
                        .then(function(res) {
                            if (res.ok && res.data && res.data.status === 'success') {
                                if (typeof flashSuccess === 'function') flashSuccess(res.data.message || 'Updated.');
                            } else if (res.data && res.data.errors) {
                                if (typeof showLaravelErrors === 'function') showLaravelErrors(res.data.errors);
                            } else {
                                if (typeof flashError === 'function') flashError(Array.isArray(res.data && res.data.message) ? res.data.message.join(' ') : (res.data && res.data.message) || 'Update failed.');
                            }
                        })
                        .catch(function() { if (typeof flashError === 'function') flashError('An error occurred. Please try again.'); })
                        .finally(function() { if (typeof setButtonLoading === 'function') setButtonLoading(btn, false); });
                    });
                }

                submitFormAjax(document.getElementById('edit-account-form'), document.getElementById('edit-account-btn'), ['firstname', 'lastname', 'othername', 'dob', 'contact_phone']);
                submitFormAjax(document.getElementById('edit-academic-form'), document.getElementById('edit-academic-btn'), ['class', 'reg_number', 'subjects'], function() {
                    if (editSubjectsContainer && editSubjectsHidden) {
                        const checked = [].slice.call(editSubjectsContainer.querySelectorAll('.edit-subject-checkbox:checked')).map(function(c) { return c.value; });
                        editSubjectsHidden.value = checked.join(',');
                    }
                });
                submitFormAjax(document.getElementById('edit-contact-form'), document.getElementById('edit-contact-btn'), ['lga', 'state', 'city', 'nationality', 'address']);
                submitFormAjax(document.getElementById('edit-parents-form'), document.getElementById('edit-parents-btn'), ['father_name', 'father_occupation', 'father_phone', 'mother_name', 'mother_occupation', 'mother_phone']);
                submitFormAjax(document.getElementById('edit-sponsors-form'), document.getElementById('edit-sponsors-btn'), ['sponsor_name', 'sponsor_occupation', 'sponsor_phone', 'relationship', 'sponsor_address']);
                submitFormAjax(document.getElementById('edit-other-form'), document.getElementById('edit-other-btn'), ['house', 'category']);

                setTimeout(function() {
                    const p = document.getElementById('phone');
                    if (p && p.value) p.dispatchEvent(new Event('input', { bubbles: true }));
                }, 300);

                (function() {
                    const profilePreview = document.getElementById('student-profile-preview');
                    const profileInput = document.getElementById('student-profile-image');
                    const profileUploadBtn = document.getElementById('student-profile-upload-btn');
                    const photoErrorEl = document.getElementById('photoimg-error');
                    const uploadUrl = @json(route('admin.students.upload-profile'));
                    if (!profilePreview || !profileInput || !profileUploadBtn) return;
                    profileInput.addEventListener('change', function() {
                        const file = this.files[0];
                        if (file && file.type.indexOf('image') === 0) {
                            const r = new FileReader();
                            r.onload = function() { profilePreview.src = r.result; };
                            r.readAsDataURL(file);
                            profileUploadBtn.classList.remove('hidden');
                        }
                        if (photoErrorEl) { photoErrorEl.textContent = ''; photoErrorEl.classList.add('hidden'); }
                    });
                    profileUploadBtn.addEventListener('click', function() {
                        const file = profileInput.files[0];
                        if (!file) {
                            if (typeof flashError === 'function') flashError('Please select an image first.');
                            return;
                        }
                        if (typeof setButtonLoading === 'function') setButtonLoading(profileUploadBtn, true);
                        if (photoErrorEl) { photoErrorEl.textContent = ''; photoErrorEl.classList.add('hidden'); }
                        const fd = new FormData();
                        fd.append('studentId', studentId);
                        fd.append('photoimg', file);
                        fd.append('_token', csrf);
                        fetch(uploadUrl, { method: 'POST', body: fd, headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); })
                        .then(function(res) {
                            if (res.ok && res.data.status === 'success') {
                                if (res.data.image_url) profilePreview.src = res.data.image_url;
                                profileInput.value = '';
                                profileUploadBtn.classList.add('hidden');
                                if (typeof flashSuccess === 'function') flashSuccess(res.data.message || 'Profile picture updated.');
                            } else if (res.data && res.data.errors && typeof showLaravelErrors === 'function') {
                                showLaravelErrors(res.data.errors);
                            } else {
                                if (typeof flashError === 'function') flashError(res.data && res.data.message || 'Upload failed.');
                            }
                        })
                        .catch(function() { if (typeof flashError === 'function') flashError('An error occurred.'); })
                        .finally(function() { if (typeof setButtonLoading === 'function') setButtonLoading(profileUploadBtn, false); });
                    });
                })();
                const scrollEl = document.querySelector('main.overflow-y-auto');

                function scrollBack() { if (scrollEl) setTimeout(function() { scrollEl.scrollTop = (scrollEl._scrollTop || 0); }, 50); }

                document.getElementById('student-status-toggle') && document.getElementById('student-status-toggle').addEventListener('change', function() {
                    const el = this;
                    el.blur();
                    if (scrollEl) scrollEl._scrollTop = scrollEl.scrollTop;
                    const url = el.getAttribute('data-status-url');
                    const classArm = el.getAttribute('data-class-arm') || '';
                    const status = el.checked ? 1 : 2;
                    const prevChecked = !el.checked;
                    fetch(url, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({ status: status, class_arm: classArm })
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.status === 'success') {
                            if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Status updated.');
                        } else {
                            el.checked = prevChecked;
                            if (typeof flashError === 'function') flashError(data.message || 'Update failed.');
                        }
                        scrollBack();
                    })
                    .catch(function() {
                        el.checked = prevChecked;
                        if (typeof flashError === 'function') flashError('An error occurred.');
                        scrollBack();
                    });
                });

                document.getElementById('fee-status-toggle') && document.getElementById('fee-status-toggle').addEventListener('change', function() {
                    const el = this;
                    el.blur();
                    if (scrollEl) scrollEl._scrollTop = scrollEl.scrollTop;
                    const url = el.getAttribute('data-fee-url');
                    const feeStatus = el.checked ? 1 : 2;
                    const prevChecked = !el.checked;
                    fetch(url, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({ fee_status: feeStatus })
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.status === 'success') {
                            if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Fee status updated.');
                        } else {
                            el.checked = prevChecked;
                            if (typeof flashError === 'function') flashError(data.message || 'Update failed.');
                        }
                        scrollBack();
                    })
                    .catch(function() {
                        el.checked = prevChecked;
                        if (typeof flashError === 'function') flashError('An error occurred.');
                        scrollBack();
                    });
                });
            });
        </script>
    @endpush
@endsection
