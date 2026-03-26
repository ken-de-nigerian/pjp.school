<footer class="bg-educave-950 text-white relative overflow-hidden font-sans border-t border-white/5">
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute -top-[20%] -right-[10%] w-[600px] h-[600px] rounded-full bg-educave-800/10 blur-[120px]"></div>
        <div class="absolute bottom-[10%] left-[-10%] w-[500px] h-[500px] rounded-full bg-blue-900/5 blur-[100px]"></div>
    </div>

    <div class="relative z-10 border-b border-white/10">
        <div class="container mx-auto px-4 md:px-8 lg:px-16 py-16 lg:py-20">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-10">
                <div class="max-w-2xl text-center lg:text-left">
                    <h2 class="text-3xl md:text-5xl font-serif font-bold mb-4">
                        Ready to shape the <span class="text-educave-800 italic">future?</span>
                    </h2>
                    <p class="text-gray-400 text-lg">Subscribe to our newsletter for the latest research, campus news, and events.</p>
                </div>
                <div class="w-full lg:w-auto flex-shrink-0">
                    <form class="flex flex-col sm:flex-row gap-3 w-full lg:min-w-[450px]">
                        <input placeholder="Email Address" class="w-full flex-grow rounded-xl bg-white/5 border border-white/10 px-5 py-4 outline-none focus:border-educave-800 text-white placeholder-gray-500 text-sm transition-all" type="email"/>
                        <button class="rounded-xl bg-white text-black px-8 py-4 font-bold uppercase tracking-widest text-xs hover:bg-educave-800 hover:text-white transition-all flex items-center justify-center gap-2 group whitespace-nowrap">
                            Subscribe
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right group-hover:translate-x-1 transition-transform" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 md:px-8 lg:px-16 py-20 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8">
            <div class="lg:col-span-5 pr-8">
                <div class="mb-8 min-w-0">
                    <x-site-logo
                        href="{{ route('home') }}"
                        variant="footer"
                        loading="lazy"
                        :aria-label="__('School home')"
                    />
                </div>
                <p class="text-gray-400 leading-relaxed mb-8 max-w-md">A co-educational Catholic secondary school in Imo State — forming students in faith, discipline, and academic excellence from JSS1 to SS3.</p>
                <div class="flex gap-3">
                    @php
                        $socials = [
                            ['href' => 'https://facebook.com/educave',  'label' => 'Facebook',  'icon' => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>'],
                            ['href' => 'https://twitter.com/educave',   'label' => 'Twitter',   'icon' => '<path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/>'],
                            ['href' => 'https://instagram.com/educave', 'label' => 'Instagram', 'icon' => '<rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>'],
                            ['href' => 'https://youtube.com/educave',   'label' => 'YouTube',   'icon' => '<path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/>'],
                            ['href' => 'https://educave.edu',           'label' => 'Website',   'icon' => '<circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/>'],
                        ]
                    @endphp
                    @foreach ($socials as $s)
                        <a href="{{ $s['href'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $s['label'] }}" class="w-10 h-10 border border-white/10 flex items-center justify-center text-gray-500 hover:bg-white hover:text-black hover:border-white transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $s['icon'] !!}</svg>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-2">
                <h4 class="font-bold text-white mb-6 uppercase tracking-widest text-xs border-b border-white/10 pb-4 inline-block">Academics</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    @foreach ([
                        ['route' => 'academic_overview', 'label' => 'Academic overview'],
                        ['route' => 'academic_overview', 'label' => 'Academic curriculum'],
                        ['route' => 'news', 'label' => 'News'],
                        ['route' => 'faqs', 'label' => 'FAQs'],
                    ] as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" class="hover:text-white transition-colors hover:translate-x-1 inline-block duration-300">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="lg:col-span-2">
                <h4 class="font-bold text-white mb-6 uppercase tracking-widest text-xs border-b border-white/10 pb-4 inline-block">Campus</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    @foreach ([
                        ['route' => 'about_us', 'label' => 'About us'],
                        ['route' => 'about_us', 'label' => 'Vision & mission'],
                        ['route' => 'admin_process', 'label' => 'Admission process'],
                        ['route' => 'apply_online', 'label' => 'Apply online'],
                    ] as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" class="hover:text-white transition-colors hover:translate-x-1 inline-block duration-300">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="lg:col-span-3">
                <h4 class="font-bold text-white mb-6 uppercase tracking-widest text-xs border-b border-white/10 pb-4 inline-block">Get In Touch</h4>
                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin text-educave-800 shrink-0 mt-1" aria-hidden="true">
                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <div>
                            <p class="text-gray-300 text-sm font-medium">Umunagbor Amagbor Ihitte</p>
                            <p class="text-white/55 text-sm">Ezinihitte Mbaise, Imo State, Nigeria</p>
                        </div>
                    </div>

                    <a href="tel:{{ preg_replace('/\s+/', '', (string) config('school.school_phone')) }}" class="flex items-center gap-4 group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone text-educave-800 shrink-0 group-hover:scale-110 transition-transform" aria-hidden="true">
                            <path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/>
                        </svg>
                        <span class="text-gray-300 text-sm group-hover:text-white transition-colors">{{ config('school.school_phone') }}</span>
                    </a>

                    <a href="mailto:{{ config('school.school_email') }}" class="flex items-center gap-4 group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail text-educave-800 shrink-0 group-hover:scale-110 transition-transform" aria-hidden="true">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/>
                        </svg>
                        <span class="text-gray-300 text-sm group-hover:text-white transition-colors">{{ config('school.school_email') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-white/5 bg-educave-975 py-8 relative z-10">
        <div class="container mx-auto px-4 md:px-8 lg:px-16 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-600">
            <p>© {{ date('Y') }} {{ site_settings()?->name ?? config('app.name') }}. All rights reserved.</p>
            <div class="flex gap-6 font-medium uppercase tracking-wider">
                @foreach (['Privacy', 'Terms', 'Cookies'] as $link)
                    <button class="hover:text-white transition-colors">{{ $link }}</button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 w-full overflow-hidden pointer-events-none opacity-[0.03]">
        <h1 class="text-[15vw] font-black text-center whitespace-nowrap leading-[0.75] text-white">PJP MODEL SEC SCH.</h1>
    </div>
</footer>
