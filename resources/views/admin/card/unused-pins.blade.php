@php use Carbon\Carbon; @endphp
@extends('layouts.app', ['title' => 'Unused PINs'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Unused scratch card pins"
                pill="Admin"
                title="Unused pins"
                :description="'Scratch card pins that have not been used yet. Session: ' . e($settings['session'] ?? '—')"
            >
                <x-slot name="above">
                    <a href="{{ route('admin.card.index') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Scratch card
                    </a>
                </x-slot>
                @if(isset($unused) && !$unused->isEmpty())
                    <x-slot name="actions">
                        <div class="flex flex-col sm:flex-row flex-wrap gap-2 w-full lg:w-auto">
                            <a href="{{ route('admin.card.unused-pins.pdf') }}"
                               target="_blank" rel="noopener noreferrer"
                               class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                                <i class="fas fa-file-pdf text-sm" aria-hidden="true"></i>
                                Export to PDF
                            </a>
                            <a href="{{ route('admin.card.unused-pins.excel') }}"
                               class="admin-dashboard-hero__btn w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0 border"
                               style="background: var(--surface); color: var(--primary); border-color: var(--outline-variant);">
                                <i class="fas fa-file-excel text-sm" aria-hidden="true"></i>
                                Export to Excel
                            </a>
                        </div>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if(isset($unused) && $unused->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5 flex items-center justify-center" style="border-radius: 16px;">
                            <i class="fas fa-key text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No unused pins</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">
                            There are no unused pins for this session. Generate new pins from the Scratch card page.
                        </p>

                        @if(Route::has('admin.card.index'))
                            <div class="flex justify-center">
                                <a href="{{ route('admin.card.index') }}" class="btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 12px;">
                                    <i class="fas fa-plus text-sm" aria-hidden="true"></i>
                                    <span>Generate pins</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="px-4 sm:px-5 lg:px-6 pt-4 pb-3 border-b" style="border-color: var(--outline-variant);">
                        <p class="text-xs sm:text-sm font-medium" style="color: var(--on-surface-variant);">
                            @if(method_exists($unused, 'total'))
                                {{ $unused->total() }}
                            @else
                                {{ $unused->count() }}
                            @endif
                            unused pin(s)
                        </p>
                    </div>

                    {{-- Mobile: card list --}}
                    <div class="block md:hidden border-t" style="border-color: var(--outline-variant);">
                        <ul class="flex flex-col gap-3 p-4 sm:px-6 list-none min-w-0" role="list">
                            @foreach($unused ?? [] as $index => $row)
                                <li class="flex flex-col gap-0 rounded-2xl border p-4 transition-[background-color] duration-200" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                    <div class="flex items-center justify-between gap-3 flex-wrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border shrink-0" style="background: var(--surface-container-high); color: var(--on-surface-variant); border-color: var(--outline-variant);">
                                            <i class="fas fa-hashtag text-[10px]" aria-hidden="true"></i>
                                            {{ e($row->serial_number ?? ('#' . ($index + 1))) }}
                                        </span>
                                    </div>
                                    <div class="mt-3 pt-3 border-t flex flex-col gap-2" style="border-color: var(--outline-variant);">
                                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                                            <span class="text-[11px] font-medium uppercase tracking-wide" style="color: var(--on-surface-variant);">Pin</span>
                                            <span class="text-sm font-mono font-medium break-all text-right" style="color: var(--on-surface);">{{ e($row->pins) }}</span>
                                        </div>
                                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                                            <span class="text-[11px] font-medium uppercase tracking-wide" style="color: var(--on-surface-variant);">Session</span>
                                            <span class="text-sm" style="color: var(--on-surface);">{{ e($row->session) }}</span>
                                        </div>
                                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                                            <span class="text-[11px] font-medium uppercase tracking-wide" style="color: var(--on-surface-variant);">Uploaded</span>
                                            <span class="text-sm" style="color: var(--on-surface-variant);">{{ $row->upload_date ? Carbon::parse($row->upload_date)->format('M j, Y H:i') : '—' }}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Desktop: table (same structure as used-pins) --}}
                    <div class="hidden md:block overflow-x-auto flex-1 min-h-0">
                        <table class="min-w-full border-separate border-spacing-0" style="border-color: var(--outline-variant);">
                            <thead>
                                <tr style="background: var(--surface-container-highest); border-bottom: 1px solid var(--outline-variant);">
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Serial #</th>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Pin</th>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Session</th>
                                    <th scope="col" class="px-4 sm:px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant); letter-spacing: 0.06em;">Uploaded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unused ?? [] as $index => $row)
                                    <tr style="background: var(--surface-container-low); border-top: 1px solid var(--outline-variant);">
                                        <td class="px-4 sm:px-6 py-3 text-sm tabular-nums" style="color: var(--on-surface-variant);">{{ e($row->serial_number ?? ('#' . ($index + 1))) }}</td>
                                        <td class="px-4 sm:px-6 py-3 text-sm font-mono" style="color: var(--on-surface);">{{ e($row->pins) }}</td>
                                        <td class="px-4 sm:px-6 py-3 text-sm" style="color: var(--on-surface-variant);">{{ e($row->session) }}</td>
                                        <td class="px-4 sm:px-6 py-3 text-sm" style="color: var(--on-surface-variant);">{{ $row->upload_date ? Carbon::parse($row->upload_date)->format('M j, Y H:i') : '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 sm:px-6 py-8 text-center text-sm" style="color: var(--on-surface-variant);">No unused pins.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($unused) && method_exists($unused, 'hasPages') && $unused->hasPages())
                        <div class="px-4 sm:px-6 py-4 border-t" style="border-color: var(--outline-variant); background: var(--surface-container-low);">
                            <x-pagination :paginator="$unused" />
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>
@endsection
