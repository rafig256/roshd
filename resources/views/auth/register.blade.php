@extends('layouts.public')

@section('title', 'ثبت‌نام')

@section('content')
<section class="relative overflow-hidden min-h-[calc(100vh-5rem)] flex items-center justify-center px-4 py-12
                bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-600/40 blur-3xl"></div>
    <div class="pointer-events-none absolute top-1/3 -left-24 w-80 h-80 rounded-full bg-fuchsia-500/30 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 right-1/3 w-96 h-96 rounded-full bg-cyan-500/20 blur-3xl"></div>

    {{-- Glass card --}}
    <div class="relative w-full max-w-2xl">
        <div class="rounded-3xl border border-white/15 bg-white/10 backdrop-blur-xl shadow-2xl shadow-black/30 p-6 sm:p-10">

            {{-- Heading --}}
            <div class="text-center mb-8">
                <span class="inline-flex items-center gap-2 px-3 py-1 mb-4 text-xs font-medium text-indigo-200 bg-white/10 rounded-full border border-white/10">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    همین امروز مسیر استارتاپ خود را شروع کنید
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white">ساخت حساب کاربری</h1>
                <p class="mt-2 text-sm text-slate-300">برای عضویت در جامعه نوآوران، اطلاعات زیر را تکمیل کنید.</p>
            </div>

            <form method="POST" action="{{ route('register.submit') }}" class="space-y-5" novalidate>
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-slate-200">نام</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               autocomplete="given-name" placeholder="مثلاً علی"
                               class="w-full px-4 py-3 text-sm text-white placeholder:text-slate-400 rounded-xl bg-white/10 border
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white/15 transition
                                      @error('name') border-red-400 @else border-white/20 @enderror">
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Family --}}
                    <div>
                        <label for="family" class="block mb-2 text-sm font-medium text-slate-200">نام خانوادگی</label>
                        <input type="text" id="family" name="family" value="{{ old('family') }}"
                               autocomplete="family-name" placeholder="مثلاً رضایی"
                               class="w-full px-4 py-3 text-sm text-white placeholder:text-slate-400 rounded-xl bg-white/10 border
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white/15 transition
                                      @error('family') border-red-400 @else border-white/20 @enderror">
                        @error('family')
                            <p class="mt-1.5 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- National code --}}
                    <div>
                        <label for="national_code" class="block mb-2 text-sm font-medium text-slate-200">کد ملی</label>
                        <input type="text" id="national_code" name="national_code" value="{{ old('national_code') }}"
                               dir="ltr" inputmode="numeric" maxlength="10" placeholder="۱۰ رقم"
                               class="w-full px-4 py-3 text-sm text-left text-white placeholder:text-right placeholder:text-slate-400 rounded-xl bg-white/10 border
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white/15 transition
                                      @error('national_code') border-red-400 @else border-white/20 @enderror">
                        @error('national_code')
                            <p class="mt-1.5 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mobile --}}
                    <div>
                        <label for="mobile" class="block mb-2 text-sm font-medium text-slate-200">شماره موبایل</label>
                        <input type="text" id="mobile" name="mobile" value="{{ old('mobile') }}"
                               dir="ltr" inputmode="tel" maxlength="11" autocomplete="tel" placeholder="09123456789"
                               class="w-full px-4 py-3 text-sm text-left text-white placeholder:text-right placeholder:text-slate-400 rounded-xl bg-white/10 border
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white/15 transition
                                      @error('mobile') border-red-400 @else border-white/20 @enderror">
                        @error('mobile')
                            <p class="mt-1.5 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-slate-200">رمز عبور</label>
                        <input type="password" id="password" name="password"
                               dir="ltr" autocomplete="new-password" placeholder="حداقل ۸ کاراکتر"
                               class="w-full px-4 py-3 text-sm text-left text-white placeholder:text-right placeholder:text-slate-400 rounded-xl bg-white/10 border
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white/15 transition
                                      @error('password') border-red-400 @else border-white/20 @enderror">
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password confirmation --}}
                    <div>
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-slate-200">تکرار رمز عبور</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               dir="ltr" autocomplete="new-password" placeholder="رمز عبور را دوباره وارد کنید"
                               class="w-full px-4 py-3 text-sm text-left text-white placeholder:text-right placeholder:text-slate-400 rounded-xl bg-white/10 border
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white/15 transition
                                      @error('password_confirmation') border-red-400 @else border-white/20 @enderror">
                        @error('password_confirmation')
                            <p class="mt-1.5 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3.5 text-sm font-bold text-white rounded-xl bg-gradient-to-l from-indigo-500 to-fuchsia-500
                               shadow-lg shadow-indigo-900/40 hover:shadow-xl hover:-translate-y-0.5
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-indigo-400
                               transition-all">
                    ثبت‌نام
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-300">
                قبلاً ثبت‌نام کرده‌اید؟
                <a href="#" class="font-semibold text-indigo-300 hover:text-white underline-offset-4 hover:underline transition-colors">
                    وارد شوید
                </a>
            </p>
        </div>
    </div>
</section>
@endsection