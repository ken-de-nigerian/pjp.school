@extends('layouts.guest')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h1 class="text-2xl font-semibold">Result — {{ $term }} {{ $session }}</h1>
        <p class="text-gray-600 text-sm mt-1">
            {{ $student->firstname ?? '' }} {{ $student->lastname ?? '' }} {{ $student->othername ?? '' }} · {{ $student->reg_number ?? '' }} · {{ $class }}
        </p>
        <p class="text-sm mt-2"><a href="{{ route('result.check') }}" class="text-indigo-600 hover:underline">Check another result</a></p>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="rounded border border-gray-200 p-3">
                <p class="text-sm text-gray-500">Class position</p>
                <p class="text-xl font-semibold">{{ $reportCard->class_position ?? '—' }}</p>
            </div>
            <div class="rounded border border-gray-200 p-3">
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-xl font-semibold">{{ $reportCard->students_sub_total ?? '—' }}</p>
            </div>
        </div>

        <h2 class="text-lg font-medium mb-2">Subject breakdown</h2>
        <table class="min-w-full divide-y divide-gray-200 mb-6">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">CA</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($getSegment as $row)
                    <tr>
                        <td class="px-4 py-2">{{ $row->subjects ?? '' }}</td>
                        <td class="px-4 py-2">{{ $row->ca ?? '' }}</td>
                        <td class="px-4 py-2">{{ $row->exam ?? '' }}</td>
                        <td class="px-4 py-2">{{ $row->total ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($behavioral->isNotEmpty())
        <h2 class="text-lg font-medium mb-2">Behavioural</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Neatness</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Punctuality</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Politeness</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($behavioral as $b)
                    <tr>
                        <td class="px-4 py-2">{{ $b->neatness ?? '' }}</td>
                        <td class="px-4 py-2">{{ $b->punctuality ?? '' }}</td>
                        <td class="px-4 py-2">{{ $b->politeness ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
