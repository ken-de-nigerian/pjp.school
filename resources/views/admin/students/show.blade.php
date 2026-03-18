@extends('layouts.app')

@section('content')
    <main class="flex-1 overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
            <div class="mb-4 sm:mb-6 w-fit">
                <a href="{{ route('admin.classes') }}" class="inline-flex items-center gap-2 text-sm font-medium transition-opacity hover:opacity-80" style="color: var(--on-surface-variant);">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    Back to Students
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-xl px-4 py-3 text-sm font-medium" style="background: var(--primary-container); color: var(--on-primary-container);" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $fullName = trim(e($student->firstname . ' ' . $student->lastname . ($student->othername ? ' ' . $student->othername : '')));
                $profileImageSrc = $student->imagelocation
                    ? (str_starts_with($student->imagelocation, 'students/') ? asset('storage/' . $student->imagelocation) : asset('storage/students/' . $student->imagelocation))
                    : asset('storage/students/default.png');
            @endphp

            <header class="mb-6 lg:mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 min-w-0">
                    <img class="w-20 h-20 rounded-full object-cover border-2 flex-shrink-0" style="border-color: var(--outline-variant);" src="{{ $profileImageSrc }}" alt="" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($fullName ?: 'S') }}&size=160'">
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-normal tracking-tight mb-1.5" style="color: var(--on-surface); letter-spacing: -0.02em;">
                            {{ $fullName ?: '—' }}
                        </h1>
                        <p class="text-sm sm:text-base font-normal" style="color: var(--on-surface-variant);">
                            {{ e($student->reg_number) }} · {{ e($student->class ?? '—') }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('admin.students.edit', $student->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 sm:py-2.5 rounded-xl text-sm font-medium transition-opacity hover:opacity-95" style="background-color: var(--primary); color: var(--on-primary); border-radius: 12px;">
                        <i class="fas fa-pen text-xs" aria-hidden="true"></i>
                        <span>Edit Student</span>
                    </a>
                </div>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8">
                <div class="space-y-4 sm:space-y-6">
                    {{-- Profile --}}
                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Profile</h2>
                        </div>
                        <div class="p-4 sm:p-5 min-w-0 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Date of birth</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->dob ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Gender</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->gender ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Mobile number</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->contact_phone ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Registration number</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->reg_number ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Class</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->class ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">House</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->house ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Category</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->category ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Status</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">
                                        @if((int)($student->status ?? 0) === 2) Active
                                        @elseif((int)($student->status ?? 0) === 1) Left school
                                        @else Inactive
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Fee status</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ (int)($student->fee_status ?? 0) === 1 ? 'Paid' : 'Unpaid' }}</p>
                                </div>
                                @if($student->time_of_reg)
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Date registered</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ $student->time_of_reg->format('j M Y') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Contact address --}}
                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Contact address</h2>
                        </div>
                        <div class="p-4 sm:p-5 min-w-0 space-y-4">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Home address</p>
                                <p class="text-sm font-medium whitespace-pre-wrap" style="color: var(--on-surface);">{{ e($student->address ?? '—') }}</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">City</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->city ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">LGA</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->lga ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">State</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->state ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Nationality</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->nationality ?? '—') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 sm:space-y-6">
                    {{-- Parents --}}
                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Parents information</h2>
                        </div>
                        <div class="p-4 sm:p-5 min-w-0 space-y-4">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Father's name</p>
                                <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->father_name ?? '—') }}</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Father's occupation</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->father_occupation ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Father's phone</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->father_phone ?? '—') }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Mother's name</p>
                                <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->mother_name ?? '—') }}</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Mother's occupation</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->mother_occupation ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Mother's phone</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->mother_phone ?? '—') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sponsor --}}
                    <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                        <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                            <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Sponsor's information</h2>
                        </div>
                        <div class="p-4 sm:p-5 min-w-0 space-y-4">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Sponsor's name</p>
                                <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->sponsor_name ?? '—') }}</p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Occupation</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->sponsor_occupation ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Phone</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->sponsor_phone ?? '—') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Relationship</p>
                                    <p class="text-sm font-medium" style="color: var(--on-surface);">{{ e($student->relationship ?? '—') }}</p>
                                </div>
                            </div>
                            @if(!empty(trim($student->sponsor_address ?? '')))
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Sponsor's address</p>
                                <p class="text-sm font-medium whitespace-pre-wrap" style="color: var(--on-surface);">{{ e($student->sponsor_address) }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($student->subjects)
            @php
                $subjectList = array_filter(array_map('trim', preg_split('/[,;\n]+/', $student->subjects)));
            @endphp
            <div class="mt-4 sm:mt-6">
                <div class="card-refined rounded-xl overflow-hidden" style="border-color: var(--outline-variant);">
                    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b" style="border-color: var(--card-border);">
                        <h2 class="text-sm sm:text-base font-semibold" style="color: var(--on-surface);">Subjects offered</h2>
                    </div>
                    <div class="p-4 sm:p-5 min-w-0">
                        <div class="flex flex-wrap gap-2">
                            @foreach($subjectList as $subject)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium" style="background: var(--primary-container); color: var(--on-primary-container);">{{ e($subject) }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($student->left_school_date || $student->graduation_date)
            <div class="mt-4 sm:mt-6">
                <div class="rounded-xl p-4 sm:p-5 border" style="background: var(--surface-container-low); border-color: var(--card-border);">
                    <h3 class="text-sm sm:text-base font-semibold mb-2" style="color: var(--on-surface);">Other dates</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($student->left_school_date)
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Left school date</p>
                            <p class="text-sm font-medium" style="color: var(--on-surface);">{{ $student->left_school_date->format('j M Y') }}</p>
                        </div>
                        @endif
                        @if($student->graduation_date)
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide mb-0.5" style="color: var(--on-surface-variant);">Graduation date</p>
                            <p class="text-sm font-medium" style="color: var(--on-surface);">{{ $student->graduation_date->format('j M Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>
@endsection
