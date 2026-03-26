@extends('layouts.app', ['title' => trim(($applicant->candidates_surname ?? '') . ', ' . ($applicant->candidates_firstname ?? '') . ' ' . ($applicant->candidates_middlename ?? '')) ?: 'Entrance application'])

@php
    $a = $applicant;
    $name = trim(($a->candidates_surname ?? '') . ', ' . ($a->candidates_firstname ?? '') . ' ' . ($a->candidates_middlename ?? ''));
    $fatherName = trim(($a->fathers_surname ?? '') . ' ' . ($a->fathers_firstname ?? '') . ' ' . ($a->fathers_middlename ?? ''));
    $motherName = trim(($a->mothers_surname ?? '') . ' ' . ($a->mothers_firstname ?? '') . ' ' . ($a->mothers_middlename ?? ''));
    $guardianName = trim(($a->guardians_surname ?? '') . ' ' . ($a->guardians_firstname ?? '') . ' ' . ($a->guardians_middlename ?? ''));
    $avatarForInitial = trim(($a->candidates_firstname ?? '') . ' ' . ($a->candidates_surname ?? ''));
    $avatarInitialShow = $avatarForInitial !== '' ? mb_substr($avatarForInitial, 0, 1) : 'A';
    $avatarSrcShow = asset('storage/students/default.png');
@endphp

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Entrance applicant"
                pill="Admin"
                :title="$name ?: 'Applicant'"
                :description="'ID: ' . e($a->uniqueID ?? $a->id ?? '—')"
            >
                <x-slot name="above">
                    <a href="{{ route('admin.online_entrance.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to applicants
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); box-shadow: var(--elevation-1);">
                <div class="rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                    <div class="px-6 sm:px-8 py-5 border-b flex flex-col sm:flex-row sm:items-center gap-4" style="border-color: var(--outline-variant); background: var(--surface-container);">
                        <img src="{{ $avatarSrcShow }}" alt="" width="56" height="56" class="w-14 h-14 rounded-full object-cover flex-shrink-0 border-2 hidden sm:block" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitialShow) }}&size=96'">
                        <div class="min-w-0">
                            <h2 class="text-xl sm:text-2xl font-medium mb-1" style="color: var(--on-surface);">{{ $name ?: 'Applicant' }}</h2>
                            <p class="text-sm" style="color: var(--on-surface-variant);">ID: {{ $a->uniqueID ?? $a->id ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[320px]" style="border-collapse: collapse;">
                            <thead>
                                <tr style="background: var(--surface-container);">
                                    <th scope="col" class="text-left text-xs font-semibold uppercase tracking-wider py-4 pl-6 sm:pl-8 pr-4 w-[40%] sm:w-[36%] max-w-[200px]" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Field</th>
                                    <th scope="col" class="text-left text-xs font-semibold uppercase tracking-wider py-4 pl-4 pr-6 sm:pr-8" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="background: var(--surface-container-lowest);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Gender</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">
                                        @if(trim((string)($a->selectgender ?? '')) !== '')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($a->selectgender) }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr style="background: var(--surface-container-low);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Date of birth</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e($a->candidates_date_of_birth ?? '—') }}</td>
                                </tr>
                                <tr style="background: var(--surface-container-lowest);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Current school</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e($a->candidates_current_school ?? '—') }}</td>
                                </tr>
                                <tr style="background: var(--surface-container-low);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Current class</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">
                                        @if(trim((string)($a->candidates_current_class ?? '')) !== '')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($a->candidates_current_class) }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                @if(isset($a->applying_for) && trim((string) $a->applying_for) !== '')
                                <tr style="background: var(--surface-container-lowest);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Applying for</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--primary-container); color: var(--on-primary-container);">{{ e($a->applying_for) }}</span>
                                    </td>
                                </tr>
                                @endif
                                <tr style="background: var(--surface-container-lowest);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Nationality</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">
                                        @if(trim((string)($a->candidates_nationality ?? '')) !== '')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($a->candidates_nationality) }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr style="background: var(--surface-container-low);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">State / LGA</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e(($a->states ?? '') . ' / ' . ($a->candidates_lga ?? '—')) }}</td>
                                </tr>
                                <tr style="background: var(--surface-container-lowest);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Father's name</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ $fatherName ?: '—' }}</td>
                                </tr>
                                <tr style="background: var(--surface-container-low);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Father's occupation</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e($a->fathers_occupation ?? '—') }}</td>
                                </tr>
                                <tr style="background: var(--surface-container-lowest);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Father's address</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e($a->fathers_address ?? '—') }}</td>
                                </tr>
                                @if(isset($a->fathers_phone) && trim((string) $a->fathers_phone) !== '')
                                <tr style="background: var(--surface-container-low);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Father's phone</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e($a->fathers_phone) }}</td>
                                </tr>
                                @endif
                                <tr style="background: var(--surface-container-low);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Mother's name</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ $motherName ?: '—' }}</td>
                                </tr>
                                <tr style="background: var(--surface-container-lowest);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Mother's occupation</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e($a->mothers_occupation ?? '—') }}</td>
                                </tr>
                                <tr style="background: var(--surface-container-low);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Mother's address</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e($a->mothers_address ?? '—') }}</td>
                                </tr>
                                @if(isset($a->mothers_phone) && trim((string) $a->mothers_phone) !== '')
                                <tr style="background: var(--surface-container-lowest);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Mother's phone</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e($a->mothers_phone) }}</td>
                                </tr>
                                @endif
                                @if($guardianName || trim((string)($a->guardians_occupation ?? '')) !== '' || trim((string)($a->guardians_address ?? '')) !== '' || (isset($a->guardians_phone) && trim((string) $a->guardians_phone) !== ''))
                                <tr style="background: var(--surface-container-lowest);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Guardian's name</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ $guardianName ?: '—' }}</td>
                                </tr>
                                <tr style="background: var(--surface-container-low);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Guardian's occupation</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e($a->guardians_occupation ?? '—') }}</td>
                                </tr>
                                <tr style="background: var(--surface-container-lowest);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Guardian's address</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e($a->guardians_address ?? '—') }}</td>
                                </tr>
                                @if(isset($a->guardians_phone) && trim((string) $a->guardians_phone) !== '')
                                <tr style="background: var(--surface-container-low);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Guardian's phone</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">{{ e($a->guardians_phone) }}</td>
                                </tr>
                                @endif
                                @endif
                                <tr style="background: var(--surface-container-low);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Blood group</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">
                                        @if(trim((string)($a->blood_group ?? '')) !== '')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--primary-container); color: var(--on-primary-container);">{{ e($a->blood_group) }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr style="background: var(--surface-container-lowest);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Payment mode</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">
                                        @if(trim((string)($a->payment_mode ?? '')) !== '')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($a->payment_mode) }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr style="background: var(--surface-container-low);">
                                    <td class="py-3.5 pl-6 sm:pl-8 pr-4 align-top text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">Payment status</td>
                                    <td class="py-3.5 pl-4 pr-6 sm:pr-8 text-sm break-words" style="color: var(--on-surface); border-bottom: 1px solid var(--outline-variant);">
                                        @if(isset($a->payment_status) && (string)$a->payment_status === '1')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--primary-container); color: var(--on-primary-container);">Paid</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--error-container); color: var(--on-error-container);">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
