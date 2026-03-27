@php use Illuminate\Support\Carbon;use Illuminate\Support\Str; @endphp
@extends('layouts.guest', ['title' => 'guest.pages.home'])

@section('content')
    <main id="main-content">
        <div class="animate-in fade-in duration-700 font-sans selection:bg-educave-200 selection:text-educave-900">
            <section
                class="relative w-full min-h-[85vh] flex flex-col items-center justify-center bg-white overflow-hidden pt-20">
                <div class="absolute top-0 left-0 w-full h-1/2 bg-educave-100/50 -skew-y-3 origin-top-left z-0"></div>
                <div class="container mx-auto px-4 relative z-10 text-center">
                    <div class="inline-flex items-center gap-2 mb-6 scroll-fade-up visible">
                        <span class="w-8 h-px bg-pjp-gold-500" aria-hidden="true"></span>
                        <span class="text-xs font-bold uppercase tracking-[0.2em] text-educave-800">Est. 2006 • Imo State</span>
                        <span class="w-8 h-px bg-pjp-torch/90" aria-hidden="true"></span>
                    </div>

                    <h1 class="text-6xl md:text-8xl lg:text-9xl font-serif font-bold text-educave-900 tracking-tight leading-[0.9] mb-8 scroll-fade-up delay-100 visible">
                        PJP MODEL <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-educave-800 to-educave-500">SEC SCH.</span>
                    </h1>

                    <p class="max-w-2xl mx-auto text-xl text-neutral-500 font-serif italic mb-12 scroll-fade-up delay-200 visible">
                        "From this soil, greatness rises. We are shaping sharp minds, strong character, and world-class
                        leaders one child at a time."
                    </p>

                    <div class="relative w-full max-w-5xl mx-auto h-[400px] md:h-[500px] mt-8">
                        <div
                            class="absolute left-1/2 top-0 -translate-x-1/2 w-full md:w-2/3 h-full z-10 shadow-2xl overflow-hidden rounded-t-full border-t-8 border-educave-900">
                            <img class="w-full h-full object-cover" alt="University Main" loading="lazy"
                                 decoding="async" src="{{ asset('assets/img/right_1.jpeg') }}"/>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-educave-900 text-white padding-custom relative overflow-hidden">
                <div
                    class="absolute inset-0 opacity-5 bg-[image:radial-gradient(theme(colors.white)_1px,transparent_1px)] [background-size:30px_30px]"></div>
                <div class="container mx-auto px-4 md:px-8 lg:px-16 relative z-10">
                    <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
                        <div class="w-full lg:w-1/2 order-2 lg:order-1 scroll-fade-up visible">
                            <div class="mb-4"><span class="guest-crest-badge">The Visionary</span></div>
                            <h2 class="text-4xl md:text-5xl font-serif font-bold mb-8 leading-tight">
                                "Train up a child <br/>
                                in the way he <span class="text-educave-400 italic">should go.</span>"
                            </h2>
                            <p class="text-white/75 leading-relaxed mb-8 text-lg">
                                We are a co-educational Catholic school rooted in faith, discipline, and a deep
                                commitment to raising children of excellence.
                                Here, we do not just teach — we form. We shape minds, build character, and send forth
                                young men and women who are ready to make their mark, not just in Nigeria, but in the
                                world.
                                Our conviction is simple: <span class="text-white font-semibold">"You must be different to make the difference."</span>
                            </p>
                            <div class="flex items-center gap-4 mt-8">
                                <x-site-logo-mark
                                    class="w-16 h-16 rounded-full border-2 border-educave-500 p-1 object-cover"
                                    width="64"
                                    height="64"
                                    alt="{{ __('School Principal') }}"
                                />
                                <div>
                                    <h4 class="text-xl font-serif font-bold text-white">Fr. Ephraim U. Ibekwe</h4>
                                    <p class="text-xs text-white/60 uppercase tracking-widest">School Principal</p>
                                </div>
                                <div class="ml-auto">
                                    <svg width="100" height="40" viewBox="0 0 120 40"
                                         class="stroke-white opacity-50 fill-none" stroke-width="2">
                                        <path d="M10,20 Q30,5 50,20 T90,20 T110,20"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="w-full lg:w-1/2 order-1 lg:order-2 relative">
                            <div
                                class="relative z-10 border-[12px] border-white/5 shadow-2xl scroll-reveal-image visible">
                                <img class="w-full h-auto grayscale hover:grayscale-0 transition-all duration-700"
                                     alt="University History" loading="lazy" decoding="async"
                                     src="{{ asset('assets/img/about-2.jpg') }}"/>
                            </div>
                            <div
                                class="absolute -bottom-10 -left-10 w-40 h-40 bg-educave-600 z-0 hidden md:block scroll-scale-in delay-200 visible"></div>
                            <div
                                class="absolute -top-10 -right-10 w-40 h-40 border-2 border-educave-600 z-20 hidden md:block scroll-scale-in delay-300 visible"></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="padding-custom bg-neutral-950 text-white relative overflow-hidden">
                <div
                    class="absolute inset-0 opacity-[0.03] bg-[linear-gradient(rgba(255,255,255,0.1)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.1)_1px,transparent_1px)] [background-size:60px_60px]"></div>
                <div class="container mx-auto px-4 md:px-8 lg:px-16 relative z-10">
                    <div class="flex flex-col lg:flex-row items-center gap-20">
                        <div class="w-full lg:w-1/2 relative z-20">
                            <div class="inline-flex items-center gap-3 mb-8 scroll-fade-up visible">
                                <div
                                    class="px-3 py-1 border border-white/20 rounded-full bg-white/5 backdrop-blur-sm text-[10px] font-bold uppercase tracking-widest text-pjp-gold-bright flex items-center gap-2">
                                    <span
                                        class="w-2 h-2 bg-pjp-yellow rounded-full animate-pulse shadow-[0_0_0_3px_rgba(242,230,49,0.25)]"></span>Our
                                    Standard
                                </div>
                            </div>

                            <h2 class="text-5xl md:text-7xl font-serif font-bold leading-tight mb-8 scroll-fade-up delay-100">
                                Raising <br/>
                                <span
                                    class="relative inline-block text-transparent bg-clip-text bg-gradient-to-r from-white via-white/95 to-zinc-200 italic pr-4">Champions
                                    <svg class="absolute bottom-2 left-0 w-full h-3 text-pjp-gold-500 -z-10"
                                         viewBox="0 0 100 10" preserveAspectRatio="none">
                                        <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="3" fill="none"
                                              class="opacity-60"/>
                                    </svg>
                                </span>
                            </h2>

                            <p class="text-lg text-white/80 leading-relaxed max-w-lg mb-12 font-sans scroll-fade-up delay-200 border-l-4 border-pjp-torch/70 pl-6">
                                We do not just pass children through classrooms — we pour into them. Every lesson, every
                                value, every correction is building something that will last.
                            </p>

                            <div class="grid grid-cols-2 gap-8 scroll-fade-up delay-300">
                                <div>
                                    <div class="text-4xl font-serif font-bold text-white mb-1">Est. 2006</div>
                                    <div class="text-xs text-white/60 uppercase tracking-widest">Years of Excellence
                                    </div>
                                </div>

                                <div>
                                    <div class="text-4xl font-serif font-bold text-white mb-1">100%</div>
                                    <div class="text-xs text-white/60 uppercase tracking-widest">Commitment to Every
                                        Child
                                    </div>
                                </div>

                                <div>
                                    <div class="text-4xl font-serif font-bold text-white mb-1">Faith</div>
                                    <div class="text-xs text-white/60 uppercase tracking-widest">Catholic Foundation
                                    </div>
                                </div>

                                <div>
                                    <div class="text-4xl font-serif font-bold text-white mb-1">Heart</div>
                                    <div class="text-xs text-white/60 uppercase tracking-widest">Behind Every Graduate
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full lg:w-1/2 relative h-[600px] hidden lg:block">
                            <img
                                class="absolute inset-0 w-full h-full object-cover grayscale transition-all duration-1000 scale-105 hover:scale-100"
                                alt="Vision" loading="lazy" decoding="async"
                                src="{{ asset('assets/img/474624756_122217854930224692_4400465607954318102_n.jpg') }}"/>
                            <div class="absolute inset-0 bg-black/10"></div>
                            <div class="absolute bottom-12 left-12 right-12">
                                <p class="text-white text-3xl font-serif italic leading-tight">"Where intellect meets
                                    instinct."</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="padding-custom bg-white text-educave-900 relative overflow-hidden">
                <div class="container mx-auto px-4 relative z-10">
                    <div class="flex flex-col items-center text-center mb-24 scroll-fade-up">
                        <span
                            class="guest-crest-underline text-educave-800 font-bold tracking-widest text-xs uppercase mb-4">Our Facilities</span>
                        <h2 class="text-6xl md:text-8xl font-serif font-bold tracking-tight mb-6">
                            Built to <span class="italic text-neutral-400">Inspire</span>
                        </h2>
                        <p class="max-w-2xl text-lg text-neutral-500 leading-relaxed font-serif">
                            Every corner of this school is designed with purpose — to create an environment where
                            learning thrives, faith is nurtured, and curiosity is given room to grow.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-20">
                        @php
                            $facilities = [
                                [
                                    'tag'     => 'Knowledge',
                                    'tagPos'  => 'top-4 left-4',
                                    'img'     => asset('assets/img/about-1.jpg'),
                                    'alt'     => 'Library',
                                    'mt'      => 'md:mt-20',
                                    'order'   => '',
                                    'name'    => 'The Library',
                                    'role'    => 'The Quiet Room',
                                    'desc'    => 'A well-stocked space where students read, research, and discover — building the habit of learning beyond the classroom.',
                                ],
                                [
                                    'tag'     => 'Discovery',
                                    'tagPos'  => 'bottom-4 right-4',
                                    'img'     => asset('assets/img/gallery-5.jpeg'),
                                    'alt'     => 'Science Lab',
                                    'mt'      => '',
                                    'order'   => 'reverse',
                                    'name'    => 'The Laboratories',
                                    'role'    => 'The Science Block',
                                    'desc'    => 'Hands-on learning in a fully equipped lab environment — where theory meets practice and young scientists are born.',
                                ],
                                [
                                    'tag'     => 'Faith',
                                    'tagPos'  => 'top-4 right-4',
                                    'img'     => asset('assets/img/gallery-7.jpeg'),
                                    'alt'     => 'Chapel',
                                    'mt'      => 'md:mt-32',
                                    'order'   => '',
                                    'name'    => 'The Chapel',
                                    'role'    => 'The Sacred Space',
                                    'desc'    => 'At the heart of everything we do. A place of prayer, reflection, and worship — grounding our community in faith every day.',
                                ],
                            ]
                        @endphp

                        @foreach ($facilities as $f)
                            <div class="group flex flex-col gap-6 mt-0 {{ $f['mt'] }} scroll-fade-up delay-100">
                                @if ($f['order'] === 'reverse')
                                    <div
                                        class="order-2 md:order-1 border-l-2 border-neutral-100 pl-6 group-hover:border-pjp-gold-500 transition-colors duration-500 mb-6 md:mb-0">
                                        <h3 class="text-3xl font-serif font-bold mb-1">{{ $f['name'] }}</h3>
                                        <p class="text-xs font-bold text-educave-500 uppercase tracking-widest mb-3">{{ $f['role'] }}</p>
                                        <p class="text-sm text-gray-500 leading-relaxed mb-4">{{ $f['desc'] }}</p>
                                    </div>

                                    <div class="order-1 md:order-2 relative overflow-hidden aspect-[3/4] rounded-sm">
                                        <img
                                            class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 grayscale group-hover:grayscale-0"
                                            alt="{{ $f['alt'] }}" loading="lazy" decoding="async"
                                            src="{{ $f['img'] }}"/>
                                        <div
                                            class="absolute {{ $f['tagPos'] }} bg-white/90 backdrop-blur-sm px-4 py-2 text-xs font-bold uppercase tracking-widest">{{ $f['tag'] }}</div>
                                    </div>
                                @else
                                    <div class="relative overflow-hidden aspect-[3/4] rounded-sm">
                                        <img
                                            class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 grayscale group-hover:grayscale-0"
                                            alt="{{ $f['alt'] }}" loading="lazy" decoding="async"
                                            src="{{ $f['img'] }}"/>
                                        <div
                                            class="absolute {{ $f['tagPos'] }} bg-white/90 backdrop-blur-sm px-4 py-2 text-xs font-bold uppercase tracking-widest">{{ $f['tag'] }}</div>
                                    </div>
                                    <div
                                        class="border-l-2 border-neutral-100 pl-6 group-hover:border-pjp-gold-500 transition-colors duration-500">
                                        <h3 class="text-3xl font-serif font-bold mb-1">{{ $f['name'] }}</h3>
                                        <p class="text-xs font-bold text-educave-500 uppercase tracking-widest mb-3">{{ $f['role'] }}</p>
                                        <p class="text-sm text-gray-500 leading-relaxed mb-4">{{ $f['desc'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="w-full bg-white">
                <div class="grid grid-cols-2 md:grid-cols-4 h-[600px] md:h-[500px]">
                    <div class="col-span-2 md:col-span-2 relative group overflow-hidden scroll-scale-in">
                        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             alt="School campus grounds" loading="lazy" decoding="async"
                             src="{{ asset('assets/img/gallery-9.jpeg') }}"/>
                        <div
                            class="absolute inset-0 bg-educave-900/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="text-white font-serif text-3xl">School Grounds</span>
                        </div>
                    </div>

                    <div
                        class="relative group overflow-hidden bg-educave-800 flex items-center justify-center p-8 text-center scroll-scale-in delay-100 cursor-pointer">
                        <div>
                            <h3 class="text-white font-serif text-2xl mb-2">Request a Tour</h3>
                            <p class="text-educave-200 text-sm">See what life looks like here</p>
                            <a href="mailto:{{ config('school.school_email') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="lucide lucide-arrow-right text-white mx-auto mt-4 group-hover:translate-x-2 transition-transform"
                                     aria-hidden="true">
                                    <path d="M5 12h14"/>
                                    <path d="m12 5 7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="relative group overflow-hidden scroll-scale-in delay-200">
                        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             alt="School library" loading="lazy" decoding="async"
                             src="{{ asset('assets/img/gallery-6.jpeg') }}"/>
                        <div
                            class="absolute inset-0 bg-educave-900/20 group-hover:bg-educave-900/40 transition-colors"></div>
                    </div>
                </div>
            </section>

            <section class="bg-white">
                <div class="container mx-auto px-4 md:px-8 lg:px-16 pt-24 pb-12">
                    <div class="flex flex-col md:flex-row justify-between items-end scroll-fade-up mb-20">
                        <div class="max-w-2xl">
                            <span class="text-xs font-bold tracking-widest text-educave-800 uppercase mb-2 block">Academics</span>
                            <h2 class="text-4xl md:text-5xl font-serif font-bold text-neutral-900">
                                Our Arms of <span class="text-educave-800 border-b-4 border-pjp-gold-500">Study</span>
                            </h2>
                        </div>
                        <p class="hidden md:block text-gray-500 max-w-xs text-right text-sm">A holistic education that
                            harmonizes intellectual growth with spiritual, emotional and social development.</p>
                    </div>

                    <div
                        class="w-full h-[600px] flex flex-col md:flex-row overflow-hidden border-t border-b border-neutral-100 scroll-fade-up delay-200">
                        @php
                            $schools = [
                                [
                                    'num'   => '01',
                                    'title' => 'Junior Secondary',
                                    'desc'  => 'From JSS1 to JSS3, we lay the foundation — building discipline, curiosity and character in every child before they choose their path.',
                                    'btn'   => 'Learn More',
                                    'img'   => asset('assets/img/467947720_122201826182224692_7298821440445173774_n.jpg'),
                                ],
                                [
                                    'num'   => '02',
                                    'title' => 'Science',
                                    'desc'  => 'Mathematics, Physics, Chemistry, Biology and Further Maths — for students with a curious mind and a hunger to understand how things work.',
                                    'btn'   => 'Learn More',
                                    'img'   => asset('assets/img/gallery-2.jpeg'),
                                ],
                                [
                                    'num'   => '03',
                                    'title' => 'Arts',
                                    'desc'  => 'Literature, History, Government, CRS and Fine Art — for the expressive, the eloquent, and those called to shape culture and community.',
                                    'btn'   => 'Learn More',
                                    'img'   => asset('assets/img/480254694_122223390746224692_863623216330789397_n.jpg'),
                                ],
                                [
                                    'num'   => '04',
                                    'title' => 'Commercial',
                                    'desc'  => 'Commerce, Accounting, Economics and Office Practice — forming the next generation of business minds, rooted in integrity and enterprise.',
                                    'btn'   => 'Learn More',
                                    'img'   => asset('assets/img/about-7.jpeg')
                                ],
                            ]
                        @endphp
                        @foreach ($schools as $school)
                            <div
                                class="group relative flex-1 hover:flex-[3] transition-all duration-700 ease-in-out border-r border-neutral-100 overflow-hidden cursor-pointer">
                                <div class="absolute inset-0 w-full h-full">
                                    <img alt="{{ $school['title'] }}"
                                         class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 grayscale group-hover:grayscale-0"
                                         loading="lazy" decoding="async" src="{{ $school['img'] }}"/>
                                    <div
                                        class="absolute inset-0 bg-educave-900/60 group-hover:bg-educave-900/80 transition-colors"></div>
                                </div>
                                <div class="relative z-10 h-full p-8 flex flex-col justify-end">
                                    <div
                                        class="absolute inset-0 flex items-center justify-center group-hover:opacity-0 transition-opacity duration-300 pointer-events-none md:pointer-events-auto">
                                        <h3 class="hidden md:block text-2xl font-serif font-bold text-white opacity-80 rotate-[-90deg] whitespace-nowrap tracking-wider">{{ $school['title'] }}</h3>
                                    </div>
                                    <div
                                        class="opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 transform translate-y-4 group-hover:translate-y-0">
                                        <span
                                            class="text-4xl font-serif font-bold text-white/20 mb-2 block">{{ $school['num'] }}</span>
                                        <h3 class="text-3xl font-serif font-bold text-white mb-4 leading-none">{{ $school['title'] }}</h3>
                                        <p class="text-neutral-300 mb-6 max-w-sm leading-relaxed text-sm md:text-base hidden md:block">{{ $school['desc'] }}</p>
                                        <a href="{{ route('academic_overview') }}"
                                           class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-educave-400 hover:text-white transition-colors">
                                            {{ $school['btn'] }}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="lucide lucide-arrow-right" aria-hidden="true">
                                                <path d="M5 12h14"/>
                                                <path d="m12 5 7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="bg-educave-900 py-12 border-t border-educave-800">
                <div class="container mx-auto px-4 overflow-hidden">
                    <p class="text-center text-educave-200/50 text-xs font-bold uppercase tracking-widest mb-8">
                        Recognised & Affiliated With</p>
                    <div class="mask-linear-gradient w-full overflow-hidden">
                        <div class="flex whitespace-nowrap animate-marquee">
                            @foreach ([
                                'WAEC', 'NECO', 'Federal Ministry of Education', 'Imo State SUBEB',
                                'Catholic Diocese of Ahiara', 'JAMB', 'Ezinihitte Mbaise LGA',
                                'WAEC', 'NECO', 'Federal Ministry of Education', 'Imo State SUBEB',
                                'Catholic Diocese of Ahiara', 'JAMB', 'Ezinihitte Mbaise LGA',
                            ] as $partner)
                                <div
                                    class="mx-12 text-white/40 font-serif text-3xl font-bold hover:text-white transition-colors cursor-pointer">{{ $partner }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section class="padding-custom bg-white">
                <div class="container mx-auto px-4 md:px-8 lg:px-16">
                    <div class="text-center mb-16 scroll-fade-up">
                        <span
                            class="text-xs font-bold tracking-widest text-educave-800 uppercase border-b border-pjp-gold-500 pb-1">Latest News</span>
                        <h2 class="text-4xl md:text-5xl font-serif font-bold text-educave-900 mt-4">
                            School <span class="italic text-gray-400">Chronicles</span>
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        @php
                            $featuredPublished = $featuredNews
                                ? ($featuredNews->created_at ?? $featuredNews->date_added)
                                : null;
                            $featuredImage = $featuredNews && $featuredNews->cover_image
                                ? asset('storage/news/'.$featuredNews->cover_image)
                                : asset('storage/news/default.png');
                        @endphp
                        <div class="group cursor-pointer scroll-fade-up delay-100">
                            @if ($featuredNews)
                                <div class="overflow-hidden rounded-xl mb-6">
                                    <img
                                        class="w-full h-[400px] object-cover transition-transform duration-700 group-hover:scale-110"
                                        alt="{{ $featuredNews->title }}" loading="lazy" decoding="async"
                                        src="{{ $featuredImage }}"
                                        onerror="this.src='{{ asset('storage/news/default.png') }}'; this.onerror=null;"/>
                                </div>
                                <div
                                    class="flex items-center gap-4 text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">
                                    <span class="text-educave-800">{{ $featuredNews->category ?: __('News') }}</span>
                                    <span>•</span>
                                    <span>{{ $featuredPublished ? Carbon::parse($featuredPublished)->format('M d, Y') : '' }}</span>
                                </div>
                                <h3 class="text-3xl font-serif font-bold text-educave-900 mb-3 group-hover:text-educave-800 transition-colors">
                                    <a href="{{ route('news.show', $featuredNews) }}">{{ $featuredNews->title }}</a>
                                </h3>
                                <p class="text-gray-500 leading-relaxed">{{ Str::limit(strip_tags((string) ($featuredNews->content ?? '')), 220) }}</p>
                            @else
                                <div
                                    class="overflow-hidden rounded-xl mb-6 bg-gray-100 flex items-center justify-center h-[400px]">
                                    <span class="text-gray-400 text-sm">{{ __('No news published yet.') }}</span>
                                </div>
                                <p class="text-gray-500 leading-relaxed">{{ __('Check back soon for updates from the school.') }}</p>
                            @endif
                        </div>

                        <div class="flex flex-col gap-8 justify-center">
                            @forelse ($moreNews as $item)
                                @php
                                    $itemDate = $item->created_at ?? $item->date_added;
                                    $d = $itemDate ? Carbon::parse($itemDate) : null;
                                @endphp
                                <div
                                    class="flex gap-6 group cursor-pointer border-b border-gray-100 pb-8 last:border-0 scroll-fade-up"
                                    style="transition-delay: {{ 100 + $loop->iteration * 100 }}ms">
                                    <div
                                        class="shrink-0 w-20 h-20 bg-gray-100 rounded-lg flex flex-col items-center justify-center text-center">
                                        <span
                                            class="text-2xl font-bold text-educave-800 font-serif">{{ $d ? $d->format('d') : '—' }}</span>
                                        <span
                                            class="text-xs uppercase text-gray-500">{{ $d ? $d->format('M') : '' }}</span>
                                    </div>
                                    <div>
                                        <span
                                            class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1 block group-hover:text-educave-800">{{ $item->category ?: __('News') }}</span>
                                        <h4 class="text-xl font-serif font-bold text-educave-900 group-hover:text-educave-800 transition-colors">
                                            <a href="{{ route('news.show', $item) }}">{{ $item->title }}</a>
                                        </h4>
                                    </div>
                                </div>
                            @empty
                                @if ($featuredNews)
                                    <p class="text-gray-500 text-sm">{{ __('No additional articles yet.') }}</p>
                                @else
                                    <p class="text-gray-500 text-sm">{{ __('No announcements at the moment.') }}</p>
                                @endif
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
