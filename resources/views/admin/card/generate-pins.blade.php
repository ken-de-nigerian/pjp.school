@extends('layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Generate Pins</h1>
    <a href="{{ route('admin.card.index') }}" class="text-blue-600 hover:underline">Back to Scratch Card</a>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form id="generate-pins-form" action="{{ route('admin.card.generate-pins.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="session" class="block text-sm font-medium text-gray-700">Session <span class="text-red-500">*</span></label>
                <select name="session" id="session" required class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                    <option value="">Select session</option>
                    @foreach($sessions as $s)
                        <option value="{{ e($s->year ?? $s->id ?? '') }}" @selected(($settings['session'] ?? '') === ($s->year ?? ''))>
                            {{ e($s->year ?? 'Session '.$s->id) }}
                        </option>
                    @endforeach
                </select>
                @error('session')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="pins_text" class="block text-sm font-medium text-gray-700">Pins (one per line, or use gen_pin1–gen_pin500)</label>
                <textarea name="pins_text" id="pins_text" rows="10" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" placeholder="Enter one pin per line. Max 500."></textarea>
                <p class="mt-1 text-xs text-gray-500">Pins are submitted via JavaScript from the textarea or from gen_pin1..gen_pin500 fields.</p>
            </div>
        </div>
        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-amber-600 text-white px-4 py-2 rounded hover:bg-amber-700">Generate Pins</button>
            <a href="{{ route('admin.card.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('generate-pins-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var textarea = document.getElementById('pins_text');
    var lines = textarea.value.split(/[\r\n]+/).map(function(s) { return s.trim(); }).filter(Boolean);
    var form = this;
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = '_token';
    input.value = '{{ csrf_token() }}';
    form.appendChild(input);
    lines.slice(0, 500).forEach(function(pin, i) {
        var inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'pins[' + i + ']';
        inp.value = pin;
        form.appendChild(inp);
    });
    var sessionVal = form.querySelector('[name=session]');
    if (!sessionVal || !sessionVal.value) {
        alert('Please select a session.');
        return;
    }
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    }).then(function(r) { return r.json(); }).then(function(d) {
        if (d.status === 'success') {
            if (typeof flashSuccess === 'function') flashSuccess(d.message || 'Pins generated successfully.');
            setTimeout(function() { window.location.href = '{{ route('admin.card.index') }}'; }, 2800);
        } else {
            alert(d.message || 'Failed to generate pins.');
        }
    }).catch(function() { form.submit(); });
});
</script>
@endpush
@endsection
