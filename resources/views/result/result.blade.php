@php
    use App\Enums\FeeCategoryEnum;
    use Carbon\Carbon;
@endphp
@extends('layouts.result', ['title' => trim(($student->firstname ?? '') . ' ' . ($student->lastname ?? '') . ' ' . ($student->othername ?? '')) ?: 'Student result'])

@section('content')
    @php
        $fullName = trim(($student->firstname ?? '') . ' ' . ($student->lastname ?? '') . ' ' . ($student->othername ?? ''));
        $avatarSrc = $student->imagelocation
            ? (str_starts_with($student->imagelocation, 'students/') ? asset('storage/' . $student->imagelocation) : asset('storage/students/' . $student->imagelocation))
            : asset('storage/students/default.png');

        $classPositionOrdinal = '—';
        $rawClassPosition = $reportCard->class_position ?? null;
        if ($rawClassPosition !== null && $rawClassPosition !== '') {
            if (is_numeric($rawClassPosition)) {
                $n = (int) $rawClassPosition;
                $v = abs($n) % 100;
                $suffix = ($v >= 11 && $v <= 13)
                    ? 'th'
                    : match (abs($n) % 10) {
                        1 => 'st',
                        2 => 'nd',
                        3 => 'rd',
                        default => 'th',
                    };
                $classPositionOrdinal = $n . $suffix;
            } else {
                $classPositionOrdinal = (string) $rawClassPosition;
            }
        }

        $feesGrouped = $fees->groupBy(fn ($fee) => $fee->category->value);
        $feeCategoryOrder = FeeCategoryEnum::cases();
    @endphp

    @push('styles')
        <style>
            @media (min-width: 1024px) {
                html {
                    overflow: auto;
                    height: auto;
                    min-height: 100%;
                }
            }

            .result-watermark-layer {
                position: absolute;
                inset: 0;
                z-index: 0;
                pointer-events: none;
                overflow: hidden;
                isolation: isolate;
            }

            /* Logo grid: neutral tone, vignette mask (standard mask-* only — avoids legacy -webkit-* validator noise) */
            .result-watermark-layer__pattern {
                position: absolute;
                inset: -42%;
                background-image: var(--result-watermark-url);
                background-repeat: repeat;
                background-size: min(26vw, 220px) min(26vw, 220px);
                background-position: center center;
                opacity: 0.17;
                filter: grayscale(100%) contrast(0.88) brightness(1.12) blur(6px);
                mix-blend-mode: multiply;
                mask-image: radial-gradient(
                    ellipse 78% 72% at 50% 44%,
                    #000 0%,
                    #000 42%,
                    rgba(0, 0, 0, 0.35) 68%,
                    transparent 100%
                );
                mask-size: 100% 100%;
            }

            /* Multiply disappears on very dark surfaces — fall back to soft-light */
            @supports (mix-blend-mode: multiply) {
                [data-theme="dark"] .result-watermark-layer__pattern {
                    mix-blend-mode: soft-light;
                    opacity: 0.22;
                    filter: grayscale(100%) contrast(0.75) brightness(1.35) blur(6px);
                }
            }

            /*
             * Hero inside result card: global .admin-dashboard-hero uses its own radius (1.25–1.5rem),
             * which fights the shell’s rounded-2xl clip. Top corners come only from .result-shell;
             * bottom corners stay aligned with the same scale as the card (1rem ≈ rounded-2xl).
             */
            .result-shell .admin-dashboard-hero {
                border-radius: 0 0 1rem 1rem;
                margin-bottom: 1.25rem;
            }

            @media (min-width: 1024px) {
                .result-shell .admin-dashboard-hero {
                    margin-bottom: 1.5rem;
                }
            }

            /* Subject scores table — clearer hierarchy, sticky header on scroll */
            .result-scores-section .result-sheet-table--scores thead th {
                position: sticky;
                top: 0;
                z-index: 2;
                background: var(--surface-container);
                box-shadow: 0 1px 0 color-mix(in srgb, var(--outline-variant) 90%, transparent);
            }

            .result-sheet-table--scores tbody tr {
                transition: background-color 0.12s ease;
            }

            @media (hover: hover) {
                .result-sheet-table--scores tbody tr:hover {
                    background: color-mix(
                        in srgb,
                        var(--primary-container) 14%,
                        var(--surface-container-lowest)
                    ) !important;
                }
            }

            .result-sheet-table--scores tfoot .result-summary-row:first-child th,
            .result-sheet-table--scores tfoot .result-summary-row:first-child td {
                border-top-width: 2px;
            }

            @media print {
                @page {
                    size: A4 portrait;
                    margin: 5mm;
                }

                html,
                body {
                    background: #fff !important;
                    height: auto !important;
                    overflow: visible !important;
                    print-color-adjust: exact;
                    -webkit-print-color-adjust: exact;
                }

                .noprint {
                    display: none !important;
                }

                .result-page-outer {
                    min-height: 0 !important;
                    padding: 0 !important;
                    background: #fff !important;
                }

                /* Full width for print — same content width as scaled result block */
                .result-page-outer > .mx-auto.max-w-7xl {
                    max-width: 100% !important;
                    padding-left: 0 !important;
                    padding-right: 0 !important;
                }

                /* Page 1: entire published result (scaled to fit). Page 2+: next term only. */
                #infoForm.result-shell {
                    --result-print-scale: 1;
                    transform: scale(var(--result-print-scale));
                    transform-origin: top center;
                    box-shadow: none !important;
                    border: 1px solid #ccc !important;
                    margin: 0 auto !important;
                    max-width: 100% !important;
                    border-radius: 0 !important;
                    padding: 2mm 3mm !important;
                    page-break-after: always !important;
                    break-after: page !important;
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                }

                .result-next-term-card {
                    box-shadow: none !important;
                    border: 1px solid #ccc !important;
                    margin: 0 auto !important;
                    max-width: 100% !important;
                    border-radius: 0 !important;
                    background: #fff !important;
                    page-break-before: always !important;
                    break-before: page !important;
                    page-break-inside: auto !important;
                }

                .result-next-term-card .result-next-term-heading {
                    padding: 2mm 3mm 1mm !important;
                }

                .result-next-term-card .result-print-requirements {
                    padding: 2mm 3mm !important;
                }

                /*
                 * One print gutter: match hero __inner (x-admin.hero-page) to the same inset as
                 * .result-print-inner (screen p-5 / sm:p-8). Cancel bleed margins so print isn’t
                 * 0.5rem in the hero vs 1.25rem–2rem on sections.
                 */
                .result-print-inner {
                    padding: 2mm 1.25rem 1rem 1.25rem !important;
                }

                .result-print-inner > *:first-child {
                    margin-left: 0 !important;
                    margin-right: 0 !important;
                    margin-top: 0 !important;
                }

                /* Keep blocks whole inside the scaled sheet so page 1 stays one unit (scale shrinks to fit). */
                .result-print-inner > * {
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                }

                .result-watermark-layer__pattern {
                    opacity: 0.15 !important;
                    mix-blend-mode: multiply !important;
                    filter: grayscale(100%) contrast(0.9) brightness(1.05) blur(4px) !important;
                    mask-image: radial-gradient(
                        ellipse 85% 78% at 50% 45%,
                        #000 0%,
                        rgba(0, 0, 0, 0.5) 70%,
                        transparent 100%
                    ) !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .result-print-inner .admin-dashboard-hero {
                    margin-bottom: 0.35rem !important;
                    border-radius: 0 !important;
                }

                /* Horizontal gutter = .result-print-inner only (same as screen p-5 / sm:p-8) */
                .result-print-inner .admin-dashboard-hero__inner {
                    padding: 4mm 0 0.75rem 0 !important;
                }

                /* Actions are .noprint — center school name + meta line for print */
                .result-print-inner .admin-dashboard-hero__header {
                    flex-direction: column !important;
                    align-items: center !important;
                    justify-content: center !important;
                    gap: 0.35rem !important;
                }

                .result-print-inner .admin-dashboard-hero__header > .min-w-0 {
                    width: 100% !important;
                    max-width: 100% !important;
                    text-align: center !important;
                }

                .result-print-inner .admin-dashboard-hero__header > .shrink-0 {
                    display: none !important;
                }

                .result-print-inner .admin-page-hero__title {
                    font-size: 11pt !important;
                }

                .result-print-inner .admin-page-hero__description {
                    font-size: 7.5pt !important;
                }

                /* Keep student row images off the hero edges (hero __inner has no horizontal pad) */
                .result-print-inner .admin-page-hero__below {
                    padding-left: 0.5rem !important;
                    padding-right: 0.5rem !important;
                }

                .result-print-inner .admin-page-hero__below > .flex {
                    gap: 0.65rem !important;
                    justify-content: space-between !important;
                    align-items: center !important;
                }

                .result-print-inner .admin-page-hero__below img {
                    max-height: 64px !important;
                    width: auto !important;
                    max-width: 100% !important;
                    box-shadow: none !important;
                }

                .result-sheet-table--scores thead th {
                    position: static !important;
                    box-shadow: none !important;
                }

                .result-sheet-table th,
                .result-sheet-table td {
                    padding: 0.2rem 0.35rem !important;
                    font-size: 7.5pt !important;
                    line-height: 1.2 !important;
                }

                .result-sheet-table .badge {
                    padding: 0.1rem 0.35rem !important;
                    font-size: 7pt !important;
                }

                /* Behavioural: screen shows cards on small viewports; print always uses table */
                .result-behavioral-mobile-cards {
                    display: none !important;
                }

                .result-behavioral-desktop-table {
                    display: block !important;
                }

                .result-behavioral-desktop-table > .overflow-x-auto {
                    overflow: visible !important;
                }

                .result-summary-row th,
                .result-summary-row td {
                    font-size: 8pt !important;
                    font-weight: 700 !important;
                    padding: 0.25rem 0.35rem !important;
                }

                .result-print-inner .mb-8,
                .result-print-inner .mb-6,
                .result-print-inner .mt-6 {
                    margin-bottom: 0.35rem !important;
                    margin-top: 0.35rem !important;
                }

                .result-print-inner section {
                    margin-bottom: 0.35rem !important;
                    margin-top: 0.35rem !important;
                }

                .result-print-inner .rounded-2xl,
                .result-print-inner .rounded-xl {
                    border-radius: 4px !important;
                }

                .result-print-inner .result-scores-section h2,
                .result-print-inner .result-behavioral-section h2,
                .result-next-term-card .result-next-term-heading h2 {
                    font-size: 10pt !important;
                }

                .result-next-term-card h3 {
                    font-size: 9pt !important;
                }

                .result-print-inner h3,
                .result-print-inner h4,
                .result-print-inner h5 {
                    font-size: 9pt !important;
                }

                .result-print-inner .text-sm,
                .result-print-inner .text-base {
                    font-size: 8pt !important;
                }

                .result-print-inner .text-xs {
                    font-size: 7pt !important;
                }
            }

            @media print and (min-width: 640px) {
                .result-print-inner {
                    padding-left: 2rem !important;
                    padding-right: 2rem !important;
                }
            }
        </style>
    @endpush

    <div class="result-page-outer min-h-screen bg-[var(--surface-container-low)] py-6 pb-16 text-[var(--on-surface)] sm:py-8 sm:pb-20">
        <div class="mx-auto w-full min-w-0 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div id="infoForm" class="result-shell relative z-[2] w-full min-w-0 overflow-hidden rounded-2xl border border-[var(--outline-variant)] bg-[var(--surface-container-lowest)] shadow-[var(--elevation-2)]" style="--result-print-scale: 1;">
            <div class="result-watermark-layer" aria-hidden="true" style="--result-watermark-url: url('{{ asset('storage/' . config('school.logo_file', 'logo/logo.jpg')) }}')">
                <div class="result-watermark-layer__pattern"></div>
            </div>

            <div class="result-print-inner relative z-10 space-y-5 p-5 sm:space-y-6 sm:p-8">
                <div class="-mx-5 -mt-5 sm:-mx-8 sm:-mt-8">
                    <x-admin.hero-page
                        aria-label="Report sheet"
                        :hide-pill="true"
                        :title="$settings['name']"
                        :description="$settings['address'] . ' · ' . $term . ' · Report sheet · ' . $session"
                    >
                        <x-slot name="actions">
                            <div class="noprint flex flex-wrap items-center gap-2">
                                <button type="button" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary min-h-[44px] sm:min-h-0 justify-center" onclick="downloadPDF()">
                                    <i class="fa-solid fa-print" aria-hidden="true"></i>
                                    <span>Print report</span>
                                </button>
                                <a href="{{ route('result.check') }}" class="admin-dashboard-hero__btn min-h-[44px] sm:min-h-0 justify-center no-underline">
                                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                                    <span>Go back</span>
                                </a>
                            </div>
                        </x-slot>

                        <x-slot name="below">
                            <div class="flex flex-wrap items-stretch justify-between gap-4 sm:items-center sm:gap-6">
                                <div class="flex min-w-0 flex-1 basis-[72px] justify-center sm:justify-start sm:basis-[88px]">
                                    <img src="{{ $avatarSrc }}" width="88" height="96" alt="" class="h-[88px] w-[80px] rounded-xl object-cover shadow-lg sm:w-[88px]">
                                </div>
                                <div class="min-w-0 flex-[2] basis-full text-center sm:basis-0">
                                    <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-white/55">Student</p>
                                    <p class="mt-1 text-base font-semibold leading-snug text-white sm:text-lg">{{ $fullName }}</p>
                                    <p class="mt-1 text-sm text-white/75">{{ $class }} · ID {{ $student->reg_number }} · {{ $student->gender }}</p>
                                </div>
                                <div class="flex min-w-0 flex-1 basis-[72px] justify-center sm:basis-[88px] sm:justify-end">
                                    <x-site-logo-mark width="88" height="96" alt="" class="h-[88px] w-[80px] rounded-xl object-contain shadow-lg sm:w-[88px]"/>
                                </div>
                            </div>
                        </x-slot>
                    </x-admin.hero-page>
                </div>

                <section class="result-scores-section overflow-hidden rounded-2xl border border-[var(--outline-variant)] bg-[var(--surface-container-lowest)] shadow-[var(--elevation-1)]" aria-labelledby="result-scores-heading">
                    <div class="border-b border-[var(--outline-variant)] bg-[var(--surface-container)] px-4 py-3 sm:px-5 sm:py-3.5">
                        <h2 id="result-scores-heading" class="text-sm font-semibold tracking-tight text-[var(--on-surface)] sm:text-base">Subject scores</h2>
                        <p class="mt-0.5 max-w-2xl text-xs leading-snug text-[var(--on-surface-variant)]">Continuous assessment, assignments, exams and final grades per subject.</p>
                    </div>
                    <div class="overflow-x-auto [-webkit-overflow-scrolling:touch]">
                        <table class="result-sheet-table result-sheet-table--scores w-full min-w-[600px] border-collapse text-sm">
                            <caption class="sr-only">Subject scores: CA, assignment, exam, total, grade and remarks for each subject.</caption>
                            <thead>
                                <tr class="bg-[var(--surface-container)]">
                                    <th scope="col" class="min-w-[11rem] border border-[var(--outline-variant)] px-3 py-2.5 text-left text-[0.65rem] font-semibold uppercase tracking-wide text-[var(--on-surface-variant)] sm:min-w-[12rem] sm:px-4 sm:py-3">Subjects</th>
                                    <th scope="col" class="w-14 min-w-[3.25rem] border border-[var(--outline-variant)] px-2 py-2.5 text-center text-[0.65rem] font-semibold uppercase tracking-wide text-[var(--on-surface-variant)] sm:px-3 sm:py-3">CA</th>
                                    <th scope="col" class="w-14 min-w-[3.25rem] border border-[var(--outline-variant)] px-2 py-2.5 text-center text-[0.65rem] font-semibold uppercase tracking-wide text-[var(--on-surface-variant)] sm:px-3 sm:py-3">Assign</th>
                                    <th scope="col" class="w-14 min-w-[3.25rem] border border-[var(--outline-variant)] px-2 py-2.5 text-center text-[0.65rem] font-semibold uppercase tracking-wide text-[var(--on-surface-variant)] sm:px-3 sm:py-3">Exam</th>
                                    <th scope="col" class="w-16 min-w-[3.5rem] border border-[var(--outline-variant)] px-2 py-2.5 text-center text-[0.65rem] font-semibold uppercase tracking-wide text-[var(--on-surface-variant)] sm:px-3 sm:py-3">Total</th>
                                    <th scope="col" class="w-16 min-w-[3.75rem] border border-[var(--outline-variant)] px-2 py-2.5 text-center text-[0.65rem] font-semibold uppercase tracking-wide text-[var(--on-surface-variant)] sm:px-3 sm:py-3">Grade</th>
                                    <th scope="col" class="min-w-[6.5rem] border border-[var(--outline-variant)] px-2 py-2.5 text-center text-[0.65rem] font-semibold uppercase tracking-wide text-[var(--on-surface-variant)] sm:min-w-[7rem] sm:px-3 sm:py-3">Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($getSegment as $row)
                                    <tr class="bg-[var(--surface-container-lowest)] even:bg-[color-mix(in_srgb,var(--surface-container-low)_55%,var(--surface-container-lowest))]">
                                        <th scope="row" class="border border-[var(--outline-variant)] px-3 py-2 text-left text-sm font-medium leading-snug text-[var(--on-surface)] sm:px-4 sm:py-2.5">
                                            {{ $row->subjects ?? '' }}
                                        </th>
                                        <td class="border border-[var(--outline-variant)] px-2 py-2 text-center tabular-nums text-[var(--on-surface)] sm:px-3 sm:py-2.5">
                                            {{ $row->ca ?? '' }}
                                        </td>
                                        <td class="border border-[var(--outline-variant)] px-2 py-2 text-center tabular-nums text-[var(--on-surface)] sm:px-3 sm:py-2.5">
                                            {{ $row->assignment ?? '' }}
                                        </td>
                                        <td class="border border-[var(--outline-variant)] px-2 py-2 text-center tabular-nums text-[var(--on-surface)] sm:px-3 sm:py-2.5">
                                            {{ $row->exam ?? '' }}
                                        </td>
                                        <td class="border border-[var(--outline-variant)] px-2 py-2 text-center text-sm font-semibold tabular-nums text-[var(--on-surface)] sm:px-3 sm:py-2.5">
                                            {{ $row->total ?? '' }}
                                        </td>
                                        <td class="border border-[var(--outline-variant)] px-2 py-2 text-center sm:px-3 sm:py-2.5">
                                            <span class="badge inline-flex min-w-[2rem] items-center justify-center rounded-full bg-[var(--primary-container)] px-2.5 py-1 text-xs font-bold tabular-nums text-[var(--on-primary-container)]">{{ $row->grade_letter }}</span>
                                        </td>
                                        <td class="border border-[var(--outline-variant)] px-2 py-2 text-center text-sm leading-snug text-[var(--on-surface)] sm:px-3 sm:py-2.5">
                                            {{ $row->result_remarks }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="result-summary-row bg-[color-mix(in_srgb,var(--tertiary-container)_85%,var(--surface-container))]">
                                    <th scope="row" class="border border-[var(--outline-variant)] px-3 py-2.5 text-left text-[0.65rem] font-bold uppercase tracking-wide text-[var(--on-surface)] sm:px-4" colspan="4">Average</th>
                                    <td class="border border-[var(--outline-variant)] px-2 py-2.5 text-center text-sm font-bold tabular-nums text-[var(--on-surface)] sm:px-3">{{ $reportCard->students_sub_average ?? '—' }}</td>
                                    <td class="border border-[var(--outline-variant)] bg-[color-mix(in_srgb,var(--tertiary-container)_85%,var(--surface-container))] px-2 py-2.5 sm:px-3" colspan="2"></td>
                                </tr>
                                <tr class="result-summary-row bg-[color-mix(in_srgb,var(--secondary-container)_85%,var(--surface-container))]">
                                    <th scope="row" class="border border-[var(--outline-variant)] px-3 py-2.5 text-left text-[0.65rem] font-bold uppercase tracking-wide text-[var(--on-surface)] sm:px-4" colspan="4">Total</th>
                                    <td class="border border-[var(--outline-variant)] px-2 py-2.5 text-center text-sm font-bold tabular-nums text-[var(--on-surface)] sm:px-3">{{ $reportCard->students_sub_total ?? '—' }}</td>
                                    <td class="border border-[var(--outline-variant)] bg-[color-mix(in_srgb,var(--secondary-container)_85%,var(--surface-container))] px-2 py-2.5 sm:px-3" colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </section>

                <section class="min-w-0 overflow-x-auto rounded-2xl border border-[var(--outline-variant)] shadow-[var(--elevation-1)]" aria-label="Position and principal remark">
                    <table class="result-sheet-table w-full border-collapse text-sm">
                        <tbody>
                            <tr class="bg-[var(--surface-container-lowest)]">
                                <td class="border border-[var(--outline-variant)] px-3 py-2 align-top text-[0.65rem] font-bold uppercase text-[var(--on-surface-variant)]">Position</td>
                                <td class="border border-[var(--outline-variant)] px-3 py-2 text-[var(--on-surface)]">
                                    <span class="text-lg font-bold tabular-nums">{{ $classPositionOrdinal }}</span>
                                    <span class="text-sm text-[var(--on-surface-variant)]"> of </span>
                                    <span class="font-semibold tabular-nums">{{ $classCount }}</span> Students
                                </td>
                            </tr>
                            <tr class="bg-[var(--surface-container-lowest)]">
                                <td class="border border-[var(--outline-variant)] px-3 py-2 align-top text-[0.65rem] font-bold uppercase text-[var(--on-surface-variant)]">Principal's Remark:</td>
                                <td class="border border-[var(--outline-variant)] px-3 py-2 text-left text-sm leading-relaxed text-[var(--on-surface)] whitespace-pre-line break-words">{{ $principalRemark }}</td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                @if($behavioral->isNotEmpty())
                    <section class="result-behavioral-section overflow-hidden rounded-2xl border border-[var(--outline-variant)] bg-[var(--surface-container-lowest)] shadow-[var(--elevation-1)]" aria-labelledby="result-behavioral-heading">
                        <div class="border-b border-[var(--outline-variant)] bg-[var(--surface-container)] px-4 py-3 sm:px-5 sm:py-3.5">
                            <h2 id="result-behavioral-heading" class="text-sm font-semibold tracking-tight text-[var(--on-surface)] sm:text-base">Behavioural analysis</h2>
                            <p class="mt-0.5 max-w-2xl text-xs leading-snug text-[var(--on-surface-variant)]">Non-academic traits recorded for this term: conduct, participation, and wellbeing indicators.</p>
                        </div>
                        <ul class="result-behavioral-mobile-cards flex flex-col gap-3 p-4 sm:px-5 list-none m-0 md:hidden" role="list" aria-label="Behavioural analysis: ratings for neatness, music, sports, attentiveness, punctuality, health, and politeness.">
                            @foreach($behavioral as $index => $b)
                                <li class="flex flex-col rounded-xl p-4" style="background: var(--surface-container);">
                                    @if($behavioral->count() > 1)
                                        <p class="mb-2.5 text-xs font-semibold uppercase tracking-wide" style="color: var(--on-surface-variant);">Record {{ $index + 1 }}</p>
                                    @endif
                                    <div class="flex flex-col divide-y divide-[color-mix(in_srgb,var(--outline-variant)_22%,transparent)]">
                                        <div class="flex flex-col gap-1.5 py-2.5 first:pt-0">
                                            <span class="inline-flex w-fit items-center rounded-lg px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-wide" style="background: var(--surface-container-high); color: var(--on-surface-variant);">Neatness</span>
                                            <span class="text-sm font-medium tabular-nums leading-snug" style="color: var(--on-surface);">{{ $b->neatness !== null && $b->neatness !== '' ? $b->neatness : '—' }}</span>
                                        </div>
                                        <div class="flex flex-col gap-1.5 py-2.5">
                                            <span class="inline-flex w-fit items-center rounded-lg px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-wide" style="background: var(--surface-container-high); color: var(--on-surface-variant);">Music</span>
                                            <span class="text-sm font-medium tabular-nums leading-snug" style="color: var(--on-surface);">{{ $b->music !== null && $b->music !== '' ? $b->music : '—' }}</span>
                                        </div>
                                        <div class="flex flex-col gap-1.5 py-2.5">
                                            <span class="inline-flex w-fit items-center rounded-lg px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-wide" style="background: var(--surface-container-high); color: var(--on-surface-variant);">Sports</span>
                                            <span class="text-sm font-medium tabular-nums leading-snug" style="color: var(--on-surface);">{{ $b->sports !== null && $b->sports !== '' ? $b->sports : '—' }}</span>
                                        </div>
                                        <div class="flex flex-col gap-1.5 py-2.5">
                                            <span class="inline-flex w-fit items-center rounded-lg px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-wide" style="background: var(--surface-container-high); color: var(--on-surface-variant);">Attentive</span>
                                            <span class="text-sm font-medium tabular-nums leading-snug" style="color: var(--on-surface);">{{ $b->attentiveness !== null && $b->attentiveness !== '' ? $b->attentiveness : '—' }}</span>
                                        </div>
                                        <div class="flex flex-col gap-1.5 py-2.5">
                                            <span class="inline-flex w-fit items-center rounded-lg px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-wide" style="background: var(--surface-container-high); color: var(--on-surface-variant);">Punctual</span>
                                            <span class="text-sm font-medium tabular-nums leading-snug" style="color: var(--on-surface);">{{ $b->punctuality !== null && $b->punctuality !== '' ? $b->punctuality : '—' }}</span>
                                        </div>
                                        <div class="flex flex-col gap-1.5 py-2.5">
                                            <span class="inline-flex w-fit items-center rounded-lg px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-wide" style="background: var(--surface-container-high); color: var(--on-surface-variant);">Health</span>
                                            <span class="text-sm font-medium tabular-nums leading-snug" style="color: var(--on-surface);">{{ $b->health !== null && $b->health !== '' ? $b->health : '—' }}</span>
                                        </div>
                                        <div class="flex flex-col gap-1.5 py-2.5 last:pb-0">
                                            <span class="inline-flex w-fit items-center rounded-lg px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-wide" style="background: var(--surface-container-high); color: var(--on-surface-variant);">Polite</span>
                                            <span class="text-sm font-medium tabular-nums leading-snug" style="color: var(--on-surface);">{{ $b->politeness !== null && $b->politeness !== '' ? $b->politeness : '—' }}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="result-behavioral-desktop-table hidden md:block">
                            <div class="overflow-x-auto [-webkit-overflow-scrolling:touch]">
                                <table class="result-sheet-table w-full min-w-[560px] border-collapse text-sm">
                                    <caption class="sr-only">Behavioural analysis: ratings for neatness, music, sports, attentiveness, punctuality, health, and politeness.</caption>
                                    <thead>
                                        <tr class="bg-[var(--surface-container)]">
                                            <th scope="col" class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-[0.6rem] font-semibold uppercase leading-tight text-[var(--on-surface-variant)]">Neatness</th>
                                            <th scope="col" class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-[0.6rem] font-semibold uppercase leading-tight text-[var(--on-surface-variant)]">Music</th>
                                            <th scope="col" class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-[0.6rem] font-semibold uppercase leading-tight text-[var(--on-surface-variant)]">Sports</th>
                                            <th scope="col" class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-[0.6rem] font-semibold uppercase leading-tight text-[var(--on-surface-variant)]">Attentive</th>
                                            <th scope="col" class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-[0.6rem] font-semibold uppercase leading-tight text-[var(--on-surface-variant)]">Punctual</th>
                                            <th scope="col" class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-[0.6rem] font-semibold uppercase leading-tight text-[var(--on-surface-variant)]">Health</th>
                                            <th scope="col" class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-[0.6rem] font-semibold uppercase leading-tight text-[var(--on-surface-variant)]">Polite</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($behavioral as $b)
                                            <tr class="bg-[var(--surface-container-lowest)] even:bg-[color-mix(in_srgb,var(--surface-container-low)_55%,var(--surface-container-lowest))]">
                                                <td class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-xs text-[var(--on-surface)]">{{ $b->neatness ?? '' }}</td>
                                                <td class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-xs text-[var(--on-surface)]">{{ $b->music ?? '' }}</td>
                                                <td class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-xs text-[var(--on-surface)]">{{ $b->sports ?? '' }}</td>
                                                <td class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-xs text-[var(--on-surface)]">{{ $b->attentiveness ?? '' }}</td>
                                                <td class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-xs text-[var(--on-surface)]">{{ $b->punctuality ?? '' }}</td>
                                                <td class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-xs text-[var(--on-surface)]">{{ $b->health ?? '' }}</td>
                                                <td class="border border-[var(--outline-variant)] px-1 py-1.5 text-center text-xs text-[var(--on-surface)]">{{ $b->politeness ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                @endif
            </div>
        </div>

        <div class="result-next-term-card relative z-[1] mt-6 w-full min-w-0 overflow-hidden rounded-2xl border border-[var(--outline-variant)] bg-[var(--surface-container-lowest)] shadow-[var(--elevation-2)] sm:mt-8" aria-labelledby="result-next-term-heading">
            <div class="result-next-term-heading border-b border-[var(--outline-variant)] bg-[var(--surface-container)] px-4 py-3 sm:px-5 sm:py-3.5">
                <h2 id="result-next-term-heading" class="text-sm font-semibold tracking-tight text-[var(--on-surface)] sm:text-base">Next term requirements</h2>
                <p class="mt-0.5 max-w-2xl text-xs leading-snug text-[var(--on-surface-variant)]">Resumption date, fees and charges, and checklist for the upcoming term.</p>
            </div>
            <div class="result-print-requirements space-y-5 p-5 sm:space-y-6 sm:p-8">
                <section class="overflow-x-auto rounded-2xl border border-[var(--outline-variant)] shadow-[var(--elevation-1)]" aria-label="Resumption notice">
                    <table class="result-sheet-table w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-[var(--surface-container)]">
                                <th class="w-10 border border-[var(--outline-variant)] px-2 py-2 text-center text-[0.65rem] font-semibold uppercase text-[var(--on-surface-variant)]">#</th>
                                <th class="border border-[var(--outline-variant)] px-2 py-2 text-left text-[0.65rem] font-semibold uppercase text-[var(--on-surface-variant)]">Notice</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-[var(--surface-container-lowest)]">
                                <td class="border border-[var(--outline-variant)] px-2 py-2 text-center text-sm font-semibold text-[var(--on-surface)]">1</td>
                                <td class="border border-[var(--outline-variant)] px-2 py-2 text-left text-sm text-[var(--on-surface)]">
                                    Resumption: {{ Carbon::parse($settings['resumption'])->format('l, jS F Y') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                @if($fees->isNotEmpty())
                    <section class="overflow-x-auto rounded-2xl border border-[var(--outline-variant)] shadow-[var(--elevation-1)]" aria-label="Fees for next term">
                        <div class="border-b border-[var(--outline-variant)] bg-[var(--surface-container)] px-3 py-2 sm:px-4 sm:py-3">
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-[var(--on-surface)] sm:text-sm">Fees &amp; charges</h3>
                            <p class="mt-0.5 text-[0.65rem] text-[var(--on-surface-variant)]">{{ $term }} · {{ $session }}</p>
                        </div>
                        @if($fees->isEmpty())
                            <div class="bg-[var(--surface-container-lowest)] px-3 py-4 text-center text-sm text-[var(--on-surface-variant)] sm:px-4">
                                No fee lines published for this term and session.
                            </div>
                        @else
                            <div class="divide-y divide-[var(--outline-variant)] bg-[var(--surface-container-lowest)]">
                                @foreach($feeCategoryOrder as $cat)
                                    @php $catFees = $feesGrouped->get($cat->value, collect()); @endphp
                                    @if($catFees->isNotEmpty())
                                        <div class="px-3 py-3 sm:px-4">
                                            <p class="mb-2 text-[0.65rem] font-bold uppercase tracking-wide text-[var(--on-surface-variant)]">{{ $cat->label() }}</p>
                                            <ul class="space-y-2">
                                                @foreach($catFees as $fee)
                                                    <li class="rounded-lg border border-[var(--outline-variant)] bg-[var(--surface-container-lowest)] p-2.5 sm:p-3">
                                                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                                                            <p class="text-sm font-semibold text-[var(--on-surface)]">{{ $fee->title }}</p>
                                                            <p class="text-sm font-bold tabular-nums text-[var(--on-surface)]">₦{{ number_format((float) $fee->amount, 2) }}</p>
                                                        </div>
                                                        @if($fee->description)
                                                            <p class="mt-1.5 text-xs leading-relaxed text-[var(--on-surface-variant)]">{{ $fee->description }}</p>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </section>
                @endif

                @if($checklists->isNotEmpty())
                    <section class="overflow-x-auto rounded-2xl border border-[var(--outline-variant)] shadow-[var(--elevation-1)]" aria-label="Checklist">
                        <div class="border-b border-[var(--outline-variant)] bg-[var(--surface-container)] px-3 py-2 sm:px-4 sm:py-3">
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-[var(--on-surface)] sm:text-sm">Checklist</h3>
                            <p class="mt-0.5 text-[0.65rem] text-[var(--on-surface-variant)]">In order</p>
                        </div>
                        @if($checklists->isEmpty())
                            <div class="bg-[var(--surface-container-lowest)] px-3 py-4 text-center text-sm text-[var(--on-surface-variant)] sm:px-4">
                                No checklist items for this term and session.
                            </div>
                        @else
                            <table class="result-sheet-table w-full border-collapse text-sm">
                                <thead>
                                    <tr class="bg-[var(--surface-container)]">
                                        <th class="w-10 border border-[var(--outline-variant)] px-2 py-2 text-center text-[0.65rem] font-semibold uppercase text-[var(--on-surface-variant)]">#</th>
                                        <th class="border border-[var(--outline-variant)] px-2 py-2 text-left text-[0.65rem] font-semibold uppercase text-[var(--on-surface-variant)]">Item</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($checklists as $item)
                                        <tr class="bg-[var(--surface-container-lowest)]">
                                            <td class="border border-[var(--outline-variant)] px-2 py-1.5 text-center text-sm font-semibold tabular-nums text-[var(--on-surface)]">{{ $loop->iteration }}</td>
                                            <td class="border border-[var(--outline-variant)] px-2 py-1.5 text-left text-sm leading-snug text-[var(--on-surface)]">{{ $item->title }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </section>
                @endif
            </div>
        </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            function mmToPx(mm) {
                return (mm * 96) / 25.4;
            }

            function applyPrintScale() {
                const sheet = document.getElementById('infoForm');
                const inner = document.querySelector('.result-print-inner');
                if (!sheet || !inner) return;

                /* A4 portrait printable height ≈ 297mm − 10mm (@page margins) */
                const pageH = mmToPx(297) - mmToPx(10);
                const contentH = inner.scrollHeight;

                if (contentH <= 0 || !isFinite(contentH)) return;

                let scale = Math.min(1, (pageH / contentH) * 0.98);
                if (scale < 0.42) {
                    scale = 0.42;
                }
                if (scale < 0.999) {
                    sheet.style.setProperty('--result-print-scale', scale.toFixed(4));
                } else {
                    sheet.style.setProperty('--result-print-scale', '1');
                }
            }

            function clearPrintScale() {
                const sheet = document.getElementById('infoForm');
                if (sheet) {
                    sheet.style.removeProperty('--result-print-scale');
                }
            }

            window.addEventListener('beforeprint', function() {
                requestAnimationFrame(function() {
                    applyPrintScale();
                });
            });
            window.addEventListener('afterprint', clearPrintScale);

            window.downloadPDF = function() {
                window.print();
            };
        })();
    </script>
@endpush
