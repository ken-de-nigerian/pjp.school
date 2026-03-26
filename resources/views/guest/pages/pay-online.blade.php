@extends('layouts.guest', ['title' => $title])

@section('content')
    <main id="main-content">
        <div class="min-h-screen bg-educave-50 font-sans selection:bg-educave-900 selection:text-white pt-20 pb-20">
            <div class="container mx-auto px-4 md:px-8 lg:px-16 max-w-5xl">

                {{-- Page Header --}}
                <div class="mb-12">
                    <a href="{{ route('admin_process') }}" class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-educave-900 transition-colors mb-6 w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        Back to Admission Requirements
                    </a>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-8 h-px bg-educave-800"></span>
                        <span class="text-xs font-bold uppercase tracking-widest text-educave-800">Step 1 of 2</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-educave-900 mb-4">Entrance Fee Payment</h1>
                    <p class="text-gray-500 text-sm">Academic Session &bull; {{ $settings['session'] }}</p>
                </div>

                <div class="flex flex-col lg:flex-row gap-12 items-start">

                    {{-- Payment Form --}}
                    <div class="w-full lg:w-3/5 order-2 lg:order-1">
                        <div class="bg-white p-8 md:p-12 shadow-sm border border-gray-100">

                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-6 block">Payment Method</span>
                            <h2 class="text-2xl font-serif font-bold text-educave-900 mb-10">How would you like to pay?</h2>

                            <form method="POST" action="{{ route('pay_online.store', $entrance->id) }}" class="space-y-6">
                                @csrf

                                <input type="hidden" id="email" name="email" value="{{ config('school.school_email') }}"/>
                                <input type="hidden" id="amount" name="amount" value="1000"/>

                                {{-- Online Payment --}}
                                <label class="relative flex cursor-pointer border p-6 transition-all duration-300 group border-gray-200 hover:border-educave-900" id="label-online">
                                    <input type="radio" name="paymentMethod" id="online" class="sr-only" checked/>
                                    <div class="flex flex-1 items-start gap-4">
                                        <div class="w-10 h-10 border border-gray-200 flex items-center justify-center shrink-0 group-hover:border-educave-900 transition-colors" id="icon-online">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-900" aria-hidden="true"><path d="M2 10h20"/><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M6 14h2"/><path d="M12 14h4"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold uppercase tracking-widest text-educave-900 mb-1">Pay Online</p>
                                            <p class="text-xs text-gray-500 leading-relaxed">Secure payment via Paystack — Card, Bank Transfer or USSD</p>
                                        </div>
                                    </div>
                                    <svg class="h-5 w-5 text-educave-900 check-icon-online" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                </label>

                                {{-- Bank Deposit --}}
                                <label class="relative flex cursor-pointer border p-6 transition-all duration-300 group border-gray-200 hover:border-educave-900" id="label-offline">
                                    <input type="radio" name="paymentMethod" id="offline" class="sr-only"/>
                                    <div class="flex flex-1 items-start gap-4">
                                        <div class="w-10 h-10 border border-gray-200 flex items-center justify-center shrink-0 group-hover:border-educave-900 transition-colors" id="icon-offline">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-900" aria-hidden="true"><path d="M3 21h18"/><path d="M3 10h18"/><path d="M5 6l7-3 7 3"/><path d="M4 10v11"/><path d="M20 10v11"/><path d="M8 14v3"/><path d="M12 14v3"/><path d="M16 14v3"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold uppercase tracking-widest text-educave-900 mb-1">Bank Deposit / Transfer</p>
                                            <p class="text-xs text-gray-500 leading-relaxed">Pay manually at the bank or via your banking app, then upload your receipt</p>
                                        </div>
                                    </div>
                                    <svg class="h-5 w-5 text-educave-900 check-icon-offline hidden" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                </label>

                                {{-- Bank details (shown when offline selected) --}}
                                <div id="make_offline" class="hidden border border-educave-800/20 bg-educave-50 p-8 space-y-0">
                                    <p class="text-xs font-bold uppercase tracking-widest text-educave-800 mb-6">Bank Payment Details</p>
                                    <div class="space-y-4 text-sm">
                                        <div class="flex justify-between border-b border-gray-100 pb-4">
                                            <span class="text-gray-400 font-bold uppercase tracking-widest text-xs">Bank</span>
                                            <span class="font-bold text-educave-900 uppercase">Access Bank</span>
                                        </div>
                                        <div class="flex justify-between border-b border-gray-100 pb-4">
                                            <span class="text-gray-400 font-bold uppercase tracking-widest text-xs">Account Name</span>
                                            <span class="font-bold text-educave-900">Nwaneri Kenneth</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-400 font-bold uppercase tracking-widest text-xs">Account Number</span>
                                            <span class="font-bold text-educave-900 text-xl tracking-[0.2em]">0057732360</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-educave-800 mt-6 leading-relaxed border-t border-educave-800/10 pt-4">
                                        <strong>Important:</strong> Use your full name or application number: <b>{{ $entrance->uniqueID }}</b> as the transfer narration. After payment, send your receipt to <a href="mailto:{{ config('school.school_email') }}" class="underline hover:text-educave-900">{{ config('school.school_email') }}</a> to confirm your payment.
                                    </p>
                                </div>

                                {{-- CTA Button --}}
                                <div class="pt-4">
                                    <button type="submit" id="checkoutBtn" class="w-full rounded-xl bg-educave-900 text-white py-4 text-xs font-bold uppercase tracking-widest hover:bg-educave-700 transition-colors flex items-center justify-center gap-3" data-preloader>
                                        Complete Online Payment
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </button>

                                    <p class="text-center text-xs text-gray-400 mt-4 leading-relaxed">
                                        Payments are processed securely via <strong class="text-gray-600">Paystack</strong>. PJP does not store your card details.
                                    </p>
                                </div>

                            </form>
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="w-full lg:w-2/5 order-1 lg:order-2">
                        <div class="bg-white p-8 shadow-sm border border-gray-100 sticky top-24">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-6 block">Order Summary</span>
                            <h2 class="text-2xl font-serif font-bold text-educave-900 mb-8">What you're paying for</h2>

                            <div class="space-y-6">
                                <div class="flex justify-between items-start gap-4 pb-6 border-b border-gray-100">
                                    <div>
                                        <p class="font-bold text-educave-900 text-sm uppercase tracking-widest mb-1">Entrance Examination Fee</p>
                                        <p class="text-xs text-gray-500 leading-relaxed">Academic Session {{ $settings['session'] }}<br/>{{ site_settings()->name }}</p>
                                    </div>
                                    <span class="font-bold text-educave-900 text-lg shrink-0">₦1,000</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Total (NGN)</span>
                                    <span class="text-3xl font-serif font-bold text-educave-900">₦1,000</span>
                                </div>
                            </div>

                            <div class="mt-8 pt-8 border-t border-gray-100 space-y-4">
                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-500 shrink-0 mt-0.5" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                                    <p class="text-xs text-gray-500 leading-relaxed">Secure, encrypted payment</p>
                                </div>

                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-500 shrink-0 mt-0.5" aria-hidden="true"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                    <p class="text-xs text-gray-500 leading-relaxed">Receipt sent to your email after payment</p>
                                </div>

                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-educave-500 shrink-0 mt-0.5" aria-hidden="true"><path d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384"/></svg>
                                    <p class="text-xs text-gray-500 leading-relaxed">Need help? Call <a href="tel:{{ config('school.school_phone') }}" class="font-bold text-educave-800 hover:text-educave-900 transition-colors">{{ config('school.school_phone') }}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const onlineRadio  = document.getElementById('online');
            const offlineRadio = document.getElementById('offline');
            const offlineDiv   = document.getElementById('make_offline');
            const checkoutBtn  = document.getElementById('checkoutBtn');
            const labelOnline  = document.getElementById('label-online');
            const labelOffline = document.getElementById('label-offline');
            const checkOnline  = document.querySelector('.check-icon-online');
            const checkOffline = document.querySelector('.check-icon-offline');

            function updateUI() {
                if (offlineRadio.checked) {
                    offlineDiv.classList.remove('hidden');
                    checkoutBtn.closest('.pt-4').classList.add('hidden');
                    labelOffline.classList.add('border-educave-900');
                    labelOffline.classList.remove('border-gray-200');
                    labelOnline.classList.remove('border-educave-900');
                    labelOnline.classList.add('border-gray-200');
                    checkOffline.classList.remove('hidden');
                    checkOnline.classList.add('hidden');
                } else {
                    offlineDiv.classList.add('hidden');
                    checkoutBtn.closest('.pt-4').classList.remove('hidden');
                    labelOnline.classList.add('border-educave-900');
                    labelOnline.classList.remove('border-gray-200');
                    labelOffline.classList.remove('border-educave-900');
                    labelOffline.classList.add('border-gray-200');
                    checkOnline.classList.remove('hidden');
                    checkOffline.classList.add('hidden');
                }
            }

            // Set the initial active state for online (default checked)
            labelOnline.classList.add('border-educave-900');
            labelOnline.classList.remove('border-gray-200');

            onlineRadio.addEventListener('change', updateUI);
            offlineRadio.addEventListener('change', updateUI);
        });
    </script>
@endpush
