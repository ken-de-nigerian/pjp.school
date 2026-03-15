@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Upload Profile Picture</h1>
    <p class="text-gray-600 text-sm mt-1">Upload a profile photo (jpg, jpeg, png, max 2MB).</p>
</div>
<div class="bg-white rounded-lg shadow p-6 max-w-md">
    <form id="upload-form" enctype="multipart/form-data">
        @csrf
        <input type="file" name="photoimg" accept="image/jpeg,image/png,image/jpg" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-indigo-50 file:text-indigo-700" required>
        <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Upload</button>
    </form>
    <p id="upload-message" class="mt-2 text-sm hidden"></p>
</div>
<script>
document.getElementById('upload-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const fd = new FormData(form);
    const msg = document.getElementById('upload-message');
    msg.classList.add('hidden');
    fetch('{{ route("teacher.upload.store") }}', { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(d => {
            msg.classList.remove('hidden');
            msg.textContent = d.message || (d.status === 'success' ? 'Uploaded successfully.' : 'Error.');
            msg.className = 'mt-2 text-sm ' + (d.status === 'success' ? 'text-green-600' : 'text-red-600');
        })
        .catch(() => { msg.classList.remove('hidden'); msg.className = 'mt-2 text-sm text-red-600'; msg.textContent = 'Request failed.'; });
});
</script>
@endsection
