{{-- Legacy: footer.bg-dark --}}
<footer class="bg-dark p-3 mt-auto">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="row flex flex-wrap items-center gap-4">
            <div class="col-md-4 text-center lg:text-left">
                <a href="{{ url('/') }}"><span class="text-white font-semibold">{{ config('app.name') }}</span></a>
            </div>
            <div class="col-md-4 text-center text-gray-400 text-sm">
                © {{ date('Y') }} {{ config('app.name') }}, All Rights Reserved.
            </div>
            <div class="col-md-4 text-center lg:text-right">
                <ul class="list-inline mb-0 flex justify-center lg:justify-end gap-2">
                    <li class="list-inline-item"><a href="#" class="text-white hover:text-gray-300"><span aria-hidden="true">f</span></a></li>
                    <li class="list-inline-item"><a href="#" class="text-white hover:text-gray-300"><span aria-hidden="true">in</span></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
