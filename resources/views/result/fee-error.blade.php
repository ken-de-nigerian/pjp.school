@extends('layouts.guest', ['title' => 'Fee Not Approved'])

@section('content')
    <main id="main-content">
        <div class="min-h-screen bg-educave-50 font-sans selection:bg-educave-900 selection:text-white pt-20 pb-20 flex items-center justify-center px-4">
            <div class="max-w-2xl w-full">
                <div class="bg-white shadow-sm border border-gray-100 p-12 text-center">
                    <div class="w-16 h-16 bg-educave-50 border border-educave-200 flex items-center justify-center mx-auto mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-800" aria-hidden="true">
                            <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4"/><path d="M12 17h.01"/>
                        </svg>
                    </div>

                    <div class="inline-flex items-center gap-2 mb-6 px-3 py-1 border border-educave-200 bg-educave-50">
                        <span class="w-1.5 h-1.5 bg-educave-800 rounded-full"></span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-educave-800">Fee Status</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-educave-900 mb-6 leading-tight">
                        Fee Not <span class="italic text-gray-400">Approved</span>
                    </h1>

                    <p class="text-gray-500 leading-relaxed mb-4 max-w-md mx-auto">
                        Your fee payment has not yet been approved by the school. Result access is only granted after your payment has been verified and confirmed by the accounts office.
                    </p>

                    <p class="text-gray-500 leading-relaxed mb-12 max-w-md mx-auto">
                        If you believe this is an error or have already made payment, please contact the school directly so we can resolve this for you.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-12 text-left">
                        <a href="tel:{{ config('school.school_phone') }}" class="flex items-center gap-4 p-5 border border-gray-200 hover:border-educave-900 transition-colors group">
                            <div class="w-10 h-10 border border-gray-200 flex items-center justify-center shrink-0 group-hover:border-educave-900 group-hover:bg-educave-900 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-800 group-hover:text-white transition-colors" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Call Us</p>
                                <p class="text-sm font-bold text-educave-900">{{ config('school.school_phone') }}</p>
                            </div>
                        </a>

                        <a href="mailto:{{ config('school.school_email') }}" class="flex items-center gap-4 p-5 border border-gray-200 hover:border-educave-900 transition-colors group">
                            <div class="w-10 h-10 border border-gray-200 flex items-center justify-center shrink-0 group-hover:border-educave-900 group-hover:bg-educave-900 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-800 group-hover:text-white transition-colors" aria-hidden="true"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Email Us</p>
                                <p class="text-sm font-bold text-educave-900">{{ config('school.school_email') }}</p>
                            </div>
                        </a>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('result.check') }}" class="flex items-center justify-center gap-2 px-8 py-4 bg-educave-900 text-white text-xs font-bold uppercase tracking-widest hover:bg-educave-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                            Back to Result Check
                        </a>
                        <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 px-8 py-4 border border-gray-300 text-educave-900 text-xs font-bold uppercase tracking-widest hover:bg-educave-50 transition-colors">
                            Go to Homepage
                        </a>
                    </div>
                </div>

                <p class="text-center text-xs text-gray-400 mt-8 leading-relaxed">
                    Office hours: Monday — Friday, 8:00 AM – 4:00 PM WAT<br/>
                    {{ site_settings()->address }}
                </p>
            </div>
        </div>
    </main>
@endsection
