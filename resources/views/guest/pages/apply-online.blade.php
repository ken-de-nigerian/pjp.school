@extends('layouts.guest', ['title' => $title])

@section('content')
    <main id="main-content">
        <div class="min-h-screen bg-educave-50 font-sans selection:bg-educave-900 selection:text-white pt-20 pb-20">
            <div class="container mx-auto px-4 md:px-8 lg:px-16 max-w-5xl">
                <div class="mb-12">
                    <a href="{{ route('admin_process') }}" class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-educave-900 transition-colors mb-6 w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        Back to Admission Requirements
                    </a>
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-educave-900 mb-3 inline-block border-b-2 border-pjp-gold-500/90 pb-1">Application for Admission</h1>
                    <div class="guest-crest-divider guest-crest-divider--short max-w-xs ml-0 mr-auto opacity-80 my-5" aria-hidden="true"></div>
                    <p class="text-neutral-500 flex flex-wrap items-center gap-2">
                        <span class="guest-crest-badge">Academic year</span>
                        <span class="text-sm font-medium text-educave-900">{{ $settings['session'] }}</span>
                    </p>
                </div>

                <div class="flex flex-col lg:flex-row gap-12">

                    {{-- Step Sidebar --}}
                    <div class="w-full lg:w-1/4">
                        <div class="sticky top-32">
                            <div class="space-y-0 relative" id="step-nav">
                                <div class="absolute left-3 top-4 bottom-4 w-px bg-gradient-to-b from-pjp-gold-400/70 via-pjp-torch/50 to-educave-200 -z-10"></div>
                                @php
                                    $stepLabels = ['Personal Info', 'Education & Health', 'Family Info', 'Review'];
                                @endphp
                                @foreach ($stepLabels as $i => $label)
                                    <div class="flex items-center gap-4 py-4 step-nav-item {{ $i === 0 ? 'opacity-100' : 'opacity-40' }}" data-step="{{ $i + 1 }}">
                                        <div class="step-circle w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-colors {{ $i === 0 ? 'bg-educave-900 border-educave-900 text-white' : 'bg-white border-gray-300 text-gray-400' }}">
                                            {{ $i + 1 }}
                                        </div>
                                        <span class="text-sm font-bold uppercase tracking-widest {{ $i === 0 ? 'text-educave-900' : 'text-gray-400' }}">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Form --}}
                    <div class="w-full lg:w-3/4">
                        <form id="admission-form" action="{{ route('apply_online.store') }}" method="POST" novalidate>
                            @csrf

                            {{-- ── STEP 1: Personal Information ── --}}
                            <div class="form-step bg-white p-8 md:p-12 shadow-sm border border-gray-100" data-step="1">
                                <h2 class="text-2xl font-serif font-bold text-educave-900 mb-8">Personal Information</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                    <div class="field-group">
                                        <label for="surname" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Surname <span class="text-educave-800">*</span></label>
                                        <input id="surname" name="surname" type="text" value="{{ old('surname') }}" required class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label for="firstname" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">First Name <span class="text-educave-800">*</span></label>
                                        <input id="firstname" name="firstname" type="text" value="{{ old('firstname') }}" required class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group md:col-span-2">
                                        <label for="middlename" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Middle Name <span class="text-gray-300">(optional)</span></label>
                                        <input id="middlename" name="middlename" type="text" value="{{ old('middlename') }}" class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                    </div>

                                    <div class="field-group">
                                        <label for="dob" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Date of Birth <span class="text-educave-800">*</span></label>
                                        <input id="dob" name="dob" type="date" value="{{ old('dob') }}" required class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label for="place_of_birth" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Place of Birth <span class="text-educave-800">*</span></label>
                                        <input id="place_of_birth" name="place_of_birth" type="text" value="{{ old('place_of_birth') }}" required class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label for="gender" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Gender <span class="text-educave-800">*</span></label>
                                        <select id="gender" name="gender" required class="field-input w-full border-b border-gray-300 py-2 focus:border-educave-900 focus:outline-none transition-colors bg-transparent appearance-none">
                                            <option value="">— Select —</option>
                                            <option value="Male"   {{ old('gender') === 'Male'   ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label for="country" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Country <span class="text-educave-800">*</span></label>
                                        <input id="country" name="country" type="text" value="{{ old('country', 'Nigeria') }}" required class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label for="state" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">State <span class="text-educave-800">*</span></label>
                                        <select id="state" name="state" required class="field-input w-full border-b border-gray-300 py-2 focus:border-educave-900 focus:outline-none transition-colors bg-transparent appearance-none">
                                            <option value="">— Select State —</option>
                                            @foreach(['Abia','Adamawa','Akwa Ibom','Anambra','Bauchi','Bayelsa','Benue','Borno','Cross River','Delta','Ebonyi','Edo','Ekiti','Enugu','Gombe','Imo','Jigawa','Kaduna','Kano','Katsina','Kebbi','Kogi','Kwara','Lagos','Nasarawa','Niger','Ogun','Ondo','Osun','Oyo','Plateau','Rivers','Sokoto','Taraba','Yobe','Zamfara','FCT'] as $state)
                                                <option value="{{ $state }}" {{ old('state') === $state ? 'selected' : '' }}>{{ $state }}</option>
                                            @endforeach
                                        </select>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label for="lga" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">L.G.A <span class="text-educave-800">*</span></label>
                                        <input id="lga" name="lga" type="text" value="{{ old('lga') }}" required class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label for="town" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Town <span class="text-educave-800">*</span></label>
                                        <input id="town" name="town" type="text" value="{{ old('town') }}" required class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label for="village" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Village</label>
                                        <input id="village" name="village" type="text" value="{{ old('village') }}" class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                    </div>

                                </div>

                                <div class="mt-12 flex justify-end pt-8 border-t border-gray-100">
                                    <button type="button" class="btn-next rounded-xl bg-educave-900 text-white px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-educave-700 transition-colors flex items-center gap-2" data-next="2">
                                        Save & Continue
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- ── STEP 2: Education & Health ── --}}
                            <div class="form-step hidden bg-white p-8 md:p-12 shadow-sm border border-gray-100" data-step="2">
                                <h2 class="text-2xl font-serif font-bold text-educave-900 mb-8">Education & Health</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                    <div class="field-group">
                                        <label for="current_school" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Current School <span class="text-educave-800">*</span></label>
                                        <input id="current_school" name="current_school" type="text" value="{{ old('current_school') }}" required class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label for="current_class" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Current Class <span class="text-educave-800">*</span></label>
                                        <input id="current_class" name="current_class" type="text" value="{{ old('current_class') }}" required placeholder="e.g. Primary 6 / JSS 3" class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors placeholder:text-gray-300"/>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label for="applying_for" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Applying For <span class="text-educave-800">*</span></label>
                                        <select id="applying_for" name="applying_for" required class="field-input w-full border-b border-gray-300 py-2 focus:border-educave-900 focus:outline-none transition-colors bg-transparent appearance-none">
                                            <option value="">— Select Class —</option>
                                            @foreach(['JSS 1', 'JSS 2', 'JSS 3', 'SSS 1'] as $c)
                                                <option value="{{ $c }}" {{ old('applying_for') === $c ? 'selected' : '' }}>{{ $c }}</option>
                                            @endforeach
                                        </select>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-4">First School Leaving Certificate <span class="text-educave-800">*</span></label>
                                        <div class="flex gap-6">
                                            @foreach(['Yes', 'No'] as $opt)
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" name="has_leaving_cert" value="{{ $opt }}" {{ old('has_leaving_cert') === $opt ? 'checked' : '' }} required class="accent-educave-900"/>
                                                    <span class="text-sm font-bold text-gray-600">{{ $opt }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <p id="has_leaving_cert-error" style="display:none" class="field-error text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group">
                                        <label for="blood_group" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Blood Group <span class="text-educave-800">*</span></label>
                                        <select id="blood_group" name="blood_group" required class="field-input w-full border-b border-gray-300 py-2 focus:border-educave-900 focus:outline-none transition-colors bg-transparent appearance-none">
                                            <option value="">— Select —</option>
                                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                                <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                            @endforeach
                                        </select>
                                        <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                    </div>

                                    <div class="field-group md:col-span-2">
                                        <label for="disability" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Any Disability? <span class="text-gray-300">(optional)</span></label>
                                        <input id="disability" name="disability" type="text" value="{{ old('disability') }}" placeholder="Describe if applicable" class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors placeholder:text-gray-300"/>
                                    </div>

                                    <div class="field-group md:col-span-2">
                                        <label for="special_care" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Any illness requiring special care in school? <span class="text-gray-300">(optional)</span></label>
                                        <textarea id="special_care" name="special_care" rows="3" placeholder="Describe any condition the school should be aware of" class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors resize-none placeholder:text-gray-300 text-sm">{{ old('special_care') }}</textarea>
                                    </div>

                                </div>

                                <div class="mt-12 flex justify-between pt-8 border-t border-gray-100">
                                    <button type="button" class="btn-prev text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-educave-900 transition-colors flex items-center gap-2" data-prev="1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                                        Back
                                    </button>
                                    <button type="button" class="btn-next rounded-xl bg-educave-900 text-white px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-educave-700 transition-colors flex items-center gap-2" data-next="3">
                                        Save & Continue
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- ── STEP 3: Family Information ── --}}
                            <div class="form-step hidden bg-white p-8 md:p-12 shadow-sm border border-gray-100" data-step="3">
                                <h2 class="text-2xl font-serif font-bold text-educave-900 mb-8">Family Information</h2>

                                @php
                                    $familyMembers = [
                                        ['key' => 'father',   'label' => "Father's Information"],
                                        ['key' => 'mother',   'label' => "Mother's Information"],
                                        ['key' => 'guardian', 'label' => "Guardian's Information"],
                                    ]
                                @endphp

                                @foreach ($familyMembers as $member)
                                    <div class="mb-12">
                                        <h3 class="text-lg font-bold text-educave-900 uppercase tracking-widest mb-6 pb-2 border-b border-gray-100">{{ $member['label'] }}</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                            <div class="field-group">
                                                <label for="{{ $member['key'] }}_surname" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">
                                                    Surname @if($member['key'] !== 'guardian')<span class="text-educave-800">*</span>@else<span class="text-gray-300">(optional)</span>@endif
                                                </label>
                                                <input id="{{ $member['key'] }}_surname" name="{{ $member['key'] }}_surname" type="text" value="{{ old($member['key'] . '_surname') }}" {{ $member['key'] !== 'guardian' ? 'required' : '' }} class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                                <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                            </div>

                                            <div class="field-group">
                                                <label for="{{ $member['key'] }}_firstname" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">
                                                    First Name @if($member['key'] !== 'guardian')<span class="text-educave-800">*</span>@else<span class="text-gray-300">(optional)</span>@endif
                                                </label>
                                                <input id="{{ $member['key'] }}_firstname" name="{{ $member['key'] }}_firstname" type="text" value="{{ old($member['key'] . '_firstname') }}" {{ $member['key'] !== 'guardian' ? 'required' : '' }} class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                                <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                            </div>

                                            <div class="field-group">
                                                <label for="{{ $member['key'] }}_middlename" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Middle Name <span class="text-gray-300">(optional)</span></label>
                                                <input id="{{ $member['key'] }}_middlename" name="{{ $member['key'] }}_middlename" type="text" value="{{ old($member['key'] . '_middlename') }}" class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                            </div>

                                            <div class="field-group">
                                                <label for="{{ $member['key'] }}_occupation" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">
                                                    Occupation @if($member['key'] !== 'guardian')<span class="text-educave-800">*</span>@else<span class="text-gray-300">(optional)</span>@endif
                                                </label>
                                                <input id="{{ $member['key'] }}_occupation" name="{{ $member['key'] }}_occupation" type="text" value="{{ old($member['key'] . '_occupation') }}" {{ $member['key'] !== 'guardian' ? 'required' : '' }} class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                                <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                            </div>

                                            <div class="field-group md:col-span-2">
                                                <label for="{{ $member['key'] }}_address" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">
                                                    Address @if($member['key'] !== 'guardian')<span class="text-educave-800">*</span>@else<span class="text-gray-300">(optional)</span>@endif
                                                </label>
                                                <input id="{{ $member['key'] }}_address" name="{{ $member['key'] }}_address" type="text" value="{{ old($member['key'] . '_address') }}" {{ $member['key'] !== 'guardian' ? 'required' : '' }} class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors"/>
                                                <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                            </div>

                                            <div class="field-group md:col-span-2">
                                                <label for="{{ $member['key'] }}_phone" class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">
                                                    Phone Number @if($member['key'] !== 'guardian')<span class="text-educave-800">*</span>@else<span class="text-gray-300">(optional)</span>@endif
                                                </label>
                                                <input id="{{ $member['key'] }}_phone" name="{{ $member['key'] }}_phone" type="tel" value="{{ old($member['key'] . '_phone') }}" {{ $member['key'] !== 'guardian' ? 'required' : '' }} placeholder="e.g. 08012345678" class="field-input w-full border-b py-2 focus:outline-none bg-transparent border-gray-300 focus:border-educave-900 transition-colors placeholder:text-gray-300"/>
                                                <p class="field-error hidden text-xs text-red-600 mt-1"></p>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach

                                <div class="flex justify-between pt-8 border-t border-gray-100">
                                    <button type="button" class="btn-prev text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-educave-900 transition-colors flex items-center gap-2" data-prev="2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                                        Back
                                    </button>
                                    <button type="button" class="btn-next rounded-xl bg-educave-900 text-white px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-educave-700 transition-colors flex items-center gap-2" data-next="4">
                                        Save & Continue
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- ── STEP 4: Review & Submit ── --}}
                            <div class="form-step hidden bg-white p-8 md:p-12 shadow-sm border border-gray-100" data-step="4">
                                <h2 class="text-2xl font-serif font-bold text-educave-900 mb-4">Review & Submit</h2>
                                <p class="text-neutral-500 text-sm mb-10 leading-relaxed">Please review your information before submitting. Once submitted, your application will be reviewed by the admissions office and you will be contacted with next steps.</p>

                                <div id="review-output" class="space-y-8 text-sm text-gray-700"></div>

                                <div class="mt-15 p-6 bg-educave-50 border border-educave-800/20">
                                    <p class="text-xs font-bold uppercase tracking-widest text-educave-800 mb-2">Declaration</p>
                                    <p class="text-gray-600 text-sm leading-relaxed mb-4">I confirm that the information provided in this application is true and accurate to the best of my knowledge. I understand that any false information may result in the cancellation of my application or admission.</p>
                                    <label class="flex items-start gap-3 cursor-pointer group">
                                        <input type="checkbox" id="declaration" name="declaration" value="1" {{ old('declaration') ? 'checked' : '' }} required class="mt-1 accent-educave-900 shrink-0"/>
                                        <span class="text-sm text-gray-600 group-hover:text-educave-900 transition-colors">I agree to the above declaration and consent to PJP processing this information for admissions purposes.</span>
                                    </label>
                                    <p class="field-error hidden text-xs text-red-600 mt-2" id="declaration-error"></p>
                                </div>

                                <div class="mt-12 flex justify-between pt-8 border-t border-gray-100">
                                    <button type="button" class="btn-prev text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-educave-900 transition-colors flex items-center gap-2" data-prev="3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                                        Back
                                    </button>

                                    <button type="submit" id="submit-btn" class="rounded-xl bg-educave-900 text-white px-8 py-4 text-xs font-bold uppercase tracking-widest hover:bg-educave-700 transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Submit Application
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        (function () {

            // ── Helpers ──────────────────────────────────────────────────────────────
            const $ = (sel, ctx = document) => ctx.querySelector(sel);
            const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

            function showError(input, message) {
                const group = input.closest('.field-group');
                if (!group) return;
                const err = group.querySelector('.field-error');
                input.classList.add('border-red-400');
                input.classList.remove('border-gray-300', 'focus:border-educave-900');
                if (err) { err.textContent = message; err.classList.remove('hidden'); }
            }

            function clearError(input) {
                const group = input.closest('.field-group');
                if (!group) return;
                const err = group.querySelector('.field-error');
                input.classList.remove('border-red-400');
                input.classList.add('border-gray-300');
                if (err) { err.textContent = ''; err.classList.add('hidden'); }
            }

            function validateField(input) {
                const val = input.value.trim();
                const type = input.type;

                if (input.required && !val) {
                    showError(input, 'This field is required.');
                    return false;
                }
                if (type === 'date' && val) {
                    const dob = new Date(val);
                    const today = new Date();
                    if (dob >= today) { showError(input, 'Please enter a valid date of birth.'); return false; }
                    const age = today.getFullYear() - dob.getFullYear();
                    if (age > 25) { showError(input, 'Please check the date of birth entered.'); return false; }
                }
                if (type === 'tel' && val && !/^[0-9+\s\-()]{7,20}$/.test(val)) {
                    showError(input, 'Please enter a valid phone number.');
                    return false;
                }
                clearError(input);
                return true;
            }

            // ── Step Validation ───────────────────────────────────────────────────────
            function validateStep(stepEl) {
                let valid = true;

                // Text / select / date / tel / email inputs
                $$('.field-input', stepEl).forEach(function (input) {
                    if (!validateField(input)) valid = false;
                });

                // Radio groups
                const radioGroups = {};
                $$('input[type="radio"]', stepEl).forEach(function (r) {
                    radioGroups[r.name] = radioGroups[r.name] || [];
                    radioGroups[r.name].push(r);
                });
                Object.entries(radioGroups).forEach(function ([name, radios]) {
                    const checked = radios.some(r => r.checked);
                    const errEl = document.getElementById(name + '-error');
                    if (!checked) {
                        valid = false;
                        if (errEl) { errEl.textContent = 'Please select an option.'; errEl.style.display = 'block'; }
                    } else {
                        if (errEl) { errEl.textContent = ''; errEl.style.display = 'none'; }
                    }
                });

                // Declaration checkbox (step 4)
                const declaration = $('#declaration', stepEl);
                if (declaration && !declaration.checked) {
                    const err = $('#declaration-error');
                    if (err) { err.textContent = 'You must agree to the declaration before submitting.'; err.classList.remove('hidden'); }
                    valid = false;
                }

                return valid;
            }

            // ── Inline error-clearing on input ───────────────────────────────────────
            document.addEventListener('input', function (e) {
                if (e.target.matches('.field-input')) validateField(e.target);
            });
            document.addEventListener('change', function (e) {
                if (e.target.matches('.field-input')) validateField(e.target);
                if (e.target.matches('input[type="radio"]')) {
                    const errEl = document.getElementById(e.target.name + '-error');
                    if (errEl) { errEl.textContent = ''; errEl.style.display = 'none'; }
                }
                if (e.target.id === 'declaration') {
                    const err = $('#declaration-error');
                    if (err) { err.textContent = ''; err.classList.add('hidden'); }
                }
            });

            // ── Step Navigation ───────────────────────────────────────────────────────
            // Sidebar `.step-nav-item` also uses data-step; only `.form-step` holds the form panels.
            function formStepEl(step) {
                return document.querySelector('.form-step[data-step="' + step + '"]');
            }

            let currentStep = 1;

            function goTo(step) {
                $$('.form-step').forEach(el => el.classList.add('hidden'));
                const target = formStepEl(step);
                if (target) target.classList.remove('hidden');

                // Update sidebar
                $$('.step-nav-item').forEach(function (item, i) {
                    const n = i + 1;
                    const circle = item.querySelector('.step-circle');
                    if (n < step) {
                        item.classList.remove('opacity-40');
                        item.classList.add('opacity-100');
                        circle.className = 'step-circle w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-colors bg-educave-500 border-educave-500 text-white';
                        circle.innerHTML = '✓';
                    } else if (n === step) {
                        item.classList.remove('opacity-40');
                        item.classList.add('opacity-100');
                        circle.className = 'step-circle w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-colors bg-educave-900 border-educave-900 text-white';
                        circle.textContent = n;
                    } else {
                        item.classList.remove('opacity-100');
                        item.classList.add('opacity-40');
                        circle.className = 'step-circle w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-colors bg-white border-gray-300 text-gray-400';
                        circle.textContent = n;
                    }
                });

                currentStep = step;
                window.scrollTo({ top: 0, behavior: 'smooth' });

                if (step === 4) buildReview();
            }

            document.addEventListener('click', function (e) {
                const nextBtn = e.target.closest('.btn-next');
                if (nextBtn) {
                    const currentStepEl = formStepEl(currentStep);
                    if (currentStepEl && validateStep(currentStepEl)) {
                        goTo(parseInt(nextBtn.dataset.next, 10));
                    }
                    return;
                }
                const prevBtn = e.target.closest('.btn-prev');
                if (prevBtn) goTo(parseInt(prevBtn.dataset.prev));
            });

            // ── Review Builder ────────────────────────────────────────────────────────
            function buildReview() {
                const out = $('#review-output');
                if (!out) return;

                function val(id) {
                    const el = $('#' + id);
                    if (!el) return '—';
                    if (el.type === 'radio') {
                        const checked = document.querySelector('input[name="' + el.name + '"]:checked');
                        return checked ? checked.value : '—';
                    }
                    return el.value.trim() || '—';
                }

                function radVal(name) {
                    const checked = document.querySelector('input[name="' + name + '"]:checked');
                    return checked ? checked.value : '—';
                }

                function section(title, rows) {
                    return `<div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-educave-800 mb-4 pb-2 border-b border-gray-100">${title}</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    ${rows.map(([label, v]) => `
                        <div>
                            <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">${label}</dt>
                            <dd class="text-sm font-medium text-educave-900">${v}</dd>
                        </div>`).join('')}
                </dl>
            </div>`;
                }

                out.innerHTML = [
                    section('Personal Information', [
                        ['Surname',         val('surname')],
                        ['First Name',      val('firstname')],
                        ['Middle Name',     val('middlename')],
                        ['Date of Birth',   val('dob')],
                        ['Place of Birth',  val('place_of_birth')],
                        ['Gender',          val('gender')],
                        ['Country',         val('country')],
                        ['State',           val('state')],
                        ['L.G.A',           val('lga')],
                        ['Town',            val('town')],
                        ['Village',         val('village')],
                    ]),
                    section('Education & Health', [
                        ['Current School',          val('current_school')],
                        ['Current Class',           val('current_class')],
                        ['Applying For',            val('applying_for')],
                        ['Leaving Certificate',     radVal('has_leaving_cert')],
                        ['Blood Group',             val('blood_group')],
                        ['Disability',              val('disability') || 'None'],
                        ['Special Care Needed',     val('special_care') || 'None'],
                    ]),
                    section("Father's Information", [
                        ['Surname',      val('father_surname')],
                        ['First Name',   val('father_firstname')],
                        ['Middle Name',  val('father_middlename')],
                        ['Occupation',   val('father_occupation')],
                        ['Phone',        val('father_phone')],
                        ['Address',      val('father_address')],
                    ]),
                    section("Mother's Information", [
                        ['Surname',      val('mother_surname')],
                        ['First Name',   val('mother_firstname')],
                        ['Middle Name',  val('mother_middlename')],
                        ['Occupation',   val('mother_occupation')],
                        ['Phone',        val('mother_phone')],
                        ['Address',      val('mother_address')],
                    ]),
                    section("Guardian's Information", [
                        ['Surname',      val('guardian_surname')],
                        ['First Name',   val('guardian_firstname')],
                        ['Middle Name',  val('guardian_middlename')],
                        ['Occupation',   val('guardian_occupation')],
                        ['Phone',        val('guardian_phone')],
                        ['Address',      val('guardian_address')],
                    ]),
                ].join('');
            }

            // ── Submit: re-validate all steps, then POST (native submit avoids re-entering this listener) ──
            const admissionForm = document.getElementById('admission-form');
            if (admissionForm) {
                admissionForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    for (let s = 1; s <= 4; s++) {
                        const stepEl = document.querySelector('.form-step[data-step="' + s + '"]');
                        if (!stepEl || !validateStep(stepEl)) {
                            goTo(s);
                            const firstBad = stepEl.querySelector('.border-red-400');
                            const firstMsg = stepEl.querySelector('.field-error:not(.hidden), #declaration-error:not(.hidden)');
                            const target = firstBad || firstMsg;
                            if (target) {
                                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                if (firstBad && typeof firstBad.focus === 'function') firstBad.focus();
                            } else {
                                stepEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                            return;
                        }
                    }
                    const submitBtn = document.getElementById('submit-btn');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.setAttribute('aria-busy', 'true');
                    }
                    HTMLFormElement.prototype.submit.call(admissionForm);
                });
            }

        })();
    </script>
@endpush
