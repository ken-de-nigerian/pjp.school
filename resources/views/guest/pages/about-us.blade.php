@extends('layouts.guest')

@section('content')
    <main id="main-content">
        <div class="transition-opacity duration-1000 opacity-100 font-sans text-educave-975 bg-educave-50 selection:bg-educave-200">
            <section class="relative pt-32 pb-24 overflow-hidden bg-educave-900">
                <div class="absolute inset-0 z-0">
                    <img alt="PJP School" class="w-full h-full object-cover opacity-10 scale-105" src="{{ asset('assets/img/right_1.jpeg') }}"/>
                    <div class="absolute inset-0 bg-gradient-to-b from-educave-900/80 via-educave-900/60 to-educave-900"></div>
                </div>
                <div class="container mx-auto px-6 md:px-12 lg:px-24 relative z-10">
                    <div class="max-w-4xl">
                        <div class="inline-flex items-center gap-3 mb-8 px-4 py-2 rounded-full border border-white/10 bg-white/5 backdrop-blur-md animate-in slide-in-from-top duration-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-400" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-white/70">Est. 2006 • Ihitte, Imo State</span>
                        </div>
                        <h1 class="text-6xl md:text-8xl font-serif font-bold text-white mb-8 leading-[0.9] tracking-tighter animate-in fade-in slide-in-from-bottom duration-1000">
                            Our <br/><span class="text-educave-400 italic">Story</span>
                        </h1>
                        <p class="text-xl md:text-2xl text-white/60 font-light leading-relaxed max-w-2xl animate-in fade-in slide-in-from-bottom duration-1000 delay-200">
                            {{ site_settings()->name }} was born from a bishop's prayer and a community's resolve that quality education must reach every child, regardless of background.
                        </p>
                    </div>
                </div>
            </section>

            <section class="bg-white text-educave-900 py-24 border-t border-black overflow-hidden relative">
                <div class="absolute top-10 left-0 w-full overflow-hidden whitespace-nowrap opacity-5 pointer-events-none">
                    <h2 class="text-[10rem] font-black uppercase tracking-tighter animate-marquee">Faith • Excellence • Character • Service • Truth •</h2>
                </div>
                <div class="container mx-auto px-4 md:px-8 lg:px-16 relative z-10 pt-20">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                        <div class="lg:col-span-4">
                            <span class="w-12 h-12 bg-educave-950 text-white flex items-center justify-center text-xl font-bold mb-6">01</span>
                            <h3 class="text-4xl font-serif font-bold leading-tight">We are not just <span class="italic">teaching</span> children we are forming them.</h3>
                        </div>
                        <div class="lg:col-span-8 columns-1 md:columns-2 gap-12 space-y-6">
                            <p class="text-lg leading-relaxed text-gray-800"><span class="text-5xl float-left mr-4 mt-[-10px] font-serif font-bold">T</span>he founding of {{ site_settings()->name }} was an act of God answering the prayerful longing of Bishop Chikwe to provide education to post-primary school children of middle and low income earners. Through the coordination of Rev. Fr. Sylvester Ihuoma, the school building was sponsored by Germans under the auspices of Ecumenical One World – Saint Nikolaus Wolback, under the care of Mrs. Ingrid Sieverding of Münster, Germany.</p>
                            <p class="text-lg leading-relaxed text-gray-600">Local support came through the Mbaise Educational Development Forum a voluntary organisation in the diocese charged with executing the project. On <strong>24th November 2006</strong>, the school opened her doors for the 2006/2007 academic year with Very Rev. Msgr. Paul Amakiri as her first principal. On that first day, 45 students were received 30 males and 15 females. The school closed that year with 72 students and 8 members of staff.</p>
                            <p class="text-lg leading-relaxed text-gray-600">Originally named <strong>Mater Ecclesiae Secondary School</strong>, the school was renamed {{ site_settings()->name }} in 2007 in honour of the beloved saint, whose vision of integral human formation became the school's guiding light. Today, her alumni are doing her proud all over the world.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-educave-950 padding-custom relative">
                <div class="container mx-auto px-4 md:px-8 lg:px-16">
                    <div class="flex justify-between items-end mb-16">
                        <h2 class="text-6xl md:text-8xl font-serif font-bold text-white">Our <span class="text-gray-600 italic">Code.</span></h2>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right text-white w-12 h-12 -rotate-45" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 grid-rows-2 gap-4 h-auto lg:h-[800px]">
                        <div class="md:col-span-2 lg:col-span-2 row-span-2 relative group overflow-hidden rounded-sm border border-white/10">
                            <img class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 scale-105 group-hover:scale-100" alt="Students at PJP" loading="lazy" decoding="async" src="{{ asset('assets/img/474624756_122217854930224692_4400465607954318102_n.jpg') }}"/>
                            <div class="absolute inset-0 bg-educave-950/40 group-hover:bg-transparent transition-colors"></div>
                            <div class="absolute bottom-8 left-8 max-w-md">
                                <h3 class="text-4xl font-serif font-bold text-white mb-4">Community</h3>
                                <p class="text-gray-300 text-lg opacity-0 group-hover:opacity-100 transition-opacity duration-500 transform translate-y-4 group-hover:translate-y-0 leading-relaxed">Every student here belongs. We build a family: one that challenges, supports and lifts each other toward something greater.</p>
                            </div>
                        </div>

                        <div class="bg-educave-900 p-8 flex flex-col justify-between group hover:bg-educave-800 transition-colors rounded-sm border border-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target text-white mb-4" aria-hidden="true"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-4">Excellence</h3>
                                <p class="text-white/70 text-sm leading-relaxed">Any student wishing to stand out must be ready to go the extra mile. All subjects are handled by qualified hands, and we hold every child to that standard.</p>
                            </div>
                        </div>

                        <div class="bg-white text-educave-900 p-8 flex flex-col justify-between group hover:bg-gray-200 transition-colors rounded-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart text-educave-900 mb-4" aria-hidden="true"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                            <div>
                                <h3 class="text-2xl font-bold mb-4">Service</h3>
                                <p class="text-gray-600 text-sm leading-relaxed">Leadership through service is at our core. We raise students who give back to their families, their communities and the Church.</p>
                            </div>
                        </div>

                        <div class="md:col-span-2 lg:col-span-2 border border-white/20 p-12 flex flex-col justify-center items-center text-center group hover:border-white transition-colors rounded-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield text-white mb-6 group-hover:scale-110 transition-transform duration-500" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                            <h3 class="text-4xl font-serif font-bold text-white mb-6">Integrity</h3>
                            <p class="text-gray-400 max-w-lg text-lg leading-relaxed">"You must be different to make the difference." Our students can stand shoulder to shoulder among peers trained in Catholic seminaries and convents.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white text-educave-900 padding-custom border-t border-black">
                <div class="container mx-auto px-4 md:px-8 lg:px-16">
                    <div class="max-w-4xl mx-auto">
                        <div class="flex items-center gap-4 mb-12">
                            <span class="w-12 h-px bg-educave-950"></span>
                            <span class="text-sm font-bold uppercase tracking-widest">Mission & Vision</span>
                        </div>
                        <h2 class="text-5xl md:text-7xl font-serif font-bold mb-16 leading-tight">Fostering lifelong learning in a safe environment steeped in <span class="italic text-educave-900">living faith.</span></h2>
                        <div class="prose prose-lg md:prose-xl prose-headings:font-serif prose-headings:font-bold text-gray-600 leading-relaxed space-y-8">
                            <p><span class="text-6xl float-left mr-4 mt-[-10px] font-serif font-bold text-educave-900">O</span>ur mission is to foster a lifelong learning experience in a safe environment steeped in living faith, an innovative spirit and an uncommon thirst for excellence. We offer our students a holistic education that harmonizes intellectual growth with spiritual, religious, emotional and social development.</p>
                            <p>Our vision is to be a leading Catholic institution offering holistic, innovative and world-class education that is accessible and affordable, confidently inspiring students for leadership in an ever-changing world.</p>
                            <blockquote class="border-l-4 border-educave-900 pl-8 py-4 my-12 italic text-2xl text-educave-900 font-serif">"We are committed to the education of the whole child: deepening faith in God, developing the intellect, forming the mind to seek truth and beauty, and the heart to be conscious, conscientious and compassionate."</blockquote>
                            <p>This is the philosophy handed to us by St. Pope John Paul II. It is the philosophy we carry into every classroom, every assembly, every correction and every celebration. We do not just pass children through this school. We pour into them.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-educave-950 padding-custom relative">
                <div class="container mx-auto px-4 md:px-8 lg:px-16">
                    <div class="flex items-center gap-4 mb-16">
                        <span class="w-12 h-px bg-educave-700"></span>
                        <span class="text-xs font-bold uppercase tracking-widest text-educave-300">What We Have Built</span>
                    </div>
                    <h2 class="text-5xl md:text-7xl font-serif font-bold text-white mb-16 max-w-2xl leading-tight">Built for <span class="italic text-gray-500">every kind</span> of learner.</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-px bg-white/10">
                        @php
                            $facilities = [
                                ['icon' => '<path d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2v-4M9 21H5a2 2 0 0 1-2-2v-4m0 0h18"/>', 'name' => 'Science Laboratories', 'desc' => 'Well-equipped science labs where students conduct experiments and bring theory to life.'],
                                ['icon' => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="M6 8h.01M10 8h.01M14 8h.01M18 8h.01M6 12h.01M10 12h.01M14 12h.01M18 12h.01M6 16h.01M10 16h.01M14 16h.01M18 16h.01"/>', 'name' => 'Computer Laboratory', 'desc' => 'A robust CBT-certified computer lab and an accredited centre for JAMB CBT examinations.'],
                                ['icon' => '<path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" x2="6" y1="1" y2="4"/><line x1="10" x2="10" y1="1" y2="4"/><line x1="14" x2="14" y1="1" y2="4"/>', 'name' => 'Steady Power & Water', 'desc' => 'Reliable electricity and water supply, ensuring a conducive environment every day.'],
                                ['icon' => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>', 'name' => 'Sports & Recreation', 'desc' => 'Football, volleyball, table tennis, badminton and more, keeping students active, healthy and team-spirited.'],
                                ['icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>', 'name' => 'Qualified Staff', 'desc' => 'Every subject handled by qualified, experienced and dedicated teachers committed to each student\'s growth.'],
                                ['icon' => '<path d="M18 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/><path d="m9 22 3-3 3 3"/><path d="M12 17V9"/><path d="m9 12 3-3 3 3"/>', 'name' => 'Strong Moral Formation', 'desc' => 'A strictly coordinated co-educational environment that trains students to stand out spiritually, morally and academically.'],
                            ]
                        @endphp
                        @foreach ($facilities as $f)
                            <div class="bg-educave-950 p-10 group hover:bg-educave-900 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-400 mb-6 group-hover:text-white transition-colors" aria-hidden="true">{!! $f['icon'] !!}</svg>
                                <h3 class="text-xl font-bold text-white mb-3">{{ $f['name'] }}</h3>
                                <p class="text-gray-500 text-sm leading-relaxed group-hover:text-gray-400 transition-colors">{{ $f['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="bg-white text-educave-900 padding-custom overflow-hidden">
                <div class="container mx-auto px-4 md:px-8 lg:px-16">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                        <div class="lg:col-span-4 sticky top-32 h-fit">
                            <h2 class="text-6xl font-serif font-bold mb-8">Time<br>line.</h2>
                            <p class="text-gray-500 text-lg">Tracing the journey of PJP from a bishop's prayer to a thriving Catholic institution.</p>
                        </div>
                        <div class="lg:col-span-8 border-l border-black/10 pl-20 md:pl-16 space-y-24 pt-32 lg:pt-0">
                            @php
                                $timeline = [
                                    ['year' => '2006', 'title' => 'The Foundation',        'desc' => 'School approval granted in April 2006. On 24th November 2006, PJP opened her doors with Very Rev. Msgr. Paul Amakiri as first principal, 45 students and 8 members of staff, through the vision of Bishop Chikwe and with support from German donors and the Mbaise Educational Development Forum.'],
                                    ['year' => '2007', 'title' => 'The Renaming',          'desc' => 'The school receives full approval and is renamed ' . site_settings()->name . ', Rev. Fr. Francis Amaliri serves as second principal. The school receives its second set of students in September 2007.'],
                                    ['year' => '2008', 'title' => 'JSSCE Approval',        'desc' => 'The school receives approval for the Junior Secondary School Certificate Examination (JSSCE) in November 2008. Rev. Fr. Dr. Innocent Olekamma forms the first Parents Teachers Association.'],
                                    ['year' => '2011', 'title' => 'Senior Status & WAEC',  'desc' => 'Approved to upgrade to Senior Secondary status in March 2011. WAEC approval follows in June, NECO in October, marking PJP as a fully accredited examination centre.'],
                                    ['year' => '2019', 'title' => 'New Leadership',        'desc' => 'Rev. Fr. Augustine C. Onuoha takes over as principal, succeeding the longest-serving principal Rev. Fr. Timothy Okeahialam (2008-2019), under whose tenure the school witnessed phenomenal growth. Fr. Onuoha served until August 2025.'],
                                    ['year' => '2023', 'title' => 'Into the Digital Age',  'desc' => 'PJP launches its digital portal, bringing result checking, admissions and school news online. The computer laboratory becomes an accredited JAMB CBT centre, taking the mission of accessible education further than ever before.'],
                                ]
                            @endphp
                            @foreach ($timeline as $item)
                                <div class="group relative">
                                    <span class="absolute -left-[5.5rem] md:-left-[4.5rem] top-2 w-4 h-4 rounded-full bg-educave-950 border-4 border-white group-hover:scale-150 transition-transform duration-300 z-10"></span>
                                    <span class="text-6xl md:text-8xl font-black text-gray-100 absolute -top-8 -left-12 md:-top-12 md:-left-4 -z-10 group-hover:text-educave-50/50 transition-colors duration-500">{{ $item['year'] }}</span>
                                    <div class="relative z-10">
                                        <span class="text-educave-900 font-bold tracking-widest text-sm uppercase mb-2 block">{{ $item['year'] }}</span>
                                        <h3 class="text-4xl font-serif font-bold mb-4 group-hover:translate-x-4 transition-transform duration-500">{{ $item['title'] }}</h3>
                                        <p class="text-gray-600 text-xl leading-relaxed max-w-lg">{{ $item['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-educave-950 padding-custom border-t border-white/10">
                <div class="container mx-auto px-4 md:px-8 lg:px-16">
                    <div class="text-center mb-20">
                        <h2 class="text-5xl md:text-7xl font-serif font-bold text-white mb-6">Our Leadership</h2>
                        <div class="w-24 h-1 bg-educave-500 mx-auto"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-4xl mx-auto">
                        @php
                            $leaders = [
                                ['name' => 'Rev. Fr. Ephraim U. Ibekwe', 'role' => 'School Principal',          'img' => asset('storage/news/default.png')],
                                ['name' => 'To be updated',                 'role' => 'Vice Principal',  'img' => asset('storage/news/default.png')],
                                ['name' => 'To be updated',                 'role' => 'Chaplain',      'img' => asset('storage/news/default.png')],
                                ['name' => 'To be updated',                 'role' => 'Bursar',      'img' => asset('storage/news/default.png')],
                            ]
                        @endphp
                        @foreach ($leaders as $leader)
                            <div class="group relative">
                                <div class="relative aspect-[3/4] mb-6 transition-transform duration-500 group-hover:-translate-y-2">
                                    <div class="absolute inset-0 border border-white/20 translate-x-2 translate-y-2" style="clip-path: polygon(20% 0%, 100% 0px, 100% 85%, 80% 100%, 0px 100%, 0% 15%);"></div>
                                    <div class="absolute inset-0 overflow-hidden bg-educave-900" style="clip-path: polygon(20% 0%, 100% 0px, 100% 85%, 80% 100%, 0px 100%, 0% 15%);">
                                        <img alt="{{ $leader['name'] }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700" loading="lazy" decoding="async" src="{{ $leader['img'] }}"/>
                                        <div class="absolute inset-0 bg-educave-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500 mix-blend-multiply"></div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-xl font-bold text-white group-hover:text-educave-500 transition-colors">{{ $leader['name'] }}</h3>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">{{ $leader['role'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="max-w-4xl mx-auto border-t border-white/10" style="margin-top: 15px;">
                        <h3 class="text-2xl font-serif font-bold text-white mb-10 text-center" style="margin-top: 15px;">Past Principals</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                $pastPrincipals = [
                                    ['name' => 'Very Rev. Msgr. Paul Amakiri',    'tenure' => 'November 2006 – March 2007'],
                                    ['name' => 'Rev. Fr. Francis Amaliri',         'tenure' => 'March 2007 – October 2007'],
                                    ['name' => 'Rev. Fr. Dr. Innocent Olekamma',  'tenure' => 'October 2007 – March 2008'],
                                    ['name' => 'Rev. Fr. Timothy Okeahialam',     'tenure' => 'March 2008 – 2019'],
                                    ['name' => 'Rev. Fr. Augustine C. Onuoha',      'tenure' => 'January 2019 – August 2025'],
                                ]
                            @endphp
                            @foreach ($pastPrincipals as $p)
                                <div class="flex gap-4 p-6 border border-white/10 rounded-sm hover:border-white/30 transition-colors">
                                    <div class="w-10 h-10 bg-educave-900 rounded-full flex items-center justify-center shrink-0 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-400" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white text-sm">{{ $p['name'] }}</p>
                                        <p class="text-educave-400 text-xs font-bold uppercase tracking-widest mt-0.5">{{ $p['tenure'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section class="relative padding-custom flex items-center justify-center overflow-hidden bg-white text-educave-900">
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-gray-400 to-transparent"></div>
                </div>
                <div class="container mx-auto px-4 relative z-10 text-center">
                    <p class="text-sm font-bold uppercase tracking-[0.3em] mb-6 text-educave-900">Admissions Open</p>
                    <h2 class="text-7xl md:text-9xl font-black tracking-tighter mb-10 leading-none hover:scale-105 transition-transform duration-700 cursor-default">GIVE YOUR <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-educave-900 to-black">CHILD THE BEST</span></h2>
                    <a href="{{ route('admin_process') }}" class="group relative inline-block rounded-xl px-12 py-6 bg-educave-950 text-white font-bold uppercase tracking-widest overflow-hidden">
                        <span class="relative z-10 group-hover:text-educave-900 transition-colors duration-300">Apply Now</span>
                        <div class="absolute inset-0 bg-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out"></div>
                    </a>
                </div>
            </section>
        </div>
    </main>
@endsection
