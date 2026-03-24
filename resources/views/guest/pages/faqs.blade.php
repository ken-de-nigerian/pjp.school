@extends('layouts.guest')

@section('content')
    <main id="main-content">
        <div class="transition-opacity duration-1000 opacity-100 font-sans text-educave-975 bg-educave-50 selection:bg-educave-200">
            <section class="relative pt-32 pb-24 overflow-hidden bg-educave-900">
                <div class="absolute inset-0 z-0">
                    <img alt="University Architecture" class="w-full h-full object-cover opacity-10 scale-105" src="{{ asset('assets/img/right_1.jpeg') }}"/>
                    <div class="absolute inset-0 bg-gradient-to-b from-educave-900/80 via-educave-900/60 to-educave-900"></div>
                </div>

                <div class="container mx-auto px-6 md:px-12 lg:px-24 relative z-10">
                    <div class="max-w-4xl">
                        <div class="inline-flex items-center gap-3 mb-8 px-4 py-2 rounded-full border border-white/10 bg-white/5 backdrop-blur-md animate-in slide-in-from-top duration-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-question-mark text-educave-400" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <path d="M12 17h.01"></path>
                            </svg><span class="text-[10px] font-bold uppercase tracking-[0.3em] text-white/70">Support &amp; Guidance</span>
                        </div>

                        <h1 class="text-6xl md:text-8xl font-serif font-bold text-white mb-8 leading-[0.9] tracking-tighter animate-in fade-in slide-in-from-bottom duration-1000">
                            The Knowledge <br /><span class="text-educave-400 italic">Concierge.</span>
                        </h1>

                        <p class="text-xl md:text-2xl text-white/60 font-light leading-relaxed max-w-2xl animate-in fade-in slide-in-from-bottom duration-1000 delay-200">
                            Clarity on demand. Find comprehensive answers to the most frequent inquiries from our global
                            community.
                        </p>
                    </div>
                </div>
            </section>

            <section class="py-24">
                <div class="container mx-auto px-6 md:px-12 lg:px-24">
                    <div class="flex flex-col lg:flex-row gap-20">
                        <div class="w-full lg:w-1/3">
                            <div class="sticky top-12">
                                <div class="inline-flex items-center gap-2 mb-8">
                                    <div class="h-px w-8 bg-educave-600"></div>
                                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-educave-600"
                                    >Browse Topics</span
                                    >
                                </div>
                                <div class="space-y-3">
                                    <button
                                        class="w-full group text-left p-6 rounded-[24px] border transition-all duration-500 flex items-center justify-between bg-educave-800 border-educave-800 text-white shadow-2xl shadow-educave-900/20"
                                    >
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-2 h-2 rounded-full transition-all duration-500 bg-educave-400"
                                            ></div>
                                            <span class="text-sm font-bold uppercase tracking-widest">General Info</span>
                                        </div>
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-chevron-right transition-transform duration-500 translate-x-1 opacity-100"
                                            aria-hidden="true"
                                        >
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg></button
                                    ><button
                                        class="w-full group text-left p-6 rounded-[24px] border transition-all duration-500 flex items-center justify-between bg-white border-educave-900/5 text-educave-900 hover:border-educave-800/30"
                                    >
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-2 h-2 rounded-full transition-all duration-500 bg-educave-900/10 group-hover:bg-educave-800/30"
                                            ></div>
                                            <span class="text-sm font-bold uppercase tracking-widest">Admissions</span>
                                        </div>
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-chevron-right transition-transform duration-500 opacity-0"
                                            aria-hidden="true"
                                        >
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg></button
                                    ><button
                                        class="w-full group text-left p-6 rounded-[24px] border transition-all duration-500 flex items-center justify-between bg-white border-educave-900/5 text-educave-900 hover:border-educave-800/30"
                                    >
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-2 h-2 rounded-full transition-all duration-500 bg-educave-900/10 group-hover:bg-educave-800/30"
                                            ></div>
                                            <span class="text-sm font-bold uppercase tracking-widest">Academics</span>
                                        </div>
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-chevron-right transition-transform duration-500 opacity-0"
                                            aria-hidden="true"
                                        >
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg></button
                                    ><button
                                        class="w-full group text-left p-6 rounded-[24px] border transition-all duration-500 flex items-center justify-between bg-white border-educave-900/5 text-educave-900 hover:border-educave-800/30"
                                    >
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-2 h-2 rounded-full transition-all duration-500 bg-educave-900/10 group-hover:bg-educave-800/30"
                                            ></div>
                                            <span class="text-sm font-bold uppercase tracking-widest"
                                            >Housing &amp; Dining</span
                                            >
                                        </div>
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-chevron-right transition-transform duration-500 opacity-0"
                                            aria-hidden="true"
                                        >
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg></button
                                    ><button
                                        class="w-full group text-left p-6 rounded-[24px] border transition-all duration-500 flex items-center justify-between bg-white border-educave-900/5 text-educave-900 hover:border-educave-800/30"
                                    >
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-2 h-2 rounded-full transition-all duration-500 bg-educave-900/10 group-hover:bg-educave-800/30"
                                            ></div>
                                            <span class="text-sm font-bold uppercase tracking-widest"
                                            >Tuition &amp; Aid</span
                                            >
                                        </div>
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-chevron-right transition-transform duration-500 opacity-0"
                                            aria-hidden="true"
                                        >
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg></button
                                    ><button
                                        class="w-full group text-left p-6 rounded-[24px] border transition-all duration-500 flex items-center justify-between bg-white border-educave-900/5 text-educave-900 hover:border-educave-800/30"
                                    >
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-2 h-2 rounded-full transition-all duration-500 bg-educave-900/10 group-hover:bg-educave-800/30"
                                            ></div>
                                            <span class="text-sm font-bold uppercase tracking-widest">International</span>
                                        </div>
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-chevron-right transition-transform duration-500 opacity-0"
                                            aria-hidden="true"
                                        >
                                            <path d="m9 18 6-6-6-6"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div
                                    class="mt-12 p-8 rounded-[32px] bg-educave-900 text-white relative overflow-hidden group"
                                >
                                    <div
                                        class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-[1.5s]"
                                    ></div>
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="32"
                                        height="32"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-message-square text-educave-400 mb-6"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"
                                        ></path>
                                    </svg>
                                    <h4 class="text-xl font-serif font-bold mb-4">Dedicated Help.</h4>
                                    <p class="text-white/50 text-sm font-light leading-relaxed mb-8">
                                        Our consultants are available for personalized 1-on-1 calls to discuss your specific
                                        situation.
                                    </p>
                                    <button
                                        class="w-full py-4 bg-educave-800 text-white font-bold uppercase tracking-widest text-[10px] rounded-2xl hover:bg-white hover:text-educave-900 transition-all duration-500"
                                    >
                                        Request Consultation
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="w-full lg:w-2/3">
                            <div class="mb-12">
                            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-educave-600 mb-4 block"
                            >Active Category</span
                            >
                                <h2 class="text-5xl font-serif font-bold text-educave-900 mb-6">General</h2>
                                <div class="h-1 w-20 bg-educave-800 rounded-full"></div>
                            </div>
                            <div class="space-y-4">
                                <div
                                    class="overflow-hidden rounded-[32px] border transition-all duration-700 bg-white border-educave-800/20 shadow-xl"
                                >
                                    <button
                                        class="w-full text-left p-8 md:p-10 flex items-start justify-between gap-6 group"
                                    >
                                        <div class="flex gap-6">
                                        <span
                                            class="text-xl font-serif font-bold transition-all duration-500 text-educave-800 scale-125"
                                        >01</span
                                        ><span
                                                class="text-xl md:text-2xl font-serif font-bold leading-tight transition-colors duration-500 text-educave-900"
                                            >Where is Educave University located?</span
                                            >
                                        </div>
                                        <div
                                            class="shrink-0 w-12 h-12 rounded-full border border-educave-900/10 flex items-center justify-center transition-all duration-700 bg-educave-800 border-educave-800 text-white rotate-45"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="20"
                                                height="20"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-plus"
                                                aria-hidden="true"
                                            >
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                        </div>
                                    </button>
                                    <div
                                        class="transition-all duration-700 ease-[cubic-bezier(0.4,0,0.2,1)] max-h-[500px] opacity-100"
                                    >
                                        <div class="px-8 md:px-24 pb-12">
                                            <div class="h-px w-12 bg-educave-400 mb-8"></div>
                                            <p class="text-educave-900/60 text-lg font-light leading-relaxed">
                                                Our main campus is located in the heart of New York City, offering students
                                                unparalleled access to industry leaders, culture, and internships. We also
                                                have satellite hubs in London and Singapore.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="overflow-hidden rounded-[32px] border transition-all duration-700 bg-transparent border-educave-900/5 hover:border-educave-800/20"
                                >
                                    <button
                                        class="w-full text-left p-8 md:p-10 flex items-start justify-between gap-6 group"
                                    >
                                        <div class="flex gap-6">
                                        <span
                                            class="text-xl font-serif font-bold transition-all duration-500 text-educave-900/20"
                                        >02</span
                                        ><span
                                                class="text-xl md:text-2xl font-serif font-bold leading-tight transition-colors duration-500 text-educave-900/70 group-hover:text-educave-800"
                                            >Is the campus open to visitors?</span
                                            >
                                        </div>
                                        <div
                                            class="shrink-0 w-12 h-12 rounded-full border border-educave-900/10 flex items-center justify-center transition-all duration-700 bg-white text-educave-900 group-hover:bg-educave-50"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="20"
                                                height="20"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-plus"
                                                aria-hidden="true"
                                            >
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                        </div>
                                    </button>
                                    <div
                                        class="transition-all duration-700 ease-[cubic-bezier(0.4,0,0.2,1)] max-h-0 opacity-0"
                                    >
                                        <div class="px-8 md:px-24 pb-12">
                                            <div class="h-px w-12 bg-educave-400 mb-8"></div>
                                            <p class="text-educave-900/60 text-lg font-light leading-relaxed">
                                                Yes! We offer guided tours Monday through Saturday at 10 AM and 2 PM. You
                                                can also explore the grounds on your own during daylight hours. Please check
                                                in at the Welcome Center upon arrival.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="overflow-hidden rounded-[32px] border transition-all duration-700 bg-transparent border-educave-900/5 hover:border-educave-800/20"
                                >
                                    <button
                                        class="w-full text-left p-8 md:p-10 flex items-start justify-between gap-6 group"
                                    >
                                        <div class="flex gap-6">
                                        <span
                                            class="text-xl font-serif font-bold transition-all duration-500 text-educave-900/20"
                                        >03</span
                                        ><span
                                                class="text-xl md:text-2xl font-serif font-bold leading-tight transition-colors duration-500 text-educave-900/70 group-hover:text-educave-800"
                                            >What is the student-to-faculty ratio?</span
                                            >
                                        </div>
                                        <div
                                            class="shrink-0 w-12 h-12 rounded-full border border-educave-900/10 flex items-center justify-center transition-all duration-700 bg-white text-educave-900 group-hover:bg-educave-50"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="20"
                                                height="20"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-plus"
                                                aria-hidden="true"
                                            >
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                        </div>
                                    </button>
                                    <div
                                        class="transition-all duration-700 ease-[cubic-bezier(0.4,0,0.2,1)] max-h-0 opacity-0"
                                    >
                                        <div class="px-8 md:px-24 pb-12">
                                            <div class="h-px w-12 bg-educave-400 mb-8"></div>
                                            <p class="text-educave-900/60 text-lg font-light leading-relaxed">
                                                We maintain a 12:1 ratio to ensure personalized mentorship and small class
                                                sizes. This allows for deep engagement with professors and collaborative
                                                research opportunities.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="overflow-hidden rounded-[32px] border transition-all duration-700 bg-transparent border-educave-900/5 hover:border-educave-800/20"
                                >
                                    <button
                                        class="w-full text-left p-8 md:p-10 flex items-start justify-between gap-6 group"
                                    >
                                        <div class="flex gap-6">
                                        <span
                                            class="text-xl font-serif font-bold transition-all duration-500 text-educave-900/20"
                                        >04</span
                                        ><span
                                                class="text-xl md:text-2xl font-serif font-bold leading-tight transition-colors duration-500 text-educave-900/70 group-hover:text-educave-800"
                                            >Is there campus parking?</span
                                            >
                                        </div>
                                        <div
                                            class="shrink-0 w-12 h-12 rounded-full border border-educave-900/10 flex items-center justify-center transition-all duration-700 bg-white text-educave-900 group-hover:bg-educave-50"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="20"
                                                height="20"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-plus"
                                                aria-hidden="true"
                                            >
                                                <path d="M5 12h14"></path>
                                                <path d="M12 5v14"></path>
                                            </svg>
                                        </div>
                                    </button>
                                    <div
                                        class="transition-all duration-700 ease-[cubic-bezier(0.4,0,0.2,1)] max-h-0 opacity-0"
                                    >
                                        <div class="px-8 md:px-24 pb-12">
                                            <div class="h-px w-12 bg-educave-400 mb-8"></div>
                                            <p class="text-educave-900/60 text-lg font-light leading-relaxed">
                                                Limited parking is available for students and visitors in the South Garage.
                                                Permits are required for overnight parking, which can be purchased
                                                semesterly.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-20 grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div
                                    class="p-10 rounded-[40px] bg-white border border-educave-900/5 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group"
                                >
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-educave-100 flex items-center justify-center text-educave-800 mb-8 group-hover:bg-educave-800 group-hover:text-white transition-all"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-mail"
                                            aria-hidden="true"
                                        >
                                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        </svg>
                                    </div>
                                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-educave-600 mb-4">
                                        Direct Email
                                    </h4>
                                    <p class="text-2xl font-serif font-bold text-educave-900 mb-4">support@educave.edu</p>
                                    <p class="text-educave-900/40 text-xs font-light tracking-wide">
                                        Average response time: 4 hours.
                                    </p>
                                </div>
                                <div
                                    class="p-10 rounded-[40px] bg-white border border-educave-900/5 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group"
                                >
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-educave-100 flex items-center justify-center text-educave-800 mb-8 group-hover:bg-educave-800 group-hover:text-white transition-all"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-phone"
                                            aria-hidden="true"
                                        >
                                            <path
                                                d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"
                                            ></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-educave-600 mb-4">
                                        24/7 Helpline
                                    </h4>
                                    <p class="text-2xl font-serif font-bold text-educave-900 mb-4">+1 (888) EDU-CAVE</p>
                                    <p class="text-educave-900/40 text-xs font-light tracking-wide">
                                        Available for urgent campus queries.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-32 bg-educave-100/50">
                <div class="container mx-auto px-6 md:px-12 lg:px-24">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 text-center lg:text-left">
                        <div>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="32"
                                height="32"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-map-pin text-educave-800 mb-6 mx-auto lg:mx-0"
                                aria-hidden="true"
                            >
                                <path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"
                                ></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <h3 class="text-2xl font-serif font-bold text-educave-900 mb-4">Global Hub</h3>
                            <p class="text-educave-900/50 text-sm leading-relaxed font-light">
                                745 Fifth Avenue, <br />Suite 1200, <br />New York, NY 10151
                            </p>
                        </div>
                        <div class="lg:border-x border-educave-900/10 px-12">
                            <h3 class="text-2xl font-serif font-bold text-educave-900 mb-4">Admissions Hours</h3>
                            <p class="text-educave-900/50 text-sm leading-relaxed font-light">
                                Monday — Friday: <br />08:00 AM — 08:00 PM <br />(EST Timezone)
                            </p>
                        </div>
                        <div>
                            <h3 class="text-2xl font-serif font-bold text-educave-900 mb-4">Global Network</h3>
                            <div class="flex justify-center lg:justify-start gap-4 mt-6">
                                <div
                                    class="px-3 py-1 rounded-lg bg-educave-800/10 text-educave-800 text-[10px] font-black tracking-widest uppercase"
                                >
                                    LDN
                                </div>
                                <div
                                    class="px-3 py-1 rounded-lg bg-educave-800/10 text-educave-800 text-[10px] font-black tracking-widest uppercase"
                                >
                                    SGP
                                </div>
                                <div
                                    class="px-3 py-1 rounded-lg bg-educave-800/10 text-educave-800 text-[10px] font-black tracking-widest uppercase"
                                >
                                    DUB
                                </div>
                                <div
                                    class="px-3 py-1 rounded-lg bg-educave-800/10 text-educave-800 text-[10px] font-black tracking-widest uppercase"
                                >
                                    PAR
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
