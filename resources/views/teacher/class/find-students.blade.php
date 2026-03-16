@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('teacher.class.index') }}" class="text-indigo-600 hover:underline">← Back to Class</a>
    <h1 class="text-2xl font-semibold mt-2">Students {{ $class ? "— {$class}" : '' }}</h1>
</div>
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="{{ route('teacher.class.find-students') }}" method="GET" class="flex gap-3 flex-wrap items-end">
        <div><label class="block text-sm text-gray-700">Class</label><select name="class" class="rounded border border-gray-300 px-2 py-1">
            @foreach($getClasses ?? [] as $cn)
                @php $val = is_object($cn) ? ($cn->class_name ?? '') : $cn; @endphp
                <option value="{{ $val }}" {{ (isset($class) && $val === $class) ? 'selected' : '' }}>{{ $val }}</option>
            @endforeach
        </select></div>
        <div><label class="block text-sm text-gray-700">Search</label><input type="text" name="search" value="{{ $search ?? '' }}" class="rounded border border-gray-300 px-2 py-1" placeholder="Name or reg number"></div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Search</button>
    </form>
</div>
@if(isset($students) && (is_array($students) ? count($students) : $students->count()) > 0)
<div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
    <p class="px-4 sm:px-6 py-4 text-sm font-medium" style="color: var(--on-surface-variant); border-bottom: 1px solid var(--outline-variant);">{{ $totalItems }} student(s) found.</p>
    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 border-x border-b md:border-x md:border-b" style="border-color: var(--outline-variant);">
        <ul class="flex flex-col gap-3 md:gap-0 md:divide-y divide-[var(--outline-variant)] p-4 sm:px-6 md:p-0 list-none min-w-0" role="list">
            <li class="hidden md:flex items-center gap-3 sm:gap-4 px-5 sm:px-6 py-3" style="background: var(--surface-container); border-color: var(--outline-variant);">
                <span class="w-10 flex-shrink-0" aria-hidden="true"></span>
                <span class="text-xs font-medium flex-1 min-w-0" style="color: var(--on-surface-variant);">Name</span>
                <span class="text-xs font-medium flex-shrink-0 w-24" style="color: var(--on-surface-variant);">Class</span>
            </li>
            @foreach($students as $s)
                @php
                    $reg = is_object($s) ? ($s->reg_number ?? '') : ($s['reg_number'] ?? '');
                    $fullName = trim((is_object($s) ? (($s->firstname ?? '') . ' ' . ($s->lastname ?? '') . ' ' . ($s->othername ?? '')) : (($s['firstname'] ?? '') . ' ' . ($s['lastname'] ?? '') . ' ' . ($s['othername'] ?? ''))));
                    $classVal = is_object($s) ? ($s->class ?? '') : ($s['class'] ?? '');
                    $avatarSrc = asset('storage/students/default.png');
                    $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : 'S';
                    if (is_object($s) && !empty($s->imagelocation)) {
                        $avatarSrc = str_starts_with($s->imagelocation, 'students/') ? asset('storage/' . $s->imagelocation) : asset('storage/students/' . $s->imagelocation);
                    }
                @endphp
                <li class="flex flex-col gap-0 rounded-2xl border p-4 md:rounded-none md:border-0 md:border-b md:border-t-0 md:flex-row md:items-center md:gap-4 md:py-4 md:px-5 lg:px-6 md:min-w-0 md:p-0 transition-[background-color] duration-200" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                    <div class="flex items-center gap-3 md:contents">
                        <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2" style="border-color: var(--outline-variant);" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                        <div class="min-w-0 flex-1 md:min-w-0 md:flex-1">
                            <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Name</span>
                            <p class="text-sm font-medium truncate" style="color: var(--on-surface);">{{ $fullName ?: '—' }}</p>
                            <p class="text-xs truncate mt-0.5" style="color: var(--on-surface-variant);">{{ $reg }}</p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t md:border-t-0 md:mt-0 md:pt-0 flex flex-wrap items-baseline gap-x-4 gap-y-1 md:contents" style="border-color: var(--outline-variant);">
                        <span class="text-xs md:flex-shrink-0 md:w-24"><span class="md:sr-only" style="color: var(--on-surface-variant);">Class </span><span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ e($classVal) }}</span></span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@else
<p class="text-sm" style="color: var(--on-surface-variant);">No students found.</p>
@endif
@endsection
