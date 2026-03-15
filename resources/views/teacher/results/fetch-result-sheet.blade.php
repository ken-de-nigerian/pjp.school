@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('teacher.dashboard') }}" class="text-indigo-600 hover:underline text-sm">← Dashboard</a>
    <h1 class="text-2xl font-semibold mt-2">Upload results</h1>
    <p class="text-gray-600 text-sm mt-1">Select class, subject, term and session to open the result sheet (CA 15 · Assign 25 · Exam 60).</p>
</div>
<div class="bg-white rounded-lg shadow p-6">
    <form method="GET" action="{{ route('teacher.results.index') }}" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Class</label>
                <select name="class" class="mt-1 rounded-lg border border-gray-300 w-full px-3 py-2" required>
                    <option value="">Select class</option>
                    @foreach($getClasses as $cn)
                        @php $cname = is_object($cn) ? ($cn->class_name ?? '') : $cn; @endphp
                        <option value="{{ $cname }}" {{ ($class ?? '') === $cname ? 'selected' : '' }}>{{ $cname }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Subject</label>
                <select id="teacher-subject" name="subjects" class="mt-1 rounded-lg border border-gray-300 w-full px-3 py-2" required>
                    <option value="">Select subject</option>
                    @foreach($getSubjects as $s)
                        <option value="{{ e($s->subject_name) }}" data-grade="{{ e($s->grade) }}" {{ ($subjects ?? '') === $s->subject_name ? 'selected' : '' }}>{{ e($s->subject_name) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Term</label>
                <select name="term" class="mt-1 rounded-lg border border-gray-300 w-full px-3 py-2">
                    <option value="First Term" {{ ($term ?? '') === 'First Term' ? 'selected' : '' }}>First Term</option>
                    <option value="Second Term" {{ ($term ?? '') === 'Second Term' ? 'selected' : '' }}>Second Term</option>
                    <option value="Third Term" {{ ($term ?? '') === 'Third Term' ? 'selected' : '' }}>Third Term</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Session</label>
                <select name="session" class="mt-1 rounded-lg border border-gray-300 w-full px-3 py-2" required>
                    <option value="">Select session</option>
                    @php
                        $sessOpts = $sessions->map(fn ($sess) => $sess->year . '/' . ($sess->year + 1))->unique()->values();
                        if ($sessOpts->isEmpty()) {
                            foreach (range((int) date('Y') - 5, (int) date('Y') + 1) as $y) {
                                $sessOpts->push($y . '/' . ($y + 1));
                            }
                        }
                    @endphp
                    @foreach($sessOpts as $opt)
                        <option value="{{ e($opt) }}" {{ ($session ?? '') === $opt ? 'selected' : '' }}>{{ e($opt) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">Load result sheet</button>
    </form>
</div>
<script>
(function () {
    var classSelect = document.querySelector('select[name="class"]');
    var subjectSelect = document.getElementById('teacher-subject');
    if (!classSelect || !subjectSelect) return;
    function gradeForClass(name) {
        if (!name) return null;
        var p = name.substring(0, 3).toUpperCase();
        if (p === 'JSS') return 'Junior';
        if (p === 'SSS') return 'Senior';
        return null;
    }
    function filterSubjects() {
        var g = gradeForClass(classSelect.value);
        subjectSelect.querySelectorAll('option[data-grade]').forEach(function (opt) {
            var show = !g || opt.getAttribute('data-grade') === g;
            opt.style.display = show ? '' : 'none';
            opt.disabled = !show;
        });
        var sel = subjectSelect.value;
        var ok = Array.from(subjectSelect.options).some(function (o) { return o.value === sel && !o.disabled; });
        if (sel && !ok) subjectSelect.value = '';
    }
    classSelect.addEventListener('change', filterSubjects);
    filterSubjects();
})();
</script>
@endsection
