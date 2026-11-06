@extends('layouts.public')

@section('title', 'درخواست تبدیل تیم به شرکت')

@section('content')
@php
    $companyTypes = [
        'private_joint_stock' => 'سهامی خاص',
        'public_joint_stock' => 'سهامی عام',
        'limited_liability'   => 'مسئولیت محدود',
        'general_partnership' => 'تضامنی',
        'other'               => 'دیگر',
    ];

    $inputBase = 'w-full px-4 py-3 text-sm rounded-xl border bg-slate-50 placeholder:text-slate-400
                  focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition';
    $inputLtr  = 'text-left placeholder:text-right';
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white min-h-[calc(100vh-5rem)]">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Back --}}
        <a href="{{ route('user.organizations.show', $organization->id) }}"
           class="inline-flex items-center gap-1.5 mb-6 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"/>
            </svg>
            بازگشت به مدیریت تیم
        </a>

        {{-- Page header --}}
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h2M13 7h2M9 11h2M13 11h2M10 21v-4h4v4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">درخواست تبدیل تیم به شرکت</h1>
                <p class="mt-2 text-sm sm:text-base leading-8 text-slate-600">
                    برای تبدیل تیم «{{ $organization->name }}» به شرکت رسمی، مشخصات ثبتی شرکت را وارد کنید.
                    پس از بررسی توسط کارشناسان، نتیجه از طریق پنل کاربری اعلام می‌شود.
                </p>
            </div>
        </div>

        {{-- Error summary --}}
        @if ($errors->any())
            <div class="mt-8 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl">
                لطفاً خطاهای مشخص‌شده در فرم را برطرف کنید و دوباره تلاش کنید.
            </div>
        @endif

        {{-- Form card --}}
        <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5">
            <form method="POST" action="{{ route('user.organizations.convert-to-company.store', $organization->id) }}"
                  class="p-6 sm:p-8 space-y-6" novalidate>
                @csrf

                {{-- Previous Rejection Notice --}}
                @if($organization->company_rejection_reason)
                    <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                        <p class="font-semibold text-red-800">درخواست قبلی شما برای تبدیل به شرکت رد شده بود.</p>
                        <p class="mt-1 text-sm text-red-700">دلیل رد: {{ $organization->company_rejection_reason }}</p>
                        <p class="mt-2 text-sm text-red-600">می‌توانید اطلاعات را اصلاح کرده و مجدداً ارسال کنید.</p>
                    </div>
                @endif

                {{-- Legal name --}}
                <div>
                    <label for="legal_name" class="block mb-2 text-sm font-medium text-slate-700">
                        نام رسمی شرکت <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="legal_name" name="legal_name" value="{{ old('legal_name') }}"
                           maxlength="255" placeholder="نام ثبت‌شده‌ی شرکت در اداره‌ی ثبت شرکت‌ها"
                           class="{{ $inputBase }} @error('legal_name') border-red-400 @else border-slate-300 @enderror">
                    @error('legal_name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- National ID --}}
                    <div>
                        <label for="national_id" class="block mb-2 text-sm font-medium text-slate-700">
                            شناسه ملی <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="national_id" name="national_id" value="{{ old('national_id') }}"
                               dir="ltr" inputmode="numeric" maxlength="11" placeholder="۱۱ رقم"
                               class="{{ $inputBase }} {{ $inputLtr }} @error('national_id') border-red-400 @else border-slate-300 @enderror">
                        @error('national_id')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Registration number --}}
                    <div>
                        <label for="registration_number" class="block mb-2 text-sm font-medium text-slate-700">
                            شماره ثبت <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="registration_number" name="registration_number" value="{{ old('registration_number') }}"
                               dir="ltr" inputmode="numeric" maxlength="50" placeholder="شماره ثبت شرکت"
                               class="{{ $inputBase }} {{ $inputLtr }} @error('registration_number') border-red-400 @else border-slate-300 @enderror">
                        @error('registration_number')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Economic code --}}
                    <div>
                        <label for="economic_code" class="block mb-2 text-sm font-medium text-slate-700">
                            کد اقتصادی <span class="text-xs font-normal text-slate-400">(اختیاری)</span>
                        </label>
                        <input type="text" id="economic_code" name="economic_code" value="{{ old('economic_code') }}"
                               dir="ltr" inputmode="numeric" maxlength="20" placeholder="کد اقتصادی"
                               class="{{ $inputBase }} {{ $inputLtr }} @error('economic_code') border-red-400 @else border-slate-300 @enderror">
                        @error('economic_code')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Established date (Persian datepicker) --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-slate-700">
                            تاریخ تأسیس <span class="text-red-500">*</span>
                        </label>

                        {{-- مقدار واقعی که همراه فرم ارسال می‌شود (YYYY/MM/DD با ارقام لاتین) --}}
                        <input type="hidden" id="established_date" name="established_date" value="{{ old('established_date') }}">

                        <div class="datepicker-wrap rounded-xl border bg-slate-50 transition
                                    focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500
                                    @error('established_date') border-red-400 @else border-slate-300 @enderror">
                            <persian-datepicker-element
                                id="established_date_picker"
                                placeholder="1403/01/01"
                                format="YYYY/MM/DD"
                                rtl
                                class="block w-full"></persian-datepicker-element>
                        </div>
                        @error('established_date')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Company type --}}
                <div>
                    <label for="company_type" class="block mb-2 text-sm font-medium text-slate-700">
                        نوع شرکت <span class="text-red-500">*</span>
                    </label>
                    <select id="company_type" name="company_type"
                            class="{{ $inputBase }} @error('company_type') border-red-400 @else border-slate-300 @enderror">
                        <option value="">یک نوع را انتخاب کنید</option>
                        @foreach ($companyTypes as $value => $label)
                            <option value="{{ $value }}" @selected(old('company_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('company_type')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Legal address --}}
                <div>
                    <label for="legal_address" class="block mb-2 text-sm font-medium text-slate-700">
                        آدرس حقوقی <span class="text-red-500">*</span>
                    </label>
                    <textarea id="legal_address" name="legal_address" rows="3"
                              placeholder="نشانی ثبت‌شده‌ی شرکت را کامل وارد کنید..."
                              class="{{ $inputBase }} leading-7 resize-y @error('legal_address') border-red-400 @else border-slate-300 @enderror">{{ old('legal_address') }}</textarea>
                    @error('legal_address')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block mb-2 text-sm font-medium text-slate-700">
                            تلفن <span class="text-xs font-normal text-slate-400">(اختیاری)</span>
                        </label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                               dir="ltr" inputmode="tel" maxlength="20" placeholder="02100000000"
                               class="{{ $inputBase }} {{ $inputLtr }} @error('phone') border-red-400 @else border-slate-300 @enderror">
                        @error('phone')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-slate-700">
                            ایمیل <span class="text-xs font-normal text-slate-400">(اختیاری)</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               dir="ltr" inputmode="email" maxlength="255" placeholder="info@example.com"
                               class="{{ $inputBase }} {{ $inputLtr }} @error('email') border-red-400 @else border-slate-300 @enderror">
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Website --}}
                <div>
                    <label for="website" class="block mb-2 text-sm font-medium text-slate-700">
                        وب‌سایت <span class="text-xs font-normal text-slate-400">(اختیاری)</span>
                    </label>
                    <input type="text" id="website" name="website" value="{{ old('website') }}"
                           dir="ltr" inputmode="url" maxlength="255" placeholder="https://example.com"
                           class="{{ $inputBase }} {{ $inputLtr }} @error('website') border-red-400 @else border-slate-300 @enderror">
                    @error('website')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                    <a href="{{ route('user.organizations.show', $organization->id) }}"
                       class="px-6 py-3 text-center text-sm font-semibold text-slate-600 rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                        انصراف
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3 text-sm font-bold text-white rounded-xl
                                   bg-gradient-to-l from-indigo-600 to-fuchsia-500 shadow-lg shadow-indigo-500/30
                                   hover:shadow-xl hover:-translate-y-0.5
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        ثبت درخواست تبدیل
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M19 12H5M11 6l-6 6 6 6"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    persian-datepicker-element { display: block; width: 100%; font-family: 'Vazirmatn', Tahoma, sans-serif; }
    .datepicker-wrap { padding: 0.25rem 0.5rem; }
</style>
@endpush

@push('scripts')
<script type="module" src="https://cdn.jsdelivr.net/npm/persian-datepicker-element@1.1.17/dist/persian-datepicker-element.min.js"></script>

<script>
    // Persian datepicker <-> hidden input sync
    (function () {
        const picker = document.getElementById('established_date_picker');
        const hidden = document.getElementById('established_date');
        if (!picker || !hidden) return;

        const pad = (n) => String(n).padStart(2, '0');
        const toLatin = (s) => String(s)
            .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
            .replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));

        picker.addEventListener('change', (e) => {
            const j = e.detail && e.detail.jalali;
            hidden.value = Array.isArray(j) && j.length === 3
                ? `${j[0]}/${pad(j[1])}/${pad(j[2])}`
                : '';
        });

        // بازگرداندن old('established_date') بعد از خطای اعتبارسنجی
        const m = toLatin(hidden.value || '').trim().match(/^(\d{4})\/(\d{1,2})\/(\d{1,2})$/);
        if (m) {
            customElements.whenDefined('persian-datepicker-element').then(() => {
                picker.setValue(+m[1], +m[2], +m[3]);
            });
        }
    })();
</script>
@endpush