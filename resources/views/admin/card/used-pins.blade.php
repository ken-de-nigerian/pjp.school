@extends('layouts.app')

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Used scratch card pins"
                pill="Admin"
                title="Used pins"
                :description="'Scratch card pins that have been used to check results. Session: ' . e($settings['session'] ?? '—')"
            >
                <x-slot name="above">
                    <a href="{{ route('admin.card.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Scratch card
                    </a>
                </x-slot>
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($used->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                            <i class="fas fa-check-circle text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No used pins</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">
                            No pins have been used yet for this session. Used pins will appear here when students check results.
                        </p>
                    </div>
                @else
                    <div class="px-4 sm:px-5 lg:px-6 pt-4 pb-3 border-b" style="border-color: var(--outline-variant);">
                        <p class="text-xs sm:text-sm font-medium" style="color: var(--on-surface-variant);">
                            {{ $used->total() }} used pin(s)
                        </p>
                    </div>

                    {{-- Mobile: card list with student avatar and details --}}
                    <div class="block md:hidden border-t" style="border-color: var(--outline-variant);">
                        <ul class="flex flex-col gap-3 p-4 sm:px-6 list-none min-w-0" role="list">
                            @foreach($used as $row)
                                @php
                                    $student = $row->student;
                                    $fullName = $student
                                        ? trim(($student->firstname ?? '') . ' ' . ($student->lastname ?? '') . ' ' . ($student->othername ?? ''))
                                        : '';
                                    $avatarSrc = $student && $student->imagelocation
                                        ? (str_starts_with($student->imagelocation, 'students/') ? asset('storage/' . $student->imagelocation) : asset('storage/students/' . $student->imagelocation))
                                        : asset('storage/students/default.png');
                                    $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : ($row->reg_number ? mb_substr($row->reg_number, 0, 1) : '?');
                                @endphp
                                <li class="flex flex-col gap-0 rounded-2xl border p-4 transition-[background-color] duration-200" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                        <div class="min-w-0 flex-1">
                                            @if($student && Route::has('admin.students.show'))
                                                <a href="{{ route('admin.students.show', $student) }}" class="text-sm font-medium block truncate transition-opacity hover:opacity-80" style="color: var(--primary);">{{ $fullName ?: e($row->reg_number) }}</a>
                                            @else
                                                <span class="text-sm font-medium block truncate" style="color: var(--on-surface);">{{ $fullName ?: e($row->reg_number) }}</span>
                                            @endif
                                            <span class="text-xs truncate block mt-0.5" style="color: var(--on-surface-variant);">{{ e($row->reg_number) }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t flex flex-col gap-2" style="border-color: var(--outline-variant);">
                                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                                            <span class="text-[11px] font-medium uppercase tracking-wide" style="color: var(--on-surface-variant);">Pin</span>
                                            <span class="text-sm font-mono font-medium break-all text-right" style="color: var(--on-surface);">{{ e($row->pins) }}</span>
                                        </div>
                                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                                            <span class="text-[11px] font-medium uppercase tracking-wide" style="color: var(--on-surface-variant);">Class</span>
                                            <span class="text-sm" style="color: var(--on-surface-variant);">{{ e($row->class) }}</span>
                                        </div>
                                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                                            <span class="text-[11px] font-medium uppercase tracking-wide" style="color: var(--on-surface-variant);">Used count</span>
                                            <span class="text-sm tabular-nums" style="color: var(--on-surface);">{{ $row->used_count }}</span>
                                        </div>
                                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                                            <span class="text-[11px] font-medium uppercase tracking-wide" style="color: var(--on-surface-variant);">Time used</span>
                                            <span class="text-sm" style="color: var(--on-surface-variant);">{{ $row->time_used?->format('M j, Y H:i') ?? '—' }}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Desktop: table with student column (avatar + name + reg number) --}}
                    <div class="hidden md:block overflow-x-auto flex-1 min-h-0">
                        <table class="min-w-full border-separate border-spacing-0" style="border-color: var(--outline-variant);">
                            <thead>
                                <tr style="background: var(--surface-container-highest); border-bottom: 1px solid var(--outline-variant);">
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Pin</th>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Student</th>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Class</th>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Used count</th>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Time used</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($used as $row)
                                    @php
                                        $student = $row->student;
                                        $fullName = $student
                                            ? trim(($student->firstname ?? '') . ' ' . ($student->lastname ?? '') . ' ' . ($student->othername ?? ''))
                                            : '';
                                        $avatarSrc = $student && $student->imagelocation
                                            ? (str_starts_with($student->imagelocation, 'students/') ? asset('storage/' . $student->imagelocation) : asset('storage/students/' . $student->imagelocation))
                                            : asset('storage/students/default.png');
                                        $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : ($row->reg_number ? mb_substr($row->reg_number, 0, 1) : '?');
                                    @endphp
                                    <tr style="background: var(--surface-container-low); border-top: 1px solid var(--outline-variant);">
                                        <td class="px-4 sm:px-6 py-3 text-sm font-mono" style="color: var(--on-surface);">{{ e($row->pins) }}</td>
                                        <td class="px-4 sm:px-6 py-3">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <img src="{{ $avatarSrc }}" alt="" class="w-9 h-9 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                                                <div class="min-w-0">
                                                    @if($student && Route::has('admin.students.show'))
                                                        <a href="{{ route('admin.students.show', $student) }}" class="text-sm font-medium truncate block transition-opacity hover:opacity-80" style="color: var(--primary);">{{ $fullName ?: e($row->reg_number) }}</a>
                                                    @else
                                                        <span class="text-sm font-medium truncate block" style="color: var(--on-surface);">{{ $fullName ?: e($row->reg_number) }}</span>
                                                    @endif
                                                    <span class="text-xs truncate block mt-0.5" style="color: var(--on-surface-variant);">{{ e($row->reg_number) }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 text-sm" style="color: var(--on-surface-variant);">{{ e($row->class) }}</td>
                                        <td class="px-4 sm:px-6 py-3 text-sm tabular-nums" style="color: var(--on-surface);">{{ $row->used_count }}</td>
                                        <td class="px-4 sm:px-6 py-3 text-sm" style="color: var(--on-surface-variant);">{{ $row->time_used?->format('M j, Y H:i') ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($used->hasPages())
                        <div class="px-4 sm:px-6 py-4 border-t" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                            <x-pagination :paginator="$used" />
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>
@endsection
