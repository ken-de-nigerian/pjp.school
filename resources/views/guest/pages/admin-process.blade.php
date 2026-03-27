@php use Carbon\Carbon; @endphp
@extends('layouts.guest', ['title' => $title])

@section('content')
    @php
        $examDate = Carbon::parse('third saturday of march ' . now()->year);
    @endphp
    <main id="main-content">
        <div class="animate-in fade-in duration-700 font-sans bg-educave-50 selection:bg-educave-900 selection:text-white">
            <section class="relative border-b border-gray-200 bg-white">
                <div class="container mx-auto px-4 md:px-8 lg:px-16">
                    <div class="flex flex-col lg:flex-row min-h-[80vh]">
                        <div class="w-full lg:w-1/2 py-16 lg:py-24 pr-0 lg:pr-16 flex flex-col justify-center">
                            <div class="flex items-center gap-2 mb-8">
                                <span class="w-8 h-px bg-educave-800"></span>
                                <span class="text-xs font-bold uppercase tracking-widest text-educave-800">Admissions {{ date('Y') }}</span>
                            </div>
                            <h1 class="text-6xl md:text-8xl font-serif font-bold text-educave-900 leading-[0.9] tracking-tighter mb-8">
                                BEGIN <br/>YOUR <br/><span class="italic text-gray-400">JOURNEY.</span>
                            </h1>
                            <p class="text-lg text-gray-600 font-serif leading-relaxed max-w-md mb-12">
                                "We are not just looking for bright students — we are looking for children who are ready to be formed. If your child is prepared to grow in faith, character and excellence, their journey starts here."
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('apply_online') }}" class="rounded-xl bg-educave-900 text-white px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-educave-700 transition-colors flex items-center justify-center gap-2 group">
                                    Start Application
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right group-hover:translate-x-1 transition-transform" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </a>

                                <a href="mailto:{{ config('school.school_email') }}"
                                   class="rounded-xl border border-gray-300 text-educave-900 px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-educave-50 transition-colors flex items-center justify-center gap-2">
                                    Contact Admissions
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail" aria-hidden="true"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                                </a>
                            </div>
                        </div>

                        <div class="w-full lg:w-1/2 relative min-h-[400px] lg:min-h-full border-l border-gray-200">
                            <div class="absolute inset-0 overflow-hidden group">
                                <img alt="PJP Students" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-1000 scale-100 group-hover:scale-105" src="{{ asset('assets/img/about-5.jpeg') }}"/>
                                <div class="absolute inset-0 bg-educave-900/10 mix-blend-multiply pointer-events-none"></div>
                                <div class="absolute bottom-0 left-0 w-full bg-white/90 backdrop-blur border-t border-gray-200 p-8 hidden md:flex justify-between items-center">
                                    <div>
                                        <p class="text-3xl font-serif font-bold text-educave-900">Est. 2006</p>
                                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Founded</p>
                                    </div>

                                    <div class="h-8 w-px bg-gray-300"></div>

                                    <div>
                                        <p class="text-3xl font-serif font-bold text-educave-900">60%</p>
                                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Pass Target</p>
                                    </div>

                                    <div class="h-8 w-px bg-gray-300"></div>

                                    <div>
                                        <p class="text-3xl font-serif font-bold text-educave-900">
                                            {{ $examDate->format('jS D') }}
                                        </p>
                                        <p class="text-xs font-bold uppercase tracking-widest text-educave-800">
                                            {{ $examDate->format('F') }} Exam
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="padding-custom bg-educave-50">
                <div class="container mx-auto px-4 md:px-8 lg:px-16">
                    <div class="mb-16">
                        <span class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 block">The Roadmap</span>
                        <h2 class="text-4xl font-serif font-bold text-educave-900">
                            Admission <span class="italic text-gray-400">Process</span>
                        </h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        @php
                            $steps = [
                                ['num' => '01', 'title' => 'Apply Online',   'desc' => 'Click the apply online button to register for the entrance examination and create your applicant profile.'],
                                ['num' => '02', 'title' => 'Sit the Exam',   'desc' => 'Attend the entrance examination held every 3rd Saturday of March at one of our accredited exam centres.'],
                                ['num' => '03', 'title' => 'Submit Documents', 'desc' => 'Provide your last term\'s result, transfer certificate, birth certificate, passport photographs and other required documents.'],
                                ['num' => '04', 'title' => 'Receive Decision', 'desc' => 'Successful candidates are notified and offered a place. Admission is confirmed upon payment of the acceptance fee.'],
                            ]
                        @endphp
                        @foreach ($steps as $step)
                            <div class="group relative pt-8 border-t border-gray-300 hover:border-educave-800 transition-colors duration-500">
                                <span class="absolute -top-3 left-0 text-xs font-bold bg-educave-50 pr-2 text-gray-400 group-hover:text-educave-700 transition-colors">STEP {{ $step['num'] }}</span>
                                <h3 class="text-2xl font-serif font-bold text-educave-900 mb-4 group-hover:translate-x-2 transition-transform duration-300">{{ $step['title'] }}</h3>
                                <p class="text-gray-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="padding-custom bg-white border-t border-gray-200">
                <div class="container mx-auto px-4 md:px-8 lg:px-16">
                    <div class="flex flex-col lg:flex-row gap-20">
                        <div class="w-full lg:w-1/2">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 block">Exam Centres & Date</span>
                            <h2 class="text-4xl font-serif font-bold text-educave-900 mb-6">
                                Where to sit the <span class="italic text-gray-400">entrance exam</span>
                            </h2>
                            <p class="text-gray-500 leading-relaxed mb-12">The entrance examination holds every <strong class="text-educave-900">3rd Saturday of March</strong> annually. Candidates may sit the exam at any of the following centres:</p>
                            <div class="space-y-6">
                                @php
                                    $centres = [
                                        ['name' => 'Pope John Paul II School Premises', 'location' => 'Umunagbor Amagbor Ihitte, Ezinihitte Mbaise, Imo State', 'primary' => true],
                                        ['name' => 'CIWA Port Harcourt',                'location' => 'Port Harcourt, Rivers State',                           'primary' => false],
                                        ['name' => 'St. Columba Parish Owerri',         'location' => 'Owerri, Imo State',                                     'primary' => false],
                                    ]
                                @endphp
                                @foreach ($centres as $centre)
                                    <div class="flex gap-6 p-6 border border-gray-200 hover:border-educave-800 transition-colors group">
                                        <div class="w-10 h-10 {{ $centre['primary'] ? 'bg-educave-900' : 'bg-educave-50' }} flex items-center justify-center shrink-0 mt-1 group-hover:bg-educave-900 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $centre['primary'] ? 'text-white' : 'text-educave-900' }} group-hover:text-white transition-colors" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-educave-900 text-sm mb-1">
                                                {{ $centre['name'] }}
                                                @if($centre['primary'])
                                                    <span class="ml-2 text-[10px] bg-educave-900 text-white px-2 py-0.5 uppercase tracking-widest font-bold">Main Campus</span>
                                                @endif
                                            </p>
                                            <p class="text-gray-500 text-xs">{{ $centre['location'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="w-full lg:w-1/2">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4 block">Eligibility</span>
                            <h2 class="text-4xl font-serif font-bold text-educave-900 mb-6">
                                Who can <span class="italic text-gray-400">apply?</span>
                            </h2>
                            <div class="space-y-6">
                                @php
                                    $eligibility = [
                                        ['level' => 'JSS 1 (Basic 7)',  'req' => 'Candidates must have completed Basic Six (Primary 6) and be at least 11 years old by the end of the JSS 1 academic year.'],
                                        ['level' => 'JSS 2 – SSS 1 (Transfers)', 'req' => 'Transfer students are accepted from Basic 8 through SSS 1. Candidates must pass the entrance/evaluation examination, provide their transcript or last term\'s result and a transfer certificate from their previous school.'],
                                        ['level' => 'SSS 1 (from JSS)',           'req' => 'Students seeking admission into SSS 1 from another school must provide their BECE result or evidence of having sat for the examination.'],
                                    ]
                                @endphp
                                @foreach ($eligibility as $e)
                                    <div class="border-l-4 border-educave-800 pl-6 py-2">
                                        <p class="font-bold text-educave-900 text-sm uppercase tracking-widest mb-2">{{ $e['level'] }}</p>
                                        <p class="text-gray-500 text-sm leading-relaxed">{{ $e['req'] }}</p>
                                    </div>
                                @endforeach

                                <div class="mt-8 p-6 bg-educave-50 border border-educave-800/20">
                                    <p class="text-xs font-bold uppercase tracking-widest text-educave-800 mb-2">Promotion Policy</p>
                                    <p class="text-gray-600 text-sm leading-relaxed">Promotion to the next class is based entirely on student performance. PJP sets a target of <strong>60% across all subjects</strong>. Students who fall short may be required to repeat the class. We want our students to continually seek self-improvement — this is how we maintain our standard.</p>
                                </div>

                                <div class="p-6 bg-educave-50 border border-educave-800/20">
                                    <p class="text-xs font-bold uppercase tracking-widest text-educave-800 mb-2">Graduation Requirement</p>
                                    <p class="text-gray-600 text-sm leading-relaxed">Students graduate from PJP after fulfilling the minimum requirement of sitting for the <strong>Senior School Certificate Examination (SSCE)</strong> and demonstrating strict faithfulness to the school's rules and regulations throughout their time in the school.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-educave-900 text-white overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2 relative min-h-[500px]">
                        <img alt="PJP School" class="absolute inset-0 w-full h-full object-cover opacity-60 grayscale hover:grayscale-0 transition-all duration-1000" src="{{ asset('assets/img/about-2.jpg') }}"/>
                        <div class="absolute inset-0 bg-gradient-to-r from-educave-900 to-transparent"></div>
                        <div class="absolute bottom-12 left-12 max-w-md">
                            <p class="font-serif text-2xl italic leading-relaxed mb-4">"Every child who walks through our gate carries potential. Our job is to make sure they leave with character, knowledge and faith."</p>
                            <p class="text-xs font-bold uppercase tracking-widest text-educave-400">— School Principal</p>
                        </div>
                    </div>

                    <div class="w-full lg:w-1/2 p-16 lg:p-24">
                        <span class="text-educave-400 font-bold tracking-widest text-xs uppercase mb-6 block">Checklist</span>
                        <h2 class="text-4xl font-serif font-bold mb-12">Required Documents</h2>
                        <div class="space-y-6">
                            @php
                                $docs = [
                                    'Completed application / entrance exam registration form',
                                    'Birth certificate or age declaration',
                                    'Primary school leaving certificate (JSS 1 applicants)',
                                    'Last term\'s result / transcript from previous school',
                                    'Transfer certificate (transfer applicants)',
                                    'BECE result or evidence of sitting (SSS 1 applicants)',
                                    'Two recent passport photographs',
                                ]
                            @endphp
                            @foreach ($docs as $i => $doc)
                                <div class="flex items-center gap-6 group cursor-default">
                                    <div class="w-8 h-8 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-educave-900 transition-colors shrink-0">
                                        <span class="text-xs font-bold">{{ $i + 1 }}</span>
                                    </div>
                                    <span class="text-lg text-gray-300 font-serif group-hover:text-white transition-colors border-b border-transparent group-hover:border-white/20 pb-1">{{ $doc }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-12 pt-12 border-t border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Next Exam Date</p>
                                    <p class="text-2xl font-serif font-bold text-white">{{ $examDate->format('jS l') }}, {{ $examDate->format('F') }} {{ date('Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="padding-custom bg-educave-50 border-t border-gray-200">
                <div class="container mx-auto px-4 md:px-8 lg:px-16">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
                        <div>
                            <h3 class="text-3xl font-serif font-bold text-educave-900 mb-3">Have more questions?</h3>
                            <p class="text-gray-500 text-sm leading-relaxed max-w-lg">Our admissions office is happy to assist. Reach us by phone or email and we will guide you through every step of the process.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4 shrink-0">
                            <a href="tel:{{ config('school.school_phone') }}" class="flex items-center gap-3 rounded-xl px-8 py-4 border border-gray-300 text-educave-900 text-xs font-bold uppercase tracking-widest hover:bg-educave-900 hover:text-white hover:border-educave-900 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
                                {{ config('school.school_phone') }}
                            </a>
                            <a href="mailto:{{ config('school.school_email') }}" class="flex items-center gap-3 rounded-xl px-8 py-4 bg-educave-900 text-white text-xs font-bold uppercase tracking-widest hover:bg-educave-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                                {{ config('school.school_email') }}
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
