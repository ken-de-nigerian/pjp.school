@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Profile</h1>
</div>

<div class="bg-white rounded-lg shadow p-4 max-w-2xl">
    <h2 class="text-lg font-medium border-b border-gray-200 pb-2 mb-4">Profile Settings</h2>
    <form id="teacher-profile-form">
        @csrf
        <input type="hidden" id="formattedPhone" name="formattedPhone" value="{{ old('formattedPhone', $user->phone ?? '') }}">
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="firstname">First name</label>
                <input type="text" id="firstname" name="firstname" class="w-full rounded border border-gray-300 px-3 py-2" value="{{ old('firstname', $user->firstname ?? '') }}" placeholder="e.g. John" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="lastname">Last name</label>
                <input type="text" id="lastname" name="lastname" class="w-full rounded border border-gray-300 px-3 py-2" value="{{ old('lastname', $user->lastname ?? '') }}" placeholder="e.g. Smith" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="phone">Mobile number</label>
                <input type="text" id="phone" name="formattedPhone" class="w-full rounded border border-gray-300 px-3 py-2" value="{{ old('formattedPhone', $user->phone ?? '') }}" placeholder="e.g. +234 800 000 0000" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="country">Country</label>
                <input type="text" id="country" name="country" class="w-full rounded border border-gray-300 px-3 py-2" value="{{ old('country', $user->country ?? '') }}" placeholder="e.g. Nigeria" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="gender">Gender</label>
                <select id="gender" name="gender" class="w-full rounded border border-gray-300 px-3 py-2" required>
                    <option value="">Select</option>
                    <option value="Male" {{ old('gender', $user->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $user->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="address">Address</label>
                <textarea id="address" name="address" class="w-full rounded border border-gray-300 px-3 py-2" rows="3" placeholder="e.g. 123 Main Street, City" required>{{ old('address', $user->address ?? '') }}</textarea>
            </div>
            <div class="pt-2">
                <button type="button" id="profileBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save change</button>
            </div>
        </div>
    </form>
</div>

<div class="mt-6 bg-white rounded-lg shadow p-4 max-w-2xl">
    <h2 class="text-lg font-medium border-b border-gray-200 pb-2 mb-4">Change Password</h2>
    <button type="button" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700" data-modal="changePassword">Change Password</button>
</div>

<div id="changePassword" class="fixed inset-0 z-50 hidden" aria-modal="true">
    <div class="fixed inset-0 bg-black/50" data-close="changePassword"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold mb-4">Change Password</h3>
            <form id="teacher-password-form">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="oldPassword">Current password</label>
                        <input type="password" id="oldPassword" name="oldPassword" class="w-full rounded border border-gray-300 px-3 py-2" placeholder="Enter current password" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="password">New password</label>
                        <input type="password" id="password" name="password" class="w-full rounded border border-gray-300 px-3 py-2" placeholder="Enter new password (min. 8 characters)" required minlength="8">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="confirmPassword">Confirm new password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" class="w-full rounded border border-gray-300 px-3 py-2" placeholder="Confirm new password" required minlength="8">
                    </div>
                    <p id="password-form-error" class="text-red-600 text-sm hidden"></p>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded" data-close="changePassword">Cancel</button>
                    <button type="submit" id="passwordBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Change password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;

    document.getElementById('profileBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        const form = document.getElementById('teacher-profile-form');
        const btn = this;
        btn.disabled = true;
        btn.textContent = 'Processing...';
        fetch('{{ route("teacher.profile.update") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: new URLSearchParams(new FormData(form))
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') setTimeout(function() { window.location.reload(); }, 2800);
            else alert(Array.isArray(data.message) ? data.message.join('\n') : data.message);
        })
        .catch(() => alert('An error occurred.'))
        .finally(() => { btn.disabled = false; btn.textContent = 'Save change'; });
    });

    document.querySelectorAll('[data-modal="changePassword"]').forEach(el => {
        el.addEventListener('click', () => document.getElementById('changePassword').classList.remove('hidden'));
    });
    document.querySelectorAll('[data-close="changePassword"]').forEach(el => {
        el.addEventListener('click', () => document.getElementById('changePassword').classList.add('hidden'));
    });

    document.getElementById('teacher-password-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('passwordBtn');
        const errEl = document.getElementById('password-form-error');
        errEl.classList.add('hidden');
        btn.disabled = true;
        fetch('{{ route("teacher.profile.password") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: new URLSearchParams(new FormData(this))
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('changePassword').classList.add('hidden');
                setTimeout(function() { window.location.reload(); }, 2800);
            } else {
                errEl.textContent = Array.isArray(data.message) ? data.message.join(' ') : data.message;
                errEl.classList.remove('hidden');
            }
        })
        .catch(() => { errEl.textContent = 'An error occurred.'; errEl.classList.remove('hidden'); })
        .finally(() => { btn.disabled = false; });
    });
});
</script>
@endpush
@endsection
