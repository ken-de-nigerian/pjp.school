@extends('layouts.guest')

@section('content')
    <main id="main-content">
        <div class="transition-opacity duration-1000 opacity-100 font-sans text-educave-975 bg-educave-50 selection:bg-educave-200">

            {{-- Hero --}}
            <section class="relative pt-32 pb-24 overflow-hidden bg-educave-900">
                <div class="absolute inset-0 z-0">
                    <img alt="PJP School" class="w-full h-full object-cover opacity-10 scale-105" src="{{ asset('assets/img/right_1.jpeg') }}"/>
                    <div class="absolute inset-0 bg-gradient-to-b from-educave-900/80 via-educave-900/60 to-educave-900"></div>
                </div>
                <div class="container mx-auto px-6 md:px-12 lg:px-24 relative z-10">
                    <div class="max-w-4xl">
                        <div class="inline-flex items-center gap-3 mb-8 px-4 py-2 rounded-full border border-white/10 bg-white/5 backdrop-blur-md animate-in slide-in-from-top duration-700">
                            <span class="h-2 w-2 shrink-0 rounded-full bg-pjp-yellow shadow-[0_0_0_3px_rgba(242,230,49,0.2)]" aria-hidden="true"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-pjp-gold-bright" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-white/70">Frequently Asked Questions</span>
                        </div>
                        <h1 class="text-6xl md:text-8xl font-serif font-bold text-white mb-8 leading-[0.9] tracking-tighter animate-in fade-in slide-in-from-bottom duration-1000">
                            Got <br/><span class="text-educave-400 italic">Questions?</span>
                        </h1>
                        <p class="text-xl md:text-2xl text-white/60 font-light leading-relaxed max-w-2xl animate-in fade-in slide-in-from-bottom duration-1000 delay-200">
                            Everything parents and prospective students need to know about life at {{ site_settings()->name }} answered honestly.
                        </p>
                    </div>
                </div>
            </section>

            {{-- FAQ Body --}}
            <section class="py-24">
                <div class="guest-crest-divider guest-crest-divider--short max-w-lg mx-auto mb-16 opacity-70" aria-hidden="true"></div>
                <div class="container mx-auto px-6 md:px-12 lg:px-24">
                    <div id="faqs-page" class="flex flex-col lg:flex-row gap-20">

                        {{-- Sidebar --}}
                        <div class="w-full lg:w-1/3">
                            <div class="sticky top-12">
                                <div class="inline-flex items-center gap-2 mb-8">
                                    <div class="h-px w-8 bg-pjp-gold-500"></div>
                                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-educave-800">Browse Topics</span>
                                </div>
                                <div class="space-y-3" role="tablist" aria-label="FAQ topics">
                                    @foreach($faqTopics as $key => $topic)
                                        <button type="button" role="tab" id="faq-tab-{{ $key }}"
                                                class="faq-topic-btn w-full group text-left p-6 rounded-[24px] border transition-all duration-500 flex items-center justify-between {{ $loop->first ? 'bg-educave-800 border-educave-800 text-white shadow-2xl shadow-educave-900/20' : 'bg-white border-educave-900/5 text-educave-900 hover:border-educave-800/30' }}"
                                                data-faq-topic="{{ $key }}" data-faq-label="{{ $topic['label'] }}"
                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                            <div class="flex items-center gap-4">
                                                <div class="faq-topic-dot w-2 h-2 rounded-full transition-all duration-500 {{ $loop->first ? 'bg-pjp-yellow shadow-[0_0_0_2px_rgba(242,230,49,0.35)]' : 'bg-educave-900/10 group-hover:bg-educave-800/30' }}"></div>
                                                <span class="text-sm font-bold uppercase tracking-widest">{{ $topic['sidebar'] }}</span>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="faq-topic-chevron lucide lucide-chevron-right transition-transform duration-500 {{ $loop->first ? 'translate-x-1 opacity-100' : 'opacity-0' }}" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                        </button>
                                    @endforeach
                                </div>

                                <div class="mt-12 p-8 rounded-[32px] bg-educave-900 text-white relative overflow-hidden group">
                                    <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-[1.5s]"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square text-educave-400 mb-6" aria-hidden="true"><path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"/></svg>
                                    <h4 class="text-xl font-serif font-bold mb-4">Still have questions?</h4>
                                    <p class="text-white/50 text-sm font-light leading-relaxed mb-8">Reach us directly. Our staff are happy to help with any enquiry about admissions, boarding or school life.</p>
                                    <a href="mailto:{{ config('school.school_email') }}" class="block w-full py-4 bg-educave-800 text-white font-bold uppercase tracking-widest text-[10px] rounded-2xl hover:bg-white hover:text-educave-900 transition-all duration-500 text-center">
                                        Email Us
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Panels --}}
                        <div class="w-full lg:w-2/3">
                            <div class="mb-12">
                                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-educave-800 mb-4 block">Active Category</span>
                                <h2 id="faq-active-title" class="text-5xl font-serif font-bold text-educave-900 mb-6">{{ $faqTopics[array_key_first($faqTopics)]['label'] }}</h2>
                                <div class="h-1 w-24 rounded-full bg-gradient-to-r from-pjp-gold-500 to-pjp-gold-600"></div>
                            </div>

                            @foreach($faqTopics as $key => $topic)
                                <div class="faq-topic-panel @if(!$loop->first) hidden @endif" data-faq-topic-panel="{{ $key }}" role="tabpanel" aria-labelledby="faq-tab-{{ $key }}">
                                    <div class="space-y-4">
                                        @foreach($topic['items'] as $item)
                                            @php
                                                $idx    = str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT);
                                                $isOpen = $loop->first;
                                            @endphp
                                            <div @class(['faq-accordion-item overflow-hidden rounded-[32px] border transition-all duration-700', 'bg-white border-educave-800/20 shadow-xl' => $isOpen, 'bg-transparent border-educave-900/5 hover:border-educave-800/20' => !$isOpen])>
                                                <button type="button" class="faq-accordion-trigger w-full text-left p-8 md:p-10 flex items-start justify-between gap-6 group" aria-expanded="{{ $isOpen ? 'true' : 'false' }}">
                                                    <div class="flex gap-6">
                                                        <span @class(['faq-acc-num text-xl font-serif font-bold transition-all duration-500', 'text-educave-800 scale-125' => $isOpen, 'text-educave-900/20' => !$isOpen])>{{ $idx }}</span>
                                                        <span @class(['faq-acc-title text-xl md:text-2xl font-serif font-bold leading-tight transition-colors duration-500', 'text-educave-900' => $isOpen, 'text-educave-900/70 group-hover:text-educave-800' => !$isOpen])>{{ $item['q'] }}</span>
                                                    </div>
                                                    <div @class(['faq-accordion-icon shrink-0 w-12 h-12 rounded-full border border-educave-900/10 flex items-center justify-center transition-all duration-700', 'bg-educave-800 border-educave-800 text-white rotate-45' => $isOpen, 'bg-white text-educave-900 group-hover:bg-educave-50' => !$isOpen])>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                                    </div>
                                                </button>
                                                <div @class(['faq-accordion-panel transition-all duration-700 ease-[cubic-bezier(0.4,0,0.2,1)]', 'max-h-[500px] opacity-100' => $isOpen, 'max-h-0 opacity-0 overflow-hidden' => !$isOpen])>
                                                    <div class="px-8 md:px-24 pb-12">
                                                        <div class="h-px w-12 bg-pjp-torch/75 mb-8"></div>
                                                        <p class="text-educave-900/60 text-lg font-light leading-relaxed">{{ $item['a'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            {{-- Contact cards --}}
                            <div class="mt-20 grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="p-10 rounded-[40px] bg-white border border-educave-900/5 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group">
                                    <div class="w-14 h-14 rounded-2xl bg-educave-100 flex items-center justify-center text-educave-800 mb-8 group-hover:bg-educave-800 group-hover:text-white transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail" aria-hidden="true"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                                    </div>
                                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-educave-600 mb-4">Email Us</h4>
                                    <a href="mailto:{{ config('school.school_email') }}" class="text-2xl font-serif font-bold text-educave-900 mb-4 block hover:text-educave-800 transition-colors">{{ config('school.school_email') }}</a>
                                    <p class="text-educave-900/40 text-xs font-light tracking-wide">We respond within one business day.</p>
                                </div>
                                <div class="p-10 rounded-[40px] bg-white border border-educave-900/5 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group">
                                    <div class="w-14 h-14 rounded-2xl bg-educave-100 flex items-center justify-center text-educave-800 mb-8 group-hover:bg-educave-800 group-hover:text-white transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
                                    </div>
                                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-educave-600 mb-4">Call Us</h4>
                                    <a href="tel:{{ config('school.school_phone') }}" class="text-2xl font-serif font-bold text-educave-900 mb-4 block hover:text-educave-800 transition-colors">{{ config('school.school_phone') }}</a>
                                    <p class="text-educave-900/40 text-xs font-light tracking-wide">Available Mon – Fri, 8:00 AM – 4:00 PM WAT.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Footer info strip --}}
            <section class="py-32 bg-educave-100/50">
                <div class="container mx-auto px-6 md:px-12 lg:px-24">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 text-center lg:text-left">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin text-educave-800 mb-6 mx-auto lg:mx-0" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                            <h3 class="text-2xl font-serif font-bold text-educave-900 mb-4">School Address</h3>
                            <p class="text-educave-900/50 text-sm leading-relaxed font-light">
                                Umunagbor Amagbor Ihitte,<br/>Ezinihitte Mbaise LGA,<br/>Imo State, Nigeria.
                            </p>
                        </div>
                        <div class="lg:border-x border-educave-900/10 px-12">
                            <h3 class="text-2xl font-serif font-bold text-educave-900 mb-4">School Hours</h3>
                            <p class="text-educave-900/50 text-sm leading-relaxed font-light">
                                Monday — Friday:<br/>7:30 AM — 4:00 PM<br/>Saturday & Sunday: Closed
                            </p>
                        </div>
                        <div>
                            <h3 class="text-2xl font-serif font-bold text-educave-900 mb-4">Visiting Day</h3>
                            <p class="text-educave-900/50 text-sm leading-relaxed font-light mb-6">Every last Sunday of the month. Parents and guardians are warmly welcome.</p>
                            <div class="flex justify-center lg:justify-start gap-3 flex-wrap">
                                @foreach(['WAEC', 'NECO', 'JAMB CBT', 'Catholic'] as $badge)
                                    <div class="px-3 py-1 rounded-lg bg-educave-800/10 text-educave-800 text-[10px] font-black tracking-widest uppercase">{{ $badge }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>
@endsection

@push('scripts')
    <script>
        (function () {
            const root = document.getElementById('faqs-page');
            if (!root) return;

            const ACC_ITEM_OPEN    = 'faq-accordion-item overflow-hidden rounded-[32px] border transition-all duration-700 bg-white border-educave-800/20 shadow-xl';
            const ACC_ITEM_CLOSED  = 'faq-accordion-item overflow-hidden rounded-[32px] border transition-all duration-700 bg-transparent border-educave-900/5 hover:border-educave-800/20';
            const NUM_OPEN         = 'faq-acc-num text-xl font-serif font-bold transition-all duration-500 text-educave-800 scale-125';
            const NUM_CLOSED       = 'faq-acc-num text-xl font-serif font-bold transition-all duration-500 text-educave-900/20';
            const TITLE_OPEN       = 'faq-acc-title text-xl md:text-2xl font-serif font-bold leading-tight transition-colors duration-500 text-educave-900';
            const TITLE_CLOSED     = 'faq-acc-title text-xl md:text-2xl font-serif font-bold leading-tight transition-colors duration-500 text-educave-900/70 group-hover:text-educave-800';
            const ICON_OPEN        = 'faq-accordion-icon shrink-0 w-12 h-12 rounded-full border border-educave-900/10 flex items-center justify-center transition-all duration-700 bg-educave-800 border-educave-800 text-white rotate-45';
            const ICON_CLOSED      = 'faq-accordion-icon shrink-0 w-12 h-12 rounded-full border border-educave-900/10 flex items-center justify-center transition-all duration-700 bg-white text-educave-900 group-hover:bg-educave-50';
            const PANEL_OPEN       = 'faq-accordion-panel transition-all duration-700 ease-[cubic-bezier(0.4,0,0.2,1)] max-h-[500px] opacity-100';
            const PANEL_CLOSED     = 'faq-accordion-panel transition-all duration-700 ease-[cubic-bezier(0.4,0,0.2,1)] max-h-0 opacity-0 overflow-hidden';
            const TOPIC_ACTIVE     = 'faq-topic-btn w-full group text-left p-6 rounded-[24px] border transition-all duration-500 flex items-center justify-between bg-educave-800 border-educave-800 text-white shadow-2xl shadow-educave-900/20';
            const TOPIC_INACTIVE   = 'faq-topic-btn w-full group text-left p-6 rounded-[24px] border transition-all duration-500 flex items-center justify-between bg-white border-educave-900/5 text-educave-900 hover:border-educave-800/30';
            const DOT_ACTIVE       = 'faq-topic-dot w-2 h-2 rounded-full transition-all duration-500 bg-pjp-yellow shadow-[0_0_0_2px_rgba(242,230,49,0.35)]';
            const DOT_INACTIVE     = 'faq-topic-dot w-2 h-2 rounded-full transition-all duration-500 bg-educave-900/10 group-hover:bg-educave-800/30';
            const CHEV_ACTIVE      = 'faq-topic-chevron lucide lucide-chevron-right transition-transform duration-500 translate-x-1 opacity-100';
            const CHEV_INACTIVE    = 'faq-topic-chevron lucide lucide-chevron-right transition-transform duration-500 opacity-0';

            function applyAccordionState(item, open) {
                const btn   = item.querySelector('.faq-accordion-trigger');
                const panel = item.querySelector('.faq-accordion-panel');
                const num   = item.querySelector('.faq-acc-num');
                const title = item.querySelector('.faq-acc-title');
                const icon  = item.querySelector('.faq-accordion-icon');
                if (!btn || !panel || !num || !title || !icon) return;
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                item.className  = open ? ACC_ITEM_OPEN   : ACC_ITEM_CLOSED;
                num.className   = open ? NUM_OPEN        : NUM_CLOSED;
                title.className = open ? TITLE_OPEN      : TITLE_CLOSED;
                icon.className  = open ? ICON_OPEN       : ICON_CLOSED;
                panel.className = open ? PANEL_OPEN      : PANEL_CLOSED;
            }

            root.addEventListener('click', function (e) {
                const topicBtn = e.target.closest('.faq-topic-btn');
                if (topicBtn && root.contains(topicBtn)) {
                    const topic = topicBtn.getAttribute('data-faq-topic');
                    const label = topicBtn.getAttribute('data-faq-label');
                    if (!topic) return;

                    document.querySelectorAll('[data-faq-topic-panel]').forEach(function (p) {
                        p.classList.toggle('hidden', p.getAttribute('data-faq-topic-panel') !== topic);
                    });
                    document.querySelectorAll('.faq-topic-btn').forEach(function (b) {
                        const active = b.getAttribute('data-faq-topic') === topic;
                        b.setAttribute('aria-selected', active ? 'true' : 'false');
                        b.className = active ? TOPIC_ACTIVE : TOPIC_INACTIVE;
                        const dot  = b.querySelector('.faq-topic-dot');
                        const chev = b.querySelector('.faq-topic-chevron');
                        if (dot)  dot.className  = active ? DOT_ACTIVE  : DOT_INACTIVE;
                        if (chev) chev.className = active ? CHEV_ACTIVE : CHEV_INACTIVE;
                    });

                    const titleEl = document.getElementById('faq-active-title');
                    if (titleEl && label) titleEl.textContent = label;

                    const panel = document.querySelector('[data-faq-topic-panel="' + topic + '"]');
                    if (panel) {
                        panel.querySelectorAll('.faq-accordion-item').forEach(function (item, i) {
                            applyAccordionState(item, i === 0);
                        });
                    }
                    return;
                }

                const trigger = e.target.closest('.faq-accordion-trigger');
                if (!trigger || !root.contains(trigger)) return;

                const item       = trigger.closest('.faq-accordion-item');
                const topicPanel = item.closest('.faq-topic-panel');
                if (!item || !topicPanel) return;

                const wasOpen = trigger.getAttribute('aria-expanded') === 'true';
                topicPanel.querySelectorAll('.faq-accordion-item').forEach(function (el) {
                    applyAccordionState(el, false);
                });
                if (!wasOpen) applyAccordionState(item, true);
            });
        })();
    </script>
@endpush
