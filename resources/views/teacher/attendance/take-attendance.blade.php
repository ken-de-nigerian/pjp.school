@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('teacher.attendance.index') }}" class="text-indigo-600 hover:underline">← Back to Attendance</a>
    <h1 class="text-2xl font-semibold mt-2">Take attendance — {{ $class }}</h1>
</div>
<form id="attendance-form" class="bg-white rounded-lg shadow overflow-hidden">
    @csrf
    <input type="hidden" name="_target" value="{{ url('requests/take_teacher_attendance') }}">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-14"></th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reg #</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Present / Absent</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $s)
                    @php
                        $fullName = trim(($s->firstname ?? '') . ' ' . ($s->lastname ?? '') . ' ' . ($s->othername ?? ''));
                        $avatarSrc = ($s->imagelocation ?? null)
                            ? (str_starts_with($s->imagelocation, 'students/') ? asset('storage/' . $s->imagelocation) : asset('storage/students/' . $s->imagelocation))
                            : asset('storage/students/default.png');
                        $avatarInitial = $fullName ? mb_substr($fullName, 0, 1) : 'S';
                    @endphp
                    <tr>
                        <td class="px-4 py-2">
                            <img src="{{ $avatarSrc }}" alt="" class="w-10 h-10 rounded-full object-cover border border-gray-200" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($avatarInitial) }}&size=80'">
                        </td>
                        <td class="px-4 py-2">{{ $s->firstname ?? '' }} {{ $s->lastname ?? '' }}</td>
                        <td class="px-4 py-2">{{ $s->reg_number ?? '' }}</td>
                        <td class="px-4 py-2">
                            <select name="students[{{ $loop->index }}][class_roll_call]" class="rounded border-gray-300">
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                            </select>
                            <input type="hidden" name="students[{{ $loop->index }}][class]" value="{{ $class }}">
                            <input type="hidden" name="students[{{ $loop->index }}][term]" value="{{ $term }}">
                            <input type="hidden" name="students[{{ $loop->index }}][session]" value="{{ $session }}">
                            <input type="hidden" name="students[{{ $loop->index }}][name]" value="{{ $s->firstname ?? '' }} {{ $s->lastname ?? '' }}">
                            <input type="hidden" name="students[{{ $loop->index }}][reg_number]" value="{{ $s->reg_number ?? '' }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 bg-gray-50">
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save attendance</button>
    </div>
</form>
<p id="attendance-msg" class="mt-2 text-sm hidden"></p>
<script>
document.getElementById('attendance-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const fd = new FormData(form);
    const msg = document.getElementById('attendance-msg');
    msg.classList.add('hidden');
    fetch(form.querySelector('input[name="_target"]').value, { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(d => { msg.classList.remove('hidden'); msg.textContent = d.message || ''; msg.className = 'mt-2 text-sm ' + (d.status === 'success' ? 'text-green-600' : 'text-red-600'); })
        .catch(() => { msg.classList.remove('hidden'); msg.className = 'mt-2 text-sm text-red-600'; msg.textContent = 'Request failed.'; });
});
</script>
@endsection
