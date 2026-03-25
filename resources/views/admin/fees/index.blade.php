@php
    use App\Enums\FeeCategoryEnum;
    use App\Models\Fee;
@endphp
@extends('layouts.app', ['title' => 'Result fees'])

@section('content')
    @php
        $storeUrl = route('admin.fees.store');
    @endphp
    <main class="flex-1 flex flex-col min-h-0 w-full overflow-y-auto overflow-x-hidden overscroll-y-none pb-24 lg:pb-8 scrollbar-hide" style="background: var(--surface);">
        <div class="page-content flex-1 flex flex-col w-full max-w-7xl mx-auto min-w-0 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10">
            <x-admin.hero-page
                aria-label="Report card fees"
                pill="Admin"
                title="Result fees"
                description="Fees shown on student report cards for each term and session. Grouped by category (boarder, day, general)."
            >
                <x-slot name="actions">
                    <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto lg:flex-shrink-0">
                        @can('create', Fee::class)
                            <button type="button" id="fee-open-create" class="admin-dashboard-hero__btn admin-dashboard-hero__btn--primary min-h-[2.5rem] sm:min-h-0 w-full sm:w-auto justify-center">
                                <i class="fas fa-plus text-[10px] sm:text-xs" aria-hidden="true"></i>
                                <span>Add fee</span>
                            </button>
                        @endcan
                    </div>
                </x-slot>
            </x-admin.hero-page>

            <div class="rounded-3xl p-4 sm:p-5 lg:p-6 mb-6 overflow-hidden min-w-0 w-full" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                <form method="get" action="{{ route('admin.fees.index') }}" class="space-y-4 sm:space-y-5">
                    <div class="form-group min-w-0">
                        <label for="filter_term" class="form-label text-xs">Term</label>
                        <x-forms.md-select-native id="filter_term" name="term" class="form-select w-full min-w-[10rem]">
                            <option value="">All terms</option>
                            @foreach($termOptions as $t)
                                <option value="{{ $t }}" @selected($filterTerm === $t)>{{ $t }}</option>
                            @endforeach
                        </x-forms.md-select-native>
                    </div>

                    <div class="form-group min-w-0">
                        <label for="filter_session" class="form-label text-xs">Session</label>
                        <x-forms.md-select-native id="filter_session" name="session" class="form-select w-full min-w-[10rem]">
                            <option value="">All sessions</option>
                            @foreach(range((int) date('Y') - 5, (int) date('Y') + 5) as $y)
                                @php $opt = $y . '/' . ($y + 1); @endphp
                                <option value="{{ $opt }}" @selected($filterSession === $opt)>{{ $opt }}</option>
                            @endforeach
                        </x-forms.md-select-native>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 min-w-0" style="border-top: 1px solid var(--outline-variant); padding-top: 1.25rem;">
                        <a href="{{ route('admin.fees.index') }}" class="btn-secondary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 sm:min-w-[120px]" style="border-radius: 12px;">
                            <i class="fas fa-times text-sm" aria-hidden="true"></i>
                            Clear
                        </a>

                        <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 min-h-[2.75rem] sm:min-h-0 min-w-[140px] rounded-xl text-sm font-medium transition-all duration-200 hover:opacity-95 active:scale-[0.98]" data-preloader style="border-radius: 12px;">
                            <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex-1 flex flex-col min-h-0 w-full rounded-3xl overflow-hidden" style="background: var(--surface-container-low); box-shadow: var(--elevation-1); border: 1px solid var(--outline-variant);">
                @if($fees->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 md:py-40 px-6">
                        <div class="dashboard-stat-icon dashboard-stat-icon--blue w-20 h-20 rounded-2xl mx-auto mb-5" style="border-radius: 16px;">
                            <i class="fas fa-money-bill-wave text-3xl" aria-hidden="true"></i>
                        </div>
                        <h2 class="text-lg font-medium mb-2" style="color: var(--on-surface);">No fees for this filter</h2>
                        <p class="text-sm text-center max-w-sm mb-6" style="color: var(--on-surface-variant);">Add fee lines or adjust term & session filters.</p>
                        <div class="flex justify-center">
                            @can('create', Fee::class)
                                <button type="button" class="fee-open-create btn-primary inline-flex items-center justify-center gap-2 px-8 py-3 min-w-[180px] rounded-xl font-medium text-sm" style="border-radius: 12px;">
                                    <i class="fas fa-plus text-sm" aria-hidden="true"></i>
                                    Add fee
                                </button>
                            @endcan
                        </div>
                    </div>
                @else
                    <div class="hidden md:grid md:grid-cols-[auto_1fr_auto_auto_auto_auto_auto] md:gap-4 lg:gap-6 px-4 sm:px-6 py-3.5 sticky top-0 z-10 min-w-0" style="background: var(--surface-container); border-bottom: 1px solid var(--outline-variant);">
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: var(--on-surface-variant);">#</span>
                        <span class="text-xs font-semibold uppercase tracking-wider min-w-0" style="color: var(--on-surface-variant);">Title</span>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: var(--on-surface-variant);">Amount</span>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: var(--on-surface-variant);">Category</span>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: var(--on-surface-variant);">Term</span>
                        <span class="text-xs font-semibold uppercase tracking-wider" style="color: var(--on-surface-variant);">Active</span>
                        <span class="text-xs font-semibold uppercase tracking-wider text-right" style="color: var(--on-surface-variant);">Actions</span>
                    </div>

                    <ul class="flex flex-col gap-3 md:gap-0 p-4 sm:px-6 md:p-0 list-none min-w-0" role="list">
                        @foreach($fees as $fee)
                            <li class="fee-row flex flex-col gap-3 rounded-2xl border p-4 md:rounded-none md:border-0 md:border-b md:border-t-0 md:grid md:grid-cols-[auto_1fr_auto_auto_auto_auto_auto] md:gap-4 lg:gap-6 md:items-center md:py-4 md:px-4 lg:px-6 md:min-w-0 transition-[background-color] duration-200 shadow-sm md:shadow-none md:hover:bg-[var(--surface-container-low)]" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                                <span class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-sm font-semibold md:w-8 md:h-8 md:place-self-center" style="background: var(--primary-container); color: var(--on-primary-container);">{{ $loop->iteration }}</span>
                                <div class="min-w-0">
                                    <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Title</span>
                                    <p class="text-sm font-medium break-words" style="color: var(--on-surface);">{{ e($fee->title) }}</p>
                                    @if($fee->description)
                                        <p class="text-xs mt-1 line-clamp-2" style="color: var(--on-surface-variant);">{{ e($fee->description) }}</p>
                                    @endif
                                </div>

                                <div>
                                    <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Amount</span>
                                    <span class="text-sm font-semibold tabular-nums">₦{{ number_format((float) $fee->amount, 2) }}</span>
                                </div>

                                <div>
                                    <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Category</span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium" style="background: var(--surface-container-high); color: var(--on-surface-variant);">{{ $fee->category->label() }}</span>
                                </div>

                                <div>
                                    <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Term</span>
                                    <span class="text-xs">{{ e($fee->term) }}</span>
                                </div>

                                <div>
                                    <span class="text-xs font-medium md:sr-only" style="color: var(--on-surface-variant);">Active</span>
                                    <span class="text-xs font-medium">{{ $fee->is_active ? 'Yes' : 'No' }}</span>
                                </div>

                                <div class="flex flex-wrap gap-2 md:justify-end">
                                    @can('update', $fee)
                                        <button type="button" class="fee-open-edit inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-medium min-h-[2.75rem] md:min-h-0 flex-1 md:flex-initial" style="background: var(--primary-container); color: var(--on-primary-container);"
                                            data-payload-b64="{{ base64_encode(json_encode(['id' => $fee->id, 'title' => $fee->title, 'description' => $fee->description ?? '', 'amount' => (string) $fee->amount, 'category' => $fee->category->value, 'term' => $fee->term, 'session' => $fee->session, 'active' => $fee->is_active ? '1' : '0'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}"
                                        >
                                            <i class="fas fa-pen text-xs" aria-hidden="true"></i>
                                            Edit
                                        </button>
                                    @endcan

                                    @can('delete', $fee)
                                        <button type="button" class="fee-open-delete inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-medium min-h-[2.75rem] md:min-h-0 flex-1 md:flex-initial" style="background: var(--error-container); color: var(--on-error-container);"
                                            data-id="{{ $fee->id }}"
                                            data-title="{{ e($fee->title) }}"
                                            data-destroy-url="{{ route('admin.fees.destroy', $fee) }}"
                                        >
                                            <i class="fas fa-trash-alt text-xs" aria-hidden="true"></i>
                                            Delete
                                        </button>
                                    @endcan
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </main>

    @auth('admin')
        <div id="fee-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="fee-modal-title">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="fee-modal" aria-hidden="true"></div>
            <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
                <div class="relative w-full max-w-lg min-w-0 max-h-[calc(100dvh-2rem)] overflow-y-auto overscroll-contain rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                    <h3 id="fee-modal-title" class="text-lg font-semibold mb-1" style="color: var(--on-surface);">Add fee</h3>
                    <p class="text-sm mb-5" style="color: var(--on-surface-variant);">Shown on student report cards for the selected term and session.</p>
                    <form id="fee-modal-form" action="{{ $storeUrl }}" method="POST" class="space-y-4 min-w-0">
                        @csrf
                        <input type="hidden" name="_method" id="fee-method" value="" autocomplete="off">

                        <div class="form-group min-w-0">
                            <label for="fee_title" class="form-label">Title</label>
                            <input type="text" id="fee_title" name="title" class="form-input w-full min-w-0" placeholder="e.g. Tuition" autocomplete="off">
                            <p id="fee_title-error" class="form-error hidden mt-1.5 text-sm" style="color: var(--on-error-container);" aria-live="polite"></p>
                        </div>

                        <div class="form-group min-w-0">
                            <label for="fee_description" class="form-label">Description <span class="font-normal opacity-75">(optional)</span></label>
                            <textarea id="fee_description" name="description" rows="2" class="form-input w-full min-w-0" placeholder="Notes for parents"></textarea>
                            <p id="fee_description-error" class="form-error hidden mt-1.5 text-sm" style="color: var(--on-error-container);" aria-live="polite"></p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-group min-w-0">
                                <label for="fee_amount" class="form-label">Amount (₦)</label>
                                <input type="number" step="0.01" min="0" id="fee_amount" name="amount" class="form-input w-full min-w-0 tabular-nums" placeholder="0.00">
                                <p id="fee_amount-error" class="form-error hidden mt-1.5 text-sm" style="color: var(--on-error-container);" aria-live="polite"></p>
                            </div>
                            <div class="form-group min-w-0">
                                <label for="fee_category" class="form-label">Category</label>
                                <x-forms.md-select-native id="fee_category" name="category" class="form-select w-full min-w-0">
                                    @foreach(FeeCategoryEnum::cases() as $cat)
                                        <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                                    @endforeach
                                </x-forms.md-select-native>
                                <p id="fee_category-error" class="form-error hidden mt-1.5 text-sm" style="color: var(--on-error-container);" aria-live="polite"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-group min-w-0">
                                <label for="fee_term" class="form-label">Term</label>
                                <x-forms.md-select-native id="fee_term" name="term" class="form-select w-full min-w-0">
                                    @foreach($termOptions as $t)
                                        <option value="{{ $t }}" @selected($filterTerm === $t)>{{ $t }}</option>
                                    @endforeach
                                </x-forms.md-select-native>
                                <p id="fee_term-error" class="form-error hidden mt-1.5 text-sm" style="color: var(--on-error-container);" aria-live="polite"></p>
                            </div>

                            <div class="form-group min-w-0">
                                <label for="fee_session" class="form-label">Session</label>
                                <x-forms.md-select-native id="fee_session" name="session" class="form-select w-full min-w-0">
                                    @foreach(range((int) date('Y') - 5, (int) date('Y') + 5) as $y)
                                        @php $opt = $y . '/' . ($y + 1); @endphp
                                        <option value="{{ $opt }}" @selected($filterSession === $opt)>{{ $opt }}</option>
                                    @endforeach
                                </x-forms.md-select-native>
                                <p id="fee_session-error" class="form-error hidden mt-1.5 text-sm" style="color: var(--on-error-container);" aria-live="polite"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="fee_is_active" name="is_active" value="1" class="rounded border" checked>
                            <label for="fee_is_active" class="text-sm" style="color: var(--on-surface);">Active (show on report)</label>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-2">
                            <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="fee-modal">Cancel</button>
                            <button type="submit" id="fee-submit" class="btn-primary inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto">
                                <i class="fas fa-save text-xs" aria-hidden="true"></i>
                                <span id="fee-submit-label">Save</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="fee-delete-modal" class="fixed inset-0 z-50 hidden overflow-y-auto overscroll-contain" aria-modal="true" role="dialog" aria-labelledby="fee-delete-title">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" data-close="fee-delete-modal" aria-hidden="true"></div>
            <div class="relative min-h-full flex items-center justify-center p-4 py-6 sm:p-6">
                <div class="relative w-full max-w-md min-w-0 rounded-xl py-5 px-4 sm:py-6 sm:px-6 shadow-xl border my-auto" style="background: var(--surface-container-lowest); border-color: var(--outline-variant);">
                    <h3 id="fee-delete-title" class="text-lg font-semibold mb-2" style="color: var(--on-surface);">Delete fee</h3>
                    <p id="fee-delete-message" class="text-sm mb-6" style="color: var(--on-surface-variant);"></p>
                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2">
                        <button type="button" class="btn-secondary px-4 py-2.5 rounded-full text-sm w-full sm:w-auto" data-close="fee-delete-modal">Cancel</button>
                        <button type="button" id="fee-delete-confirm" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-medium w-full sm:w-auto transition-opacity hover:opacity-95" style="background: var(--error-container); color: var(--on-error-container);">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    @push('scripts')
        @auth('admin')
            <script>
                (function() {
                    const csrf = document.querySelector('meta[name="csrf-token"]') && document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const storeUrl = @json($storeUrl);
                    const filterTerm = @json($filterTerm ?? '');
                    const filterSession = @json($filterSession ?? '');
                    function parsePayloadB64(btn) {
                        const b64 = btn.getAttribute('data-payload-b64');
                        if (!b64) return {};
                        try {
                            return JSON.parse(atob(b64.replace(/\s/g, '')));
                        } catch (e) {
                            return {};
                        }
                    }
                    const modal = document.getElementById('fee-modal');
                    const form = document.getElementById('fee-modal-form');
                    const methodInput = document.getElementById('fee-method');
                    const titleEl = document.getElementById('fee-modal-title');
                    const submitLabel = document.getElementById('fee-submit-label');
                    const submitBtn = document.getElementById('fee-submit');

                    const fields = ['fee_title','fee_description','fee_amount','fee_category','fee_term','fee_session'];
                    const errMap = {title:'fee_title',description:'fee_description',amount:'fee_amount',category:'fee_category',term:'fee_term',session:'fee_session',is_active:'fee_is_active'};

                    function clearErrors() {
                        if (typeof clearFieldErrors === 'function') clearFieldErrors(fields);
                        fields.forEach(function(id) {
                            const el = document.getElementById(id + '-error');
                            if (el) { el.textContent = ''; el.classList.add('hidden'); }
                        });
                    }
                    function mapErrors(errors) {
                        if (typeof showLaravelErrors === 'function') showLaravelErrors(errors, errMap);
                        else if (errors && typeof errors === 'object') {
                            Object.keys(errors).forEach(function(k) {
                                const fid = errMap[k] || k;
                                const p = document.getElementById(fid + '-error');
                                if (p) {
                                    p.textContent = Array.isArray(errors[k]) ? errors[k][0] : errors[k];
                                    p.classList.remove('hidden');
                                }
                            });
                        }
                    }
                    function openCreate() {
                        if (!form || !modal) return;
                        clearErrors();
                        titleEl.textContent = 'Add fee';
                        submitLabel.textContent = 'Save';
                        form.action = storeUrl;
                        if (methodInput) { methodInput.value = ''; methodInput.name = ''; }
                        document.getElementById('fee_title').value = '';
                        document.getElementById('fee_description').value = '';
                        document.getElementById('fee_amount').value = '';
                        document.getElementById('fee_category').selectedIndex = 0;
                        document.getElementById('fee_is_active').checked = true;
                        if (filterTerm) document.getElementById('fee_term').value = filterTerm;
                        if (filterSession) document.getElementById('fee_session').value = filterSession;
                        modal.classList.remove('hidden');
                    }
                    function openEdit(btn) {
                        if (!form || !modal) return;
                        clearErrors();
                        const p = parsePayloadB64(btn);
                        titleEl.textContent = 'Edit fee';
                        submitLabel.textContent = 'Update';
                        const id = p.id;
                        form.action = storeUrl.replace(/\/?$/, '') + '/' + encodeURIComponent(id);
                        if (methodInput) { methodInput.name = '_method'; methodInput.value = 'PUT'; }
                        document.getElementById('fee_title').value = p.title || '';
                        document.getElementById('fee_description').value = p.description || '';
                        document.getElementById('fee_amount').value = p.amount || '';
                        document.getElementById('fee_category').value = p.category || 'general';
                        document.getElementById('fee_term').value = p.term || '';
                        document.getElementById('fee_session').value = p.session || '';
                        document.getElementById('fee_is_active').checked = p.active === '1' || p.active === 1 || p.active === true;
                        modal.classList.remove('hidden');
                    }
                    function closeModal() { if (modal) modal.classList.add('hidden'); }

                    document.querySelectorAll('#fee-open-create, .fee-open-create').forEach(function(el) {
                        el.addEventListener('click', function(e) { e.preventDefault(); openCreate(); });
                    });
                    document.querySelectorAll('.fee-open-edit').forEach(function(el) {
                        el.addEventListener('click', function(e) { e.preventDefault(); openEdit(this); });
                    });
                    document.querySelectorAll('[data-close="fee-modal"]').forEach(function(el) {
                        el.addEventListener('click', closeModal);
                    });

                    if (form && submitBtn) {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            clearErrors();
                            const fd = new FormData(form);
                            const isPut = methodInput && methodInput.value === 'PUT';
                            if (!isPut && methodInput && !methodInput.value) { fd.delete('_method'); }
                            fd.delete('is_active');
                            fd.append('is_active', document.getElementById('fee_is_active').checked ? '1' : '0');
                            setButtonLoading(submitBtn, true);
                            fetch(form.action, {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                body: new URLSearchParams(fd)
                            })
                            .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, status: r.status, data: data }; }); })
                            .then(function(res) {
                                if (res.ok && res.data.status === 'success') {
                                    closeModal();
                                    flashSuccess(res.data.message || 'Saved.');
                                    setTimeout(function() { window.location.reload(); }, 600);
                                } else if (res.data && res.data.errors) {
                                    mapErrors(res.data.errors);
                                } else {
                                    flashError(res.data && res.data.message ? res.data.message : 'Could not save.');
                                }
                            })
                            .catch(function() { flashError('An error occurred. Please try again.'); })
                            .finally(function() { setButtonLoading(submitBtn, false); });
                        });
                    }

                    const delModal = document.getElementById('fee-delete-modal');
                    const delMsg = document.getElementById('fee-delete-message');
                    const delConfirm = document.getElementById('fee-delete-confirm');
                    let destroyUrl = null;

                    document.querySelectorAll('.fee-open-delete').forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            destroyUrl = btn.getAttribute('data-destroy-url');
                            const name = btn.getAttribute('data-title') || 'this fee';
                            delMsg.textContent = 'Delete "' + name + '"? This cannot be undone.';
                            delModal.classList.remove('hidden');
                        });
                    });
                    document.querySelectorAll('[data-close="fee-delete-modal"]').forEach(function(el) {
                        el.addEventListener('click', function() { delModal.classList.add('hidden'); destroyUrl = null; });
                    });
                    if (delConfirm) {
                        delConfirm.addEventListener('click', function() {
                            if (!destroyUrl) return;
                            setButtonLoading(delConfirm, true);
                            fetch(destroyUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: new URLSearchParams({ _token: csrf, _method: 'DELETE' })
                            })
                            .then(function(r) { return r.json().then(function(data) { return { ok: r.ok, data: data }; }); })
                            .then(function(res) {
                                delModal.classList.add('hidden');
                                if (res.ok && res.data.status === 'success') {
                                    flashSuccess(res.data.message || 'Deleted.');
                                    setTimeout(function() { window.location.reload(); }, 600);
                                } else {
                                    flashError(res.data && res.data.message ? res.data.message : 'Could not delete.');
                                }
                            })
                            .catch(function() { flashError('An error occurred.'); })
                            .finally(function() { setButtonLoading(delConfirm, false); destroyUrl = null; });
                        });
                    }
                })();
            </script>
        @endauth
    @endpush
@endsection
