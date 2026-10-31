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