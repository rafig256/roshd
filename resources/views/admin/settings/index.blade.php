@extends('admin.layouts.app')

@section('title', 'تنظیمات سایت')
@section('page-title', 'تنظیمات سایت')

@section('content')
    <div class="max-w-3xl mx-auto">

        {{-- Page header --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-800">تنظیمات سایت</h2>
            <p class="mt-1 text-sm text-slate-500">اطلاعات عمومی و راه‌های ارتباطی سایت را از اینجا مدیریت کنید.</p>
        </div>

        {{-- Success message --}}
        @if (session('success'))
            <div class="mb-6 flex items-center gap-2 px-4 py-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Section 1: General settings --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 3a9 9 0 100 18 9 9 0 000-18zM3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/>
                        </svg>
                    </span>
                    <h3 class="text-base font-semibold text-slate-800">تنظیمات عمومی</h3>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Site name --}}
                    <div>
                        <label for="site_name" class="block mb-2 text-sm font-medium text-slate-700">نام سایت</label>
                        <input type="text"
                               id="site_name"
                               name="site_name"
                               value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                               class="w-full px-4 py-2.5 text-sm rounded-lg border bg-slate-50
                                      focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                      @error('site_name') border-red-400 @else border-slate-300 @enderror">
                        @error('site_name')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Site version --}}
                    <div>
                        <label for="site_version" class="block mb-2 text-sm font-medium text-slate-700">نسخه سایت</label>
                        <input type="text"
                               id="site_version"
                               name="site_version"
                               value="{{ old('site_version', $settings['site_version'] ?? '') }}"
                               dir="ltr"
                               class="w-full px-4 py-2.5 text-sm text-left rounded-lg border bg-slate-50
                                      focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                      @error('site_version') border-red-400 @else border-slate-300 @enderror">
                        @error('site_version')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Contact info --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M3 5a2 2 0 012-2h3.3a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.2l-2.26 1.13a11 11 0 005.52 5.52l1.13-2.26a1 1 0 011.2-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/>
                        </svg>
                    </span>
                    <h3 class="text-base font-semibold text-slate-800">اطلاعات تماس</h3>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Email --}}
                    <div>
                        <label for="contact_email" class="block mb-2 text-sm font-medium text-slate-700">ایمیل</label>
                        <input type="text"
                               id="contact_email"
                               name="contact_email"
                               value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                               dir="ltr"
                               inputmode="email"
                               class="w-full px-4 py-2.5 text-sm text-left rounded-lg border bg-slate-50
                                      focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                      @error('contact_email') border-red-400 @else border-slate-300 @enderror">
                        @error('contact_email')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="contact_phone" class="block mb-2 text-sm font-medium text-slate-700">تلفن</label>
                        <input type="text"
                               id="contact_phone"
                               name="contact_phone"
                               value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                               dir="ltr"
                               inputmode="tel"
                               class="w-full px-4 py-2.5 text-sm text-left rounded-lg border bg-slate-50
                                      focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                      @error('contact_phone') border-red-400 @else border-slate-300 @enderror">
                        @error('contact_phone')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Public Pages --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2zM7 8h6M7 12h10M7 16h10"/>
                        </svg>
                    </span>
                    <h3 class="text-base font-semibold text-slate-800">صفحات عمومی</h3>
                </div>

                <div class="p-6 space-y-5">
                    {{-- About Us --}}
                    <div>
                        <label for="about_us" class="block mb-2 text-sm font-medium text-slate-700">درباره ما</label>
                        <textarea id="about_us"
                                  name="about_us"
                                  rows="6"
                                  class="w-full px-4 py-3 text-sm leading-7 rounded-lg border bg-slate-50 placeholder:text-slate-400 resize-y
                                         focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition
                                         @error('about_us') border-red-400 @else border-slate-300 @enderror">{{ old('about_us', $settings['about_us'] ?? '') }}</textarea>
                        @error('about_us')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-slate-400">متن صفحه «درباره ما» که در سایت عمومی نمایش داده می‌شود.</p>
                    </div>

                    {{-- Contact Us --}}
                    <div>
                        <label for="contact_us" class="block mb-2 text-sm font-medium text-slate-700">تماس با ما</label>
                        <textarea id="contact_us"
                                  name="contact_us"
                                  rows="6"
                                  class="w-full px-4 py-3 text-sm leading-7 rounded-lg border bg-slate-50 placeholder:text-slate-400 resize-y
                                         focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition
                                         @error('contact_us') border-red-400 @else border-slate-300 @enderror">{{ old('contact_us', $settings['contact_us'] ?? '') }}</textarea>
                        @error('contact_us')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-slate-400">متن صفحه «تماس با ما» که در سایت عمومی نمایش داده می‌شود.</p>
                    </div>
                </div>
            </div>

            {{-- Section 4: Contact Details (enhanced) --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11zM12 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/>
                        </svg>
                    </span>
                    <h3 class="text-base font-semibold text-slate-800">آدرس</h3>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Address --}}
                    <div class="md:col-span-2">
                        <label for="contact_address" class="block mb-2 text-sm font-medium text-slate-700">آدرس</label>
                        <textarea id="contact_address"
                                  name="contact_address"
                                  rows="3"
                                  class="w-full px-4 py-3 text-sm leading-7 rounded-lg border bg-slate-50 placeholder:text-slate-400 resize-y
                                         focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition
                                         @error('contact_address') border-red-400 @else border-slate-300 @enderror">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                        @error('contact_address')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-slate-400">آدرس فیزیکی یا دفتر مرکزی.</p>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg
                               hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>
@endsection