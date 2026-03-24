@extends('layouts.app', ['title' => 'Take behavioural record'])

@section('content')
    @php
        $behaviorFields = [
            'neatness'      => ['label' => 'Neatness',      'icon' => 'fa-broom'],
            'music'         => ['label' => 'Music',         'icon' => 'fa-music'],
            'sports'        => ['label' => 'Sports',        'icon' => 'fa-futbol'],
            'attentiveness' => ['label' => 'Attentiveness', 'icon' => 'fa-eye'],
            'punctuality'   => ['label' => 'Punctuality',   'icon' => 'fa-clock'],
            'health'        => ['label' => 'Health',        'icon' => 'fa-heartbeat'],
            'politeness'    => ['label' => 'Politeness',    'icon' => 'fa-hand-holding-heart'],
        ];
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Take behavioural analysis"
                pill="Teacher"
                title="Behavioural Analysis"
                description="Record observations for Neatness, Music, Sports, Attentiveness, Punctuality, Health and Politeness. Save when done."
            >
                <x-slot name="above">
                    <a href="{{ route('teacher.behavioral.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to Behavioural Analysis
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <div class="flex flex-wrap gap-3 sm:gap-4 mb-6">
                <div class="rounded-xl px-4 py-2.5" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium uppercase tracking-wider" style="color: var(--on-surface-variant);">Class</span>
                    <p class="text-sm font-semibold mt-0.5" style="color: var(--on-surface);">{{ $class }}</p>
                </div>

                <div class="rounded-xl px-4 py-2.5" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium uppercase tracking-wider" style="color: var(--on-surface-variant);">Term</span>
                    <p class="text-sm font-semibold mt-0.5" style="color: var(--on-surface);">{{ $term }}</p>
                </div>

                <div class="rounded-xl px-4 py-2.5" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <span class="text-xs font-medium uppercase tracking-wider" style="color: var(--on-surface-variant);">Session</span>
                    <p class="text-sm font-semibold mt-0.5" style="color: var(--on-surface);">{{ $session }}</p>
                </div>
            </div>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                @if($students->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                            <i class="fas fa-user-graduate text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No students in this class</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">There are no active students in {{ $class }}. Add or assign students to take behavioural analysis.</p>
                        <div class="flex justify-center">
                            <a href="{{ route('teacher.behavioral.index') }}"
                               class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                <i class="fas fa-arrow-left text-sm"></i>
                                Back to Behavioural Analysis
                            </a>
                        </div>
                    </div>
                @else
                    <form id="behavioral-form" class="flex flex-col min-h-0">
                        @csrf
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 sm:px-6 py-4" style="border-bottom: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <p class="text-sm font-medium" style="color: var(--on-surface-variant);">
                                <span id="behavioral-count">{{ $students->count() }}</span> student(s) Â· Rate each trait below
                            </p>
                        </div>

                        <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0" style="border-color: var(--outline-variant);">
                            {{-- Desktop: table-like header (sticky) --}}
                            <div class="hidden lg:grid lg:grid-cols-behavioral sticky top-0 z-10 px-4 sm:px-6 py-3 gap-2 text-xs font-semibold uppercase tracking-wider" style="background: var(--surface-container); border-bottom: 1px solid var(--outline-variant); color: var(--on-surface-variant);">
                                <span class="lg:pl-2">#</span>
                                <span class="min-w-0">Student</span>
                                @foreach($behaviorFields as $key => $config)
                                    <span class="min-w-0 truncate" title="{{ $config['label'] }}">{{ $config['label'] }}</span>
                                @endforeach
                            </div>

                            <ul class="divide-y divide-[var(--outline-variant)]" role="list">
                                @foreach($students as $index => $student)
                                    @php
                                        $fullName = trim(($student->firstname ?? '') . ' ' . ($student->lastname ?? '') . ' ' . ($student->othername ?? ''));
                                        $avatarSrc = $student->imagelocation
                                            ? (str_starts_with($student->imagelocation, 'students/') ? asset('storage/' . $student->imagelocation) : asset('storage/students/' . $student->imagelocation))
                                            : asset('storage/students/default.png');
                                        $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : 'S';
                                    @endphp
                                    <li class="behavioral-row flex flex-col lg:grid lg:grid-cols-behavioral gap-4 lg:gap-2 lg:items-stretch px-4 sm:px-6 py-5 lg:py-3 transition-colors" style="background: var(--surface-container-lowest);">
                                        <span class="flex-shrink-0 w-8 h-8 rounded-xl flex items-center justify-center text-sm font-semibold lg:place-self-center" style="background: var(--primary-container); color: var(--on-primary-container);">{{ $index + 1 }}</span>
                                        <div class="flex items-center gap-3 min-w-0 lg:py-1 lg:pr-2">
                                            <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                            <div class="flex flex-col justify-center min-w-0">
                                                <p class="text-sm font-semibold break-words" style="color: var(--on-surface);">
                                                    {{ $fullName ?: 'â€”' }}
                                                </p>
                                                <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">{{ $student->reg_number ?? '' }}</p>
                                            </div>
                                        </div>
                                        {{-- Trait inputs --}}
                                        @foreach($behaviorFields as $key => $config)
                                            <div class="form-group min-w-0 flex flex-col lg:py-1">
                                                <label for="behavioral-{{ $index }}-{{ $key }}" class="form-label lg:sr-only flex items-center gap-2 lg:gap-0">
                                                    <i class="fas {{ $config['icon'] }} text-xs opacity-70" style="color: var(--on-surface-variant);" aria-hidden="true"></i>
                                                    <span>{{ $config['label'] }}</span>
                                                </label>
                                                <textarea id="behavioral-{{ $index }}-{{ $key }}" name="behavioral[{{ $index }}][{{ $key }}]" class="form-input behavioral-field w-full resize-y rounded-xl border min-h-[3.5rem] lg:min-h-[2.5rem] text-sm py-2 px-3" rows="2" maxlength="255" placeholder="Comment or rating" data-reg="{{ e($student->reg_number) }}" data-name="{{ e($fullName) }}" data-field="{{ $key }}" data-row="{{ $index }}" style="border-color: var(--outline-variant); background: var(--surface-container-lowest);"></textarea>
                                            </div>
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-5 sm:px-6 py-5" style="border-top: 1px solid var(--outline-variant); background: var(--surface-container-low);">
                            <p class="text-xs order-2 sm:order-1" style="color: var(--on-surface-variant);">All fields are optional. Use brief comments or grades (e.g. Good, Fair, A).</p>
                            <button type="submit" id="save-behavioral-btn" class="btn-primary order-1 sm:order-2 inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                <i class="fas fa-save text-sm" aria-hidden="true"></i>
                                Save behavioural analysis
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </main>

    <style>
        .lg\:grid-cols-behavioral {
            grid-template-columns: 3rem minmax(11rem, 1.4fr) repeat(7, minmax(0, 1fr));
        }
        @media (max-width: 1023px) {
            .lg\:grid-cols-behavioral { grid-template-columns: 1fr; }
        }
        /* Prevent over-scrolling and hide scrollbar on desktop */
        @media (min-width: 1024px) {
            html {
                overflow: hidden;
                height: 100%;
            }
        }
    </style>

    @push('scripts')
        @if(!$students->isEmpty())
            <script>
                (function() {
                    const form = document.getElementById('behavioral-form');
                    const saveBtn = document.getElementById('save-behavioral-btn');
                    const classVal = @json($class);
                    const termVal = @json($term);
                    const sessionVal = @json($session);
                    const fieldKeys = @json(array_keys($behaviorFields));

                    form.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        setButtonLoading(saveBtn, true);

                        const rows = document.querySelectorAll('.behavioral-row');
                        const students = [];
                        rows.forEach((row) => {
                            const firstField = row.querySelector('.behavioral-field');
                            const regNumber = firstField ? firstField.dataset.reg || '' : '';
                            const name = firstField ? firstField.dataset.name || '' : '';
                            const student = {
                                class: classVal,
                                term: termVal,
                                session: sessionVal,
                                name: name,
                                reg_number: regNumber
                            };
                            fieldKeys.forEach(function(key) {
                                const input = row.querySelector('[data-field="' + key + '"]');
                                student[key] = input ? (input.value || '').trim().slice(0, 255) : '';
                            });
                            students.push(student);
                        });

                        try {
                            const res = await fetch('{{ route("teacher.behavioral.save") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({ students: students })
                            });
                            const data = await res.json().catch(() => ({}));
                            if (res.ok && data.status === 'success') {
                                flashSuccess(data.message || 'Behavioural analysis saved.');
                                setTimeout(function() { window.location.reload(); }, 2800);
                            } else {
                                flashError(data.message || 'Failed to save behavioural analysis.');
                            }
                        } catch (err) {
                            flashError('Network error. Please try again.');
                        }
                        setButtonLoading(saveBtn, false);
                    });
                })();
            </script>
        @endif
    @endpush
@endsection
