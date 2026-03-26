@extends('layouts.guest', ['title' => $title])

@section('content')
    <main id="main-content">
        <div class="animate-in fade-in duration-700 bg-stone-50 font-sans selection:bg-educave-200 selection:text-educave-900">
            <section class="relative min-h-[80vh] bg-educave-900 text-stone-50 flex flex-col justify-between overflow-hidden">
                <div class="absolute inset-0 z-0">
                    <div class="absolute right-0 top-0 w-2/3 h-full opacity-20">
                        <img class="w-full h-full object-cover mix-blend-overlay grayscale" alt="texture" src="{{ asset('assets/img/right_1.jpeg') }}"/>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-r from-educave-900 via-educave-900/95 to-transparent"></div>
                    <div class="absolute left-16 top-0 w-px h-full bg-white/5 hidden md:block"></div>
                    <div class="absolute left-1/3 top-0 w-px h-full bg-white/5 hidden md:block"></div>
                    <div class="absolute right-16 top-0 w-px h-full bg-white/5 hidden md:block"></div>
                </div>

                <div class="relative z-10 container mx-auto px-6 md:px-16 flex-grow flex items-center py-20">
                    <div class="max-w-4xl">
                    <span class="inline-block rounded-xl px-4 py-2 border border-red-500/30 text-educave-300 text-[10px] font-bold uppercase tracking-[0.2em] mb-8 hover:bg-educave-800/10 transition-colors cursor-default">Running since 2006</span>
                        <h1 class="text-6xl md:text-8xl font-serif font-bold leading-[0.9] tracking-tight mb-8">
                            <span class="block">Academic</span><span class="block">Overview</span>
                        </h1>
                        <p class="text-xl md:text-2xl text-stone-400 font-serif italic max-w-2xl border-l-2 border-red-500 pl-6 my-10">
                            Junior & Senior Secondary Education (JSS 1 - SS 3)
                        </p>

                        <div class="flex flex-col md:flex-row gap-6 mt-12">
                            <a href="{{ route('apply_online') }}" class="rounded-xl bg-white text-educave-900 px-10 py-5 text-sm font-bold uppercase tracking-widest hover:bg-educave-800 hover:text-white transition-all shadow-[8px_8px_0px_0px_rgba(255,255,255,0.2)] hover:shadow-none translate-x-0 hover:translate-x-1 hover:translate-y-1">
                                Start Application
                            </a>

                            <a href="mailto:{{ config('school.school_email') }}" class="rounded-xl px-10 py-5 text-sm font-bold uppercase tracking-widest text-white border border-white/30 hover:bg-white hover:text-educave-900 transition-colors flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right" aria-hidden="true">
                                    <path d="M7 7h10v10"></path>
                                    <path d="M7 17 17 7"></path>
                                </svg>
                                Contact Admissions
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <div class="container mx-auto px-6 md:px-16 py-24">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                    <div class="lg:col-span-3 hidden lg:block">
                        <div class="sticky top-24 border-l border-gray-200 pl-6 space-y-6">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-6">Contents</span>
                            <a href="#overview" class="block text-sm font-bold text-gray-400 hover:text-educave-900 hover:translate-x-2 transition-all text-left uppercase tracking-wider">
                                Overview
                            </a>

                            <a href="#curriculum" class="block text-sm font-bold text-gray-400 hover:text-educave-900 hover:translate-x-2 transition-all text-left uppercase tracking-wider">
                                Curriculum
                            </a>

                            <a href="#contact" class="block text-sm font-bold text-gray-400 hover:text-educave-900 hover:translate-x-2 transition-all text-left uppercase tracking-wider">
                                Contact Us
                            </a>

                            <div class="pt-12 mt-12 border-t border-gray-200">
                                <p class="text-xs text-gray-400 mb-4 font-serif italic">
                                    "Education is the kindling of a flame, not the filling of a vessel."
                                </p>
                                <p class="text-[10px] font-bold text-educave-900 uppercase tracking-widest">— Socrates</p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-9 space-y-32">
                        <section id="overview" class="scroll-mt-24">
                            <h2 class="text-8xl text-gray-100 font-serif font-bold mb-[-40px] relative z-0 select-none">
                                01
                            </h2>

                            <div class="relative z-10 pl-4 border-l-4 border-educave-900 ml-4">
                            <span class="text-educave-700 font-bold tracking-widest text-xs uppercase block mb-4">Academic program</span>
                                <h3 class="text-4xl md:text-5xl font-serif font-bold text-educave-900 mb-8">
                                    Program <span class="italic font-light">Overview</span>
                                </h3>
                            </div>

                            <div class="grid md:grid-cols-2 gap-12 mt-12">
                                <div class="text-lg text-gray-600 leading-relaxed space-y-6 font-light">
                                    <p class="first-letter:text-6xl first-letter:font-serif first-letter:float-left first-letter:mr-3 first-letter:mt-[-10px] first-letter:text-educave-900">
                                        PJP runs a six year program of studies consisting of three years of Junior Secondary School (JSS 1 (Basic 7) – JSS 3 (Basic 9)) and three years of Senior Secondary (SSS 1 – SSS 3) in line with the Federal Ministry of Education in Nigeria.
                                    </p>

                                    <p>
                                        The Junior Secondary is concluded with the Basic Education Certificate Examination (BECE) while the Senior Secondary is concluded with the Senior Secondary Certificate Examination (SSCE).
                                    </p>

                                    <p>
                                        We have three departments: Department of Science and Technology, Department of Humanities and Department of Business and Entrepreneurship. Each department is led by a Head of Department (HOD). Every department is made up of units led by a Head of Unit (HOU). The HOUs report to the HODs who in turn report to the Dean of Studies.
                                    </p>

                                    <p>
                                        PJP runs a class system in the Junior and Senior Secondary. Each of the Years is divided into class A to C. A Class Teacher is assigned to each class. He or she is the first line of pastoral care to the students and first line of contact with the parents. Subjects offered in the school are handled by qualified teachers.
                                    </p>
                                </div>
                                <div class="relative h-full min-h-[300px] bg-gray-100 rounded-lg overflow-hidden group">
                                    <img alt="Students and learning at PJP" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 grayscale group-hover:grayscale-0" src="{{ asset('assets/img/about-2.jpg') }}"/>
                                    <div class="absolute inset-0 bg-educave-900/10 group-hover:bg-transparent transition-colors"></div>
                                </div>
                            </div>
                        </section>

                        <section id="curriculum" class="scroll-mt-24">
                            <h2 class="text-8xl text-gray-100 font-serif font-bold mb-[-40px] relative z-0 select-none">
                                02
                            </h2>
                            <div class="relative z-10 pl-4 border-l-4 border-educave-900 ml-4 mb-12">
                                <span class="text-educave-700 font-bold tracking-widest text-xs uppercase block mb-4">Academic Path</span>
                                <h3 class="text-4xl md:text-5xl font-serif font-bold text-educave-900">
                                    Curriculum <span class="italic font-light">Structure</span>
                                </h3>
                            </div>

                            <div class="border-t border-gray-200 pt-12 space-y-16 text-gray-600">
                                <p class="text-lg leading-relaxed font-light">
                                    PJP is faithful to the National Education Policy in running both the Junior and Senior Secondary Curriculum where students are exposed to a wide range of subjects. In the Junior Secondary, every student is expected to acquire the basic skills in all the subjects. Subject teachers have a period of 40 minutes to deliver each lesson.
                                </p>

                                <div class="space-y-10">
                                    <h4 class="text-2xl md:text-3xl font-serif font-bold text-educave-900 border-b border-gray-200 pb-4">
                                        Junior Secondary Curriculum
                                    </h4>

                                    <div class="grid gap-10 md:grid-cols-2 md:gap-x-12 md:gap-y-10">
                                        <div class="space-y-3">
                                            <p class="text-xs font-bold uppercase tracking-widest text-educave-800">English Studies</p>
                                            <ul class="space-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                                <li>English Language</li>
                                                <li>Literature in English</li>
                                            </ul>
                                        </div>
                                        <div class="space-y-3">
                                            <p class="text-xs font-bold uppercase tracking-widest text-educave-800">Mathematics</p>
                                            <ul class="space-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                                <li>Mathematics</li>
                                            </ul>
                                        </div>
                                        <div class="space-y-3">
                                            <p class="text-xs font-bold uppercase tracking-widest text-educave-800">Igbo Language</p>
                                            <ul class="space-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                                <li>Igbo Language 1</li>
                                                <li>Igbo Language 2</li>
                                            </ul>
                                        </div>
                                        <div class="space-y-3 md:col-span-2">
                                            <p class="text-xs font-bold uppercase tracking-widest text-educave-800">Basic Science and Technology (BST)</p>
                                            <ul class="grid sm:grid-cols-2 gap-x-8 gap-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                                <li>Basic Science</li>
                                                <li>Basic Technology</li>
                                                <li>Physical and Health Education</li>
                                            </ul>
                                        </div>
                                        <div class="space-y-3">
                                            <p class="text-xs font-bold uppercase tracking-widest text-educave-800">National Values Education (NVE)</p>
                                            <ul class="space-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                                <li>Social Studies</li>
                                                <li>Civic/Security Education</li>
                                            </ul>
                                        </div>
                                        <div class="space-y-3">
                                            <p class="text-xs font-bold uppercase tracking-widest text-educave-800">Pre Vocational Studies (PVS)</p>
                                            <ul class="space-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                                <li>Agricultural Science</li>
                                                <li>Home Economics</li>
                                            </ul>
                                        </div>
                                        <div class="space-y-3">
                                            <p class="text-xs font-bold uppercase tracking-widest text-educave-800">Cultural and Creative Art</p>
                                            <ul class="space-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                                <li>Art and Craft</li>
                                                <li>Music/Dance and Drama</li>
                                            </ul>
                                        </div>
                                        <div class="space-y-3">
                                            <p class="text-xs font-bold uppercase tracking-widest text-educave-800">Other subjects</p>
                                            <ul class="space-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                                <li>Business Studies</li>
                                                <li>French Language</li>
                                                <li>Christian Religious Studies</li>
                                                <li>History</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <p class="text-base font-medium text-educave-900 border-l-4 border-educave-800 pl-4 py-2 bg-educave-50/80">
                                        Every student in junior secondary class is expected to do all 11 subjects.
                                    </p>
                                </div>

                                <div class="space-y-10 pt-4 border-t border-gray-200">
                                    <h4 class="text-2xl md:text-3xl font-serif font-bold text-educave-900 border-b border-gray-200 pb-4">
                                        Senior Secondary Curriculum
                                    </h4>

                                    <p class="text-lg leading-relaxed font-light">
                                        Within these three years, a student is expected to do all the senior Secondary subjects on offer at the school at least for the duration of 1st term of year one (1st term of SS1).
                                    </p>

                                    <div class="space-y-3">
                                        <p class="text-xs font-bold uppercase tracking-widest text-educave-800">Compulsory Subjects</p>
                                        <ul class="space-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                            <li>English Studies</li>
                                            <li>General Mathematics</li>
                                            <li>Civic Education</li>
                                            <li>Catering Craft Practice <span class="text-gray-500">or</span> Bookkeeping</li>
                                        </ul>
                                    </div>

                                    <div class="space-y-3">
                                        <p class="text-xs font-bold uppercase tracking-widest text-educave-800">Core Subjects</p>
                                        <p class="text-sm text-gray-500 italic mb-2">Each student takes two of the following subjects.</p>
                                        <ul class="grid sm:grid-cols-2 gap-x-8 gap-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                            <li>Chemistry</li>
                                            <li>Government</li>
                                            <li>Literature-in-English</li>
                                            <li>Physics</li>
                                        </ul>
                                    </div>

                                    <div class="space-y-3">
                                        <p class="text-xs font-bold uppercase tracking-widest text-educave-800">Complementary Subjects</p>
                                        <p class="text-sm text-gray-500 italic mb-2">Each student takes at least one of the following subjects.</p>
                                        <ul class="grid sm:grid-cols-2 gap-x-8 gap-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                            <li>Biology</li>
                                            <li>Economics</li>
                                        </ul>
                                    </div>

                                    <div class="space-y-3">
                                        <p class="text-xs font-bold uppercase tracking-widest text-educave-800">Elective Subjects</p>
                                        <p class="text-sm text-gray-500 italic mb-2">Each student takes at least one of the following subjects.</p>
                                        <ul class="grid sm:grid-cols-2 gap-x-8 gap-y-2 text-base font-light leading-relaxed list-disc list-inside marker:text-educave-700">
                                            <li>Accounting</li>
                                            <li>Agricultural Science</li>
                                            <li>Christian Religious Studies</li>
                                            <li>Commerce</li>
                                            <li>French</li>
                                            <li>Further Mathematics</li>
                                            <li>Geography</li>
                                            <li>Igbo Language</li>
                                        </ul>
                                    </div>

                                    <div class="space-y-3">
                                        <p class="text-xs font-bold uppercase tracking-widest text-educave-800">Additional School Subjects</p>

                                        <p class="text-base font-light leading-relaxed space-y-4">
                                            Doctrinal Education and CRK are compulsory for all students in Year one and Year Two. The two complementary subjects are compulsory for all SS1 Students. Where additional school subject, for instance CRK, happens to be part of the subject of choice for SSCE for a candidate, Igbo Language or Computer could be used as alternatives to complete the number of subjects required for a class.
                                        </p>

                                        <p class="text-base font-light leading-relaxed">
                                            From the 2nd Term of Year One each student must offer not less than 12 subjects: the 4 compulsory subjects, 2 core subjects, 2 complementary subjects, 2 elective subjects and the 2 additional school subject. Year Two must offer only 11 subjects following the guide provided.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section id="contact" class="scroll-mt-24">
                            <div class="bg-stone-100 border border-stone-200 p-8 md:p-16 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-12 h-12 text-educave-900 mx-auto mb-6" aria-hidden="true">
                                    <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path>
                                    <circle cx="12" cy="8" r="6"></circle>
                                </svg>

                                <h2 class="text-3xl md:text-4xl font-serif font-bold text-educave-900 mb-6">
                                    Have any questions?
                                </h2>

                                <p class="text-gray-600 max-w-lg mx-auto mb-10 text-lg">
                                    Our admissions office is happy to assist. Reach us by phone or email and we will guide you through every step of the process.
                                </p>

                                <a href="mailto:{{ config('school.school_email') }}" class="rounded-xl bg-educave-900 text-white px-12 py-5 text-sm font-bold uppercase tracking-widest hover:bg-educave-700 transition-colors shadow-xl">
                                    Contact Us
                                </a>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('styles')
    <style>
        @media (prefers-reduced-motion: no-preference) {
            html {
                scroll-behavior: smooth;
            }
        }
    </style>
@endpush
