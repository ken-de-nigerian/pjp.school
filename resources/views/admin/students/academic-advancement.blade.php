@extends('layouts.app', ['title' => 'Academic advancement'])

@section('content')
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Promote students"
                pill="Admin"
                title="Promote students"
                description="Promotion is done class by class starting from SSS 3 down to JSS 1."
            >
                <x-slot name="above">
                    <a href="{{ route('admin.classes') }}" class="admin-page-hero__back mb-2 sm:mb-0">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Back to students
                    </a>
                </x-slot>
                @if($layoutRole->manage_students ?? 0)
                    <x-slot name="actions">
                        <a href="{{ route('admin.students.demote_students') }}" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary w-full lg:w-auto justify-center min-h-[44px] sm:min-h-0">
                            <i class="fas fa-arrow-down-long text-[10px] sm:text-xs" aria-hidden="true"></i>
                            <span>Demote students</span>
                        </a>
                    </x-slot>
                @endif
            </x-admin.hero-page>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl p-5 sm:p-6 lg:p-8" style="background: var(--surface-container-low); box-shadow: var(--elevation-1);">
                <div class="col-span-full flex-1 flex flex-col items-center justify-center min-h-[min(400px,50vh)] py-12 sm:py-16">
                    <div class="rounded-3xl p-4 sm:p-6 lg:p-8 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                        <form action="{{ route('admin.students.promote') }}" method="POST" class="p-5 sm:p-6 space-y-5" id="promote-form">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                                <div class="md:col-span-1 form-group">
                                    <label for="promoteStudentFrom" class="form-label">From this class</label>
                                    <select id="promoteStudentFrom" name="from_class" class="form-select">
                                        <option value="">Select a class</option>
                                        @foreach(array_reverse($getClasses) as $class)
                                            <option value="{{ e($class->class_name) }}">{{ e($class->class_name) }}</option>
                                        @endforeach
                                    </select>
                                    <p id="from_class-error" class="form-error {{ $errors->has('from_class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('from_class') }}</p>
                                </div>

                                <div class="md:col-span-1 form-group">
                                    <label for="promoteStudentTo" class="form-label">To this class</label>
                                    <select id="promoteStudentTo" name="to_class" class="form-select">
                                        <option value="Graduated">Graduated</option>
                                        @foreach(array_reverse($getClasses) as $class)
                                            <option value="{{ e($class->class_name) }}">{{ e($class->class_name) }}</option>
                                        @endforeach
                                    </select>
                                    <p id="to_class-error" class="form-error {{ $errors->has('to_class') ? '' : 'hidden' }}" aria-live="polite">{{ $errors->first('to_class') }}</p>
                                </div>
                            </div>

                            <div class="pt-2 border-t" style="border-color: var(--outline-variant);">
                                <button type="submit" id="promoteBtn" class="btn-primary w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" style="border-radius: 999px;">
                                    <i class="fas fa-arrow-up-long text-xs" aria-hidden="true"></i>
                                    <span>Promote students</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('promote-form');
            const btn = document.getElementById('promoteBtn');
            const fieldIds = ['from_class', 'to_class'];
            if (!form || !btn) return;

            let token = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if (!token) token = form.querySelector('input[name="_token"]') && form.querySelector('input[name="_token"]').value;

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                if (typeof clearFieldErrors === 'function') clearFieldErrors(fieldIds);

                if (!token) {
                    if (typeof flashError === 'function') flashError('Security token missing. Please refresh the page.');
                    return;
                }

                if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(function (response) {
                    if (response.status === 422) {
                        return response.json().then(function (data) {
                            if (data.errors && typeof showLaravelErrors === 'function') {
                                showLaravelErrors(data.errors);
                            }
                            throw new Error('Validation failed');
                        });
                    }
                    return response.json();
                }).then(function (data) {
                    if (data.status === 'success' && typeof flashSuccess === 'function') {
                        flashSuccess(data.message || 'Students promoted.');
                    } else if (data.status === 'error' && typeof flashError === 'function') {
                        flashError(data.message || 'Unable to promote students.');
                    }
                }).catch(function (err) {
                    if (err.message !== 'Validation failed' && typeof flashError === 'function') {
                        flashError('An error occurred while promoting students.');
                    }
                }).finally(function () {
                    if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                });
            });
        });
    </script>
    @endpush
@endsection
