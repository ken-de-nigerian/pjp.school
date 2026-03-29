@php use Illuminate\Support\Carbon; @endphp
@extends('layouts.app', ['title' => trim(($teacher->firstname ?? '') . ' ' . ($teacher->lastname ?? '')) ?: 'Edit teacher'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Edit teacher"
                pill="Admin"
                title="Edit teacher"
                :description="e(trim(($teacher->firstname ?? '') . ' ' . ($teacher->lastname ?? ''))) . ' — ' . e($teacher->email ?? '')"
            >
                <x-slot name="above">
                    <a href="{{ route('admin.teachers.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to teachers
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                <div class="space-y-4 sm:space-y-6">
                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Profile</h2>
                        </div>

                        <form id="edit-teacher-account-form" method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="p-4 sm:p-5 min-w-0 space-y-4">
                            @csrf
                            @method('PUT')

                            <input type="hidden" id="formattedPhone" name="phone" value="{{ old('phone', $teacher->phone) }}">

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 min-w-0">
                                <div class="form-group">
                                    <label for="firstname" class="form-label">First name <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="firstname" name="firstname" class="form-input w-full min-w-0" value="{{ old('firstname', $teacher->firstname) }}" placeholder="First name" maxlength="255">
                                    <p id="firstname-error" class="form-error mt-1 text-sm {{ $errors->has('firstname') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('firstname') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="lastname" class="form-label">Last name <span style="color: var(--primary);">*</span></label>
                                    <input type="text" id="lastname" name="lastname" class="form-input w-full min-w-0" value="{{ old('lastname', $teacher->lastname) }}" placeholder="Last name" maxlength="255">
                                    <p id="lastname-error" class="form-error mt-1 text-sm {{ $errors->has('lastname') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('lastname') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="othername" class="form-label">Other name</label>
                                    <input type="text" id="othername" name="othername" class="form-input w-full min-w-0" value="{{ old('othername', $teacher->othername) }}" placeholder="Other name" maxlength="255">
                                    <p id="othername-error" class="form-error mt-1 text-sm {{ $errors->has('othername') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('othername') }}</p>
                                </div>
                            </div>

                            <div class="form-group min-w-0">
                                <label for="photoimg" class="form-label">Profile photo</label>
                                <div class="flex flex-wrap items-center gap-3">
                                    <img id="photoimg-preview" class="w-16 h-16 rounded-full object-cover border-2" style="border-color: var(--outline-variant);" src="{{ $teacher->imagelocation ? (str_starts_with($teacher->imagelocation, 'teachers/') ? asset('storage/' . $teacher->imagelocation) : asset('storage/teachers/' . $teacher->imagelocation)) : asset('storage/teachers/default.png') }}" alt="Teacher" onerror="this.src='https://ui-avatars.com/api/?name=T&size=128'">
                                    <label class="btn-secondary cursor-pointer inline-flex items-center gap-2 px-3 py-2 text-sm mb-0" for="photoimg">Select image</label>
                                    <input type="file" id="photoimg" class="hidden" accept="image/jpeg,image/png,image/jpg" aria-label="Change profile photo">
                                    <button type="button" id="teacher-profile-upload-btn" class="text-xs font-medium px-3 py-2 rounded-full cursor-pointer hidden" style="color: var(--on-surface-variant); background: var(--surface-container-high);">
                                        Update photo
                                    </button>
                                </div>

                                <p id="photoimg-error" class="form-error mt-1 text-sm {{ $errors->has('photoimg') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('photoimg') }}</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                                <div class="form-group min-w-0">
                                    <label for="email" class="form-label">Email <span style="color: var(--primary);">*</span></label>
                                    <input type="email" id="email" name="email" class="form-input w-full min-w-0" value="{{ old('email', $teacher->email) }}" placeholder="e.g. teacher@school.com" maxlength="255" autocomplete="email">
                                    <p id="email-error" class="form-error mt-1 text-sm {{ $errors->has('email') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('email') }}</p>
                                </div>

                                <div class="form-group min-w-0">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" id="phone" class="form-input w-full min-w-0" value="{{ old('phone', $teacher->phone) }}" placeholder="e.g. +234 800 000 0000" autocomplete="tel">
                                    <p id="phone-error" class="form-error mt-1 text-sm {{ $errors->has('phone') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('phone') }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 min-w-0">
                                <div class="form-group min-w-0">
                                    <label for="date_of_birth" class="form-label">Date of birth <span style="color: var(--primary);">*</span></label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-input w-full min-w-0" value="{{ old('date_of_birth', $teacher->date_of_birth ? Carbon::parse($teacher->date_of_birth)->format('Y-m-d') : '') }}">
                                    <p id="date_of_birth-error" class="form-error mt-1 text-sm {{ $errors->has('date_of_birth') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('date_of_birth') }}</p>
                                </div>

                                <div class="form-group min-w-0">
                                    <label for="employment_date" class="form-label">Employment date <span style="color: var(--primary);">*</span></label>
                                    <input type="date" id="employment_date" name="employment_date" class="form-input w-full min-w-0" value="{{ old('employment_date', $teacher->employment_date ? Carbon::parse($teacher->employment_date)->format('Y-m-d') : '') }}">
                                    <p id="employment_date-error" class="form-error mt-1 text-sm {{ $errors->has('employment_date') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('employment_date') }}</p>
                                </div>

                                <div class="form-group min-w-0">
                                    <label for="gender" class="form-label">Gender <span style="color: var(--primary);">*</span></label>
                                    <x-forms.md-select-native id="gender" name="gender" class="form-select w-full min-w-0">
                                        <option value="">Select gender</option>
                                        <option value="Male" {{ old('gender', $teacher->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $teacher->gender) === 'Female' ? 'selected' : '' }}>
                                            Female
                                        </option>
                                    </x-forms.md-select-native>
                                    <p id="gender-error" class="form-error mt-1 text-sm {{ $errors->has('gender') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('gender') }}</p>
                                </div>
                            </div>

                            <button type="submit" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-full text-sm mt-2">
                                Update account
                            </button>
                        </form>
                    </div>

                    <div class="rounded-xl p-4 sm:p-5 border" style="background: var(--surface-container-low); border-color: var(--card-border);">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-semibold mb-1" style="color: var(--on-surface);">Delete teacher</h3>
                                <p class="text-sm mb-0" style="color: var(--on-surface-variant);">Permanently remove this teacher. This action cannot be undone.</p>
                            </div>
                            <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" id="teacher-delete-form" class="flex-shrink-0 w-full sm:w-auto">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="teacher-delete-open-btn w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium transition-opacity hover:opacity-95" style="color: var(--on-error-container); background: var(--error-container);">
                                    Delete teacher
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="rounded-xl p-4 sm:p-5 border" style="background: var(--surface-container-low); border-color: var(--card-border);">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-semibold mb-1" style="color: var(--on-surface);">Change Password</h3>
                                <p class="text-sm mb-0" style="color: var(--on-surface-variant);">Set a unique password to protect your account.</p>
                            </div>
                            <div class="flex flex-col items-stretch md:items-end gap-1">
                                <button type="button" class="btn-primary px-4 py-2 rounded-full text-sm w-full sm:w-auto" data-modal="teacherChangePassword">Change Password</button>
                                @if($teacher->password_change_date ?? null)
                                    <p class="text-xs" style="color: var(--on-surface-variant);">Last changed: {{ Carbon::parse($teacher->password_change_date)->format('d M, Y') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 sm:space-y-6">
                    <div class="rounded-xl p-4 sm:p-5 border"
                         style="background: var(--surface-container-low); border-color: var(--card-border);">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-semibold mb-1" style="color: var(--on-surface);">Modify Results</h3>
                                <p class="text-sm mb-0" style="color: var(--on-surface-variant);">When enabled, this teacher can edit uploaded results in the teacher portal. Uploading new results is unchanged.</p>
                            </div>
                            <label class="settings-switch flex-shrink-0 self-start md:self-center">
                                <input type="checkbox" id="modify-results-toggle" class="settings-switch-input"{{ (int)($teacher->modify_results ?? 0) === 1 ? 'checked' : '' }}>
                                <span class="settings-switch-track"></span>
                            </label>
                        </div>
                    </div>

                    <div class="rounded-xl p-4 sm:p-5 border" style="background: var(--surface-container-low); border-color: var(--card-border);">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-semibold mb-1" style="color: var(--on-surface);">Form Teacher Status</h3>
                                <p class="text-sm mb-0" style="color: var(--on-surface-variant);">When enabled, this teacher can add and edit attendance and behavioural records for assigned classes. It does not control result editing.</p>
                            </div>
                            <label class="settings-switch flex-shrink-0 self-start md:self-center">
                                <input type="checkbox" id="form-teacher-toggle" class="settings-switch-input"{{ (int)($teacher->{'form-teacher'} ?? 2) === 1 ? 'checked' : '' }}>
                                <span class="settings-switch-track"></span>
                            </label>
                        </div>
                    </div>

                    <form id="edit-teacher-contact-form" method="POST" action="{{ route('admin.teachers.update-contact', $teacher) }}" class="card-refined rounded-xl p-4 sm:p-5 border" style="border-color: var(--outline-variant);">
                        @csrf

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                            <div class="min-w-0">
                                <h3 class="text-sm sm:text-base font-semibold mb-1" style="color: var(--on-surface);">Contact & address</h3>
                                <p class="text-sm mb-0" style="color: var(--on-surface-variant);">Update the teacher's contact details and address information.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                            <div class="form-group min-w-0">
                                <label for="lga" class="form-label">LGA</label>
                                <input type="text" id="lga" name="lga" class="form-input w-full min-w-0" value="{{ old('lga', $teacher->lga) }}" placeholder="LGA" maxlength="100">
                                <p id="lga-error" class="form-error mt-1 text-sm {{ $errors->has('lga') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('lga') }}</p>
                            </div>

                            <div class="form-group min-w-0">
                                <label for="state" class="form-label">State</label>
                                <x-forms.md-select-native id="state" name="state" class="form-select w-full min-w-0">
                                    <option value="">Select State</option>
                                    @foreach($states as $st)
                                        <option value="{{ e($st) }}" {{ old('state', $teacher->state) === $st ? 'selected' : '' }}>{{ e($st) }}</option>
                                    @endforeach
                                </x-forms.md-select-native>
                                <p id="state-error" class="form-error mt-1 text-sm {{ $errors->has('state') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('state') }}</p>
                            </div>

                            <div class="form-group min-w-0">
                                <label for="city" class="form-label">City</label>
                                <input type="text" id="city" name="city" class="form-input w-full min-w-0" value="{{ old('city', $teacher->city) }}" placeholder="City" maxlength="100">
                                <p id="city-error" class="form-error mt-1 text-sm {{ $errors->has('city') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('city') }}</p>
                            </div>

                            <div class="form-group min-w-0">
                                <label for="country-contact" class="form-label">Country</label>
                                <input type="text" id="country-contact" name="country" class="form-input w-full min-w-0" value="{{ old('country', $teacher->country) }}" placeholder="Country" maxlength="100">
                                <p id="country-contact-error" class="form-error mt-1 text-sm {{ $errors->has('country') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('country') }}</p>
                            </div>
                        </div>

                        <div class="form-group min-w-0 mt-4">
                            <label for="address-contact" class="form-label">Address</label>
                            <textarea id="address-contact" name="address" rows="3" class="form-input w-full min-w-0" placeholder="Full address" maxlength="500">{{ old('address', $teacher->address) }}</textarea>
                            <p id="address-contact-error" class="form-error mt-1 text-sm {{ $errors->has('address') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('address') }}</p>
                        </div>

                        <div class="mt-4">
                            <button type="submit" id="edit-teacher-contact-btn" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-full text-sm">Update contact</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-4 sm:mt-6">
                <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                        <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Academics</h2>
                        <p class="text-xs sm:text-sm mt-1 mb-0" style="color: var(--on-surface-variant);">Choose the class(es) this teacher is assigned to and the subject(s) they will teach.</p>
                    </div>

                    <form id="edit-teacher-employment-form" method="POST" action="{{ route('admin.teachers.update-employment', $teacher) }}" class="p-4 sm:p-5 min-w-0">
                        @csrf
                        @php
                            $assignedClassesForTeacher = $teacher->assigned_class
                                ? array_map('trim', explode(',', $teacher->assigned_class))
                                : [];
                            $subjectsForTeacher = $teacher->subject_to_teach
                                ? array_map('trim', explode(',', $teacher->subject_to_teach))
                                : [];
                            $oldAssignedClass = old('assigned_class', $assignedClassesForTeacher);
                            $oldSubjectToTeach = old('subject_to_teach', $subjectsForTeacher);
                        @endphp

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                            <div class="form-group min-w-0 flex flex-col">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                                    <div>
                                        <label class="form-label mb-0 block">Assigned class(es) <span style="color: var(--primary);">*</span></label>
                                        <p class="text-xs mt-0.5 mb-0" style="color: var(--on-surface-variant);">Classes this teacher will manage or teach.</p>
                                    </div>

                                    <div class="flex gap-2 flex-shrink-0">
                                        <button type="button" id="edit-classes-select-all" class="text-xs font-medium px-3 py-2 rounded-lg transition-opacity hover:opacity-90 min-h-[36px]" style="color: var(--primary); background: var(--primary-container);">
                                            Select all
                                        </button>

                                        <button type="button" id="edit-classes-clear" class="text-xs font-medium px-3 py-2 rounded-lg transition-opacity hover:opacity-90 min-h-[36px]" style="color: var(--on-surface-variant); background: var(--surface-container-high);">
                                            Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 rounded-xl p-3 border min-h-[4.5rem]" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                                    @foreach($getClasses as $c)
                                        <label class="flex items-center gap-2.5 cursor-pointer rounded-lg px-3 py-2.5 border transition-colors min-h-[44px] w-full" style="background: var(--surface-container); border-color: var(--outline-variant); color: var(--on-surface);">
                                            <input type="checkbox" name="assigned_class[]" value="{{ e($c->class_name) }}" class="edit-class-cb w-4 h-4 sm:w-5 sm:h-5 rounded border-2 cursor-pointer flex-shrink-0" style="border-color: var(--outline); accent-color: var(--primary);" {{ in_array($c->class_name, $oldAssignedClass) ? 'checked' : '' }}>
                                            <span class="text-xs font-medium truncate">{{ e($c->class_name) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p id="assigned_class-error" class="form-error mt-2 text-sm {{ $errors->has('assigned_class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('assigned_class') }}</p>
                            </div>

                            <div class="form-group min-w-0 flex flex-col">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                                    <div>
                                        <label class="form-label mb-0 block">Subject(s) to teach <span style="color: var(--primary);">*</span></label>
                                        <p class="text-xs mt-0.5 mb-0" style="color: var(--on-surface-variant);">Subjects this teacher will deliver.</p>
                                    </div>

                                    <div class="flex gap-2 flex-shrink-0">
                                        <button type="button" id="edit-subjects-select-all" class="text-xs font-medium px-3 py-2 rounded-lg transition-opacity hover:opacity-90 min-h-[36px]" style="color: var(--primary); background: var(--primary-container);">
                                            Select all
                                        </button>

                                        <button type="button" id="edit-subjects-clear" class="text-xs font-medium px-3 py-2 rounded-lg transition-opacity hover:opacity-90 min-h-[36px]" style="color: var(--on-surface-variant); background: var(--surface-container-high);">
                                            Clear
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 rounded-xl p-3 border min-h-[4.5rem]" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                                    @foreach($getSubjects as $s)
                                        <label class="flex items-center gap-2.5 cursor-pointer rounded-lg px-3 py-2.5 border transition-colors min-h-[44px] w-full" style="background: var(--surface-container); border-color: var(--outline-variant); color: var(--on-surface);">
                                            <input type="checkbox" name="subject_to_teach[]" value="{{ e($s->subject_name) }}" class="edit-subject-cb w-4 h-4 sm:w-5 sm:h-5 rounded border-2 cursor-pointer flex-shrink-0" style="border-color: var(--outline); accent-color: var(--primary);" {{ in_array($s->subject_name, $oldSubjectToTeach) ? 'checked' : '' }}>
                                            <span class="text-xs font-medium truncate">{{ e($s->subject_name) }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <p id="subject_to_teach-error" class="form-error mt-2 text-sm {{ $errors->has('subject_to_teach') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('subject_to_teach') }}</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" id="edit-teacher-employment-btn" class="btn-primary w-full sm:w-auto px-6 py-2.5 rounded-full text-sm">Update academics</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <div id="teacherChangePassword" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="teacherChangePassword" aria-hidden="true"></div>
        <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--on-surface);">Reset Teacher Password</h3>
                <form id="teacher-password-form" class="min-w-0">
                    @csrf

                    <div class="space-y-4">
                        <div class="form-group">
                            <label for="teacher-new-password" class="form-label">New password</label>
                            <div class="input-group">
                                <input type="password" id="teacher-new-password" name="password" class="form-input" placeholder="Enter new password" autocomplete="off" minlength="8">
                                <button type="button" class="password-toggle" onclick="togglePassword('teacher-new-password', this)" title="Toggle password visibility" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            <p id="teacher-password-error" class="form-error hidden mt-1" aria-live="polite"></p>
                        </div>

                        <div class="form-group">
                            <label for="teacher-confirm-password" class="form-label">Confirm password</label>
                            <div class="input-group">
                                <input type="password" id="teacher-confirm-password" class="form-input" placeholder="Confirm password" autocomplete="off" minlength="8">
                                <button type="button" class="password-toggle" onclick="togglePassword('teacher-confirm-password', this)" title="Toggle password visibility" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            <p id="teacher-confirm-password-error" class="form-error hidden mt-1" aria-live="polite"></p>
                        </div>
                        <p id="teacher-password-form-error" class="form-error hidden mt-1" aria-live="polite"></p>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 mt-6">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="teacherChangePassword">Close</button>
                        <button type="submit" id="teacher-password-btn" class="btn-primary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="teacher-delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="teacher-delete-modal-title">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="teacher-delete-modal" aria-hidden="true"></div>
        <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
            <div class="relative w-full max-w-md min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                <h3 id="teacher-delete-modal-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);">Delete teacher</h3>
                <p id="teacher-delete-modal-message" class="text-sm mb-6" style="color: var(--on-surface-variant);">Are you sure you want to delete this teacher? This action cannot be undone.</p>
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                    <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="teacher-delete-modal">Cancel</button>
                    <button type="button" id="teacher-delete-modal-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95" style="background: var(--error-container); color: var(--on-error-container);">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                // Small helper: clear specific field error <p> blocks by ID
                function clearLocalFieldErrors(ids) {
                    if (!Array.isArray(ids)) return;
                    ids.forEach(function (id) {
                        const el = document.getElementById(id + '-error');
                        if (el) {
                            el.textContent = '';
                            el.classList.add('hidden');
                        }
                    });
                }

                // Generic AJAX form helper (mirrors students edit)
                function submitAjaxForm(form, btn, localFieldIds) {
                    if (!form || !btn) return;
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();

                        clearLocalFieldErrors(localFieldIds || []);

                        if (typeof setButtonLoading === 'function') {
                            setButtonLoading(btn, true);
                        }

                        fetch(form.action, {
                            method: form.method && form.method.toUpperCase() === 'GET' ? 'GET' : 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: form.method && form.method.toUpperCase() === 'GET' ? null : new FormData(form),
                        })
                            .then(r => r.json().then(data => ({ ok: r.ok, status: r.status, data })))
                            .then(res => {
                                if (res.ok && res.data && res.data.status === 'success') {
                                    if (typeof flashSuccess === 'function') {
                                        flashSuccess(res.data.message || 'Updated successfully.');
                                    }
                                } else if (res.data && res.data.errors && typeof showLaravelErrors === 'function') {
                                    showLaravelErrors(res.data.errors);
                                } else if (typeof flashError === 'function') {
                                    const msg = Array.isArray(res.data && res.data.message)
                                        ? res.data.message.join(' ')
                                        : (res.data && res.data.message) || 'Update failed.';
                                    flashError(msg);
                                }
                            })
                            .catch(() => {
                                if (typeof flashError === 'function') {
                                    flashError('An error occurred. Please try again.');
                                }
                            })
                            .finally(() => {
                                if (typeof setButtonLoading === 'function') {
                                    setButtonLoading(btn, false);
                                }
                            });
                    });
                }

                // Profile image upload (AJAX, like students)
                (function () {
                    const profilePreview = document.getElementById('photoimg-preview');
                    const profileInput = document.getElementById('photoimg');
                    const profileUploadBtn = document.getElementById('teacher-profile-upload-btn');
                    const photoErrorEl = document.getElementById('photoimg-error');
                    const uploadUrl = @json(route('admin.teachers.upload-profile', $teacher));

                    if (!profilePreview || !profileInput || !profileUploadBtn) return;

                    profileInput.addEventListener('change', function () {
                        const file = this.files[0];
                        if (file && file.type.indexOf('image') === 0) {
                            const r = new FileReader();
                            r.onload = function () {
                                profilePreview.src = r.result;
                            };
                            r.readAsDataURL(file);
                            profileUploadBtn.classList.remove('hidden');
                        }
                        if (photoErrorEl) {
                            photoErrorEl.textContent = '';
                            photoErrorEl.classList.add('hidden');
                        }
                    });

                    profileUploadBtn.addEventListener('click', function () {
                        const file = profileInput.files[0];
                        if (!file) {
                            if (typeof flashError === 'function') {
                                flashError('Please select an image first.');
                            }
                            return;
                        }
                        if (typeof setButtonLoading === 'function') setButtonLoading(profileUploadBtn, true);
                        if (photoErrorEl) {
                            photoErrorEl.textContent = '';
                            photoErrorEl.classList.add('hidden');
                        }

                        const fd = new FormData();
                        fd.append('photoimg', file);
                        fd.append('_token', csrf || '');

                        fetch(uploadUrl, {
                            method: 'POST',
                            body: fd,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        })
                            .then(r => r.json().then(data => ({ ok: r.ok, data })))
                            .then(res => {
                                const data = res.data || {};
                                if (res.ok && data.status === 'success') {
                                    profileInput.value = '';
                                    profileUploadBtn.classList.add('hidden');
                                    if (typeof flashSuccess === 'function') {
                                        flashSuccess(data.message || 'Profile picture updated.');
                                    }
                                } else if (data.errors && typeof showLaravelErrors === 'function') {
                                    showLaravelErrors(data.errors);
                                } else if (typeof flashError === 'function') {
                                    flashError(data.message || 'Upload failed.');
                                }
                            })
                            .catch(() => {
                                if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                            })
                            .finally(() => {
                                if (typeof setButtonLoading === 'function') setButtonLoading(profileUploadBtn, false);
                            });
                    });
                })();

                // Account, contact, and academics forms submit/validate via shared AJAX helper
                submitAjaxForm(
                    document.getElementById('edit-teacher-account-form'),
                    document.querySelector('#edit-teacher-account-form button[type="submit"]'),
                    ['firstname', 'lastname', 'othername', 'email', 'phone', 'date_of_birth', 'employment_date', 'gender']
                );

                submitAjaxForm(
                    document.getElementById('edit-teacher-contact-form'),
                    document.getElementById('edit-teacher-contact-btn'),
                    ['lga', 'state', 'city', 'country-contact', 'address-contact']
                );

                const employmentForm = document.getElementById('edit-teacher-employment-form');
                if (employmentForm) {
                    const employmentBtn = document.getElementById('edit-teacher-employment-btn');
                    submitAjaxForm(
                        employmentForm,
                        employmentBtn,
                        ['employment_date', 'assigned_class', 'subject_to_teach']
                    );
                    // Academics select all / clear
                    const editClassesSelectAll = document.getElementById('edit-classes-select-all');
                    const editClassesClear = document.getElementById('edit-classes-clear');
                    const editSubjectsSelectAll = document.getElementById('edit-subjects-select-all');
                    const editSubjectsClear = document.getElementById('edit-subjects-clear');
                    if (editClassesSelectAll) {
                        editClassesSelectAll.addEventListener('click', function () {
                            document.querySelectorAll('.edit-class-cb').forEach(cb => { cb.checked = true; });
                        });
                    }
                    if (editClassesClear) {
                        editClassesClear.addEventListener('click', function () {
                            document.querySelectorAll('.edit-class-cb').forEach(cb => { cb.checked = false; });
                        });
                    }
                    if (editSubjectsSelectAll) {
                        editSubjectsSelectAll.addEventListener('click', function () {
                            document.querySelectorAll('.edit-subject-cb').forEach(cb => { cb.checked = true; });
                        });
                    }
                    if (editSubjectsClear) {
                        editSubjectsClear.addEventListener('click', function () {
                            document.querySelectorAll('.edit-subject-cb').forEach(cb => { cb.checked = false; });
                        });
                    }
                }

                // Form teacher status toggle
                const formTeacherToggle = document.getElementById('form-teacher-toggle');
                if (formTeacherToggle) {
                    formTeacherToggle.addEventListener('change', function () {
                        const checked = this.checked;
                        fetch('{{ route('admin.teachers.form-teacher-status', $teacher) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new URLSearchParams({
                                form_teacher: checked ? '1' : '2',
                            }),
                        })
                            .then(r => r.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Form teacher status updated.');
                                } else {
                                    if (typeof flashError === 'function') flashError(data.message || 'Could not update status.');
                                }
                            })
                            .catch(() => {
                                if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                            });
                    });
                }

                // Modify results toggle
                const modifyToggle = document.getElementById('modify-results-toggle');
                if (modifyToggle) {
                    modifyToggle.addEventListener('change', function () {
                        const checked = this.checked;
                        fetch('{{ route('admin.teachers.modify-results', $teacher) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: new URLSearchParams({
                                modify_results: checked ? '1' : '2',
                            }),
                        })
                            .then(r => r.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Modify results permission updated.');
                                } else {
                                    if (typeof flashError === 'function') flashError(data.message || 'Could not update permission.');
                                }
                            })
                            .catch(() => {
                                if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                            });
                    });
                }

                // Delete teacher modal + AJAX (mirrors student delete UX)
                (function () {
                    const deleteModal = document.getElementById('teacher-delete-modal');
                    const deleteModalConfirm = document.getElementById('teacher-delete-modal-confirm');
                    const deleteForm = document.getElementById('teacher-delete-form');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || csrf;

                    if (!deleteModal || !deleteForm) return;

                    function closeDeleteModal() {
                        deleteModal.classList.add('hidden');
                    }

                    document.querySelectorAll('[data-close="teacher-delete-modal"]').forEach(function (el) {
                        el.addEventListener('click', closeDeleteModal);
                    });

                    document.querySelectorAll('.teacher-delete-open-btn').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            deleteModal.classList.remove('hidden');
                        });
                    });

                    if (deleteModalConfirm) {
                        deleteModalConfirm.addEventListener('click', function () {
                            const btn = this;
                            if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);

                            fetch(deleteForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: new FormData(deleteForm),
                            })
                                .then(r => r.json().then(data => ({ ok: r.ok, data })))
                                .then(res => {
                                    closeDeleteModal();
                                    if (res.ok && res.data && res.data.status === 'success') {
                                        if (typeof flashSuccess === 'function') {
                                            flashSuccess(res.data.message || 'Teacher deleted.');
                                        }
                                        const nextUrl = res.data.redirect || @json(route('admin.teachers.index'));
                                        setTimeout(function () {
                                            window.location.href = nextUrl;
                                        }, window.RELOAD_DELAY_MS);
                                    } else if (typeof flashError === 'function') {
                                        flashError(res.data && res.data.message || 'Could not delete teacher.');
                                    }
                                })
                                .catch(() => {
                                    closeDeleteModal();
                                    if (typeof flashError === 'function') {
                                        flashError('An error occurred. Please try again.');
                                    }
                                })
                                .finally(() => {
                                    if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                                });
                        });
                    }
                })();

                // Change password modal open/close
                document.querySelectorAll('[data-modal="teacherChangePassword"]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        document.getElementById('teacherChangePassword')?.classList.remove('hidden');
                    });
                });
                document.querySelectorAll('[data-close="teacherChangePassword"]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        document.getElementById('teacherChangePassword')?.classList.add('hidden');
                    });
                });

                // Change password submit
                const passwordForm = document.getElementById('teacher-password-form');
                if (passwordForm) {
                    passwordForm.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const password = document.getElementById('teacher-new-password').value || '';
                        const confirm = document.getElementById('teacher-confirm-password').value || '';
                        const passwordError = document.getElementById('teacher-password-error');
                        const confirmError = document.getElementById('teacher-confirm-password-error');
                        const formError = document.getElementById('teacher-password-form-error');

                        if (passwordError) { passwordError.textContent = ''; passwordError.classList.add('hidden'); }
                        if (confirmError) { confirmError.textContent = ''; confirmError.classList.add('hidden'); }
                        if (formError) { formError.textContent = ''; formError.classList.add('hidden'); }

                        if (password.length < 8) {
                            if (passwordError) {
                                passwordError.textContent = 'Password must be at least 8 characters.';
                                passwordError.classList.remove('hidden');
                            }
                            return;
                        }
                        if (password !== confirm) {
                            if (confirmError) {
                                confirmError.textContent = 'Passwords do not match.';
                                confirmError.classList.remove('hidden');
                            }
                            return;
                        }

                        const btn = document.getElementById('teacher-password-btn');
                        if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);

                        const body = new URLSearchParams({
                            password: password,
                        });

                        fetch('{{ route('admin.teachers.reset-password', $teacher) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body,
                        })
                            .then(r => r.json().then(data => ({ ok: r.ok, data })))
                            .then(res => {
                                const data = res.data;
                                if (res.ok && data.status === 'success') {
                                    if (typeof flashSuccess === 'function') flashSuccess(data.message || 'Password changed.');
                                    document.getElementById('teacherChangePassword')?.classList.add('hidden');
                                } else if (data.errors && typeof showLaravelErrors === 'function') {
                                    showLaravelErrors(data.errors);
                                } else if (typeof flashError === 'function') {
                                    flashError(Array.isArray(data.message) ? data.message.join(' ') : (data.message || 'Update failed.'));
                                }
                            })
                            .catch(() => {
                                if (typeof flashError === 'function') flashError('An error occurred. Please try again.');
                            })
                            .finally(() => {
                                if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                            });
                    });
                }
            })();
        </script>
    @endpush
@endsection
