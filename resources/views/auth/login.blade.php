@extends('layouts.public')

@section('title', 'ورود')

@section('content')
<section class="relative overflow-hidden min-h-[calc(100vh-5rem)] flex items-center justify-center px-4 py-12
                bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-600/40 blur-3xl"></div>
    <div class="pointer-events-none absolute top-1/3 -left-24 w-80 h-80 rounded-full bg-fuchsia-500/30 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 right-1/3 w-96 h-96 rounded-full bg-cyan-500/20 blur-3xl"></div>

    {{-- Glass card --}}
    <div class="relative w-full max-w-md">
        <div class="rounded-3xl border border-white/15 bg-white/10 backdrop-blur-xl shadow-2xl shadow-black/30 p-6 sm:p-10">

            {{-- Heading --}}
            <div class="text-center mb-8">
                <span class="mx-auto mb-4 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center text-white shadow-lg shadow-indigo-900/40">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M16 11V7a4 4 0 10-8 0v4M6 11h12a1 1 0 011 1v8a1 1 0 01-1 1H6a1 1 0 01-1-1v-8a1 1 0 011-1z"/>
                    </svg>
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white">ورود به حساب کاربری</h1>
                <p class="mt-2 text-sm text-slate-300">برای ادامه، کد ملی و رمز عبور خود را وارد کنید.</p>
            </div>

            {{-- Session status (e.g. after registration or password reset) --}}
            @if (session('status'))
                <div class="mb-5 px-4 py-3 text-sm text-emerald-200 bg-emerald-500/15 border border-emerald-400/30 rounded-xl">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
                @csrf

                {{-- National code --}}
                <div>
                    <label for="national_code" class="block mb-2 text-sm font-medium text-slate-200">کد ملی</label>
                    <input type="text" id="national_code" name="national_code" value="{{ old('national_code') }}"
                           dir="ltr" inputmode="numeric" maxlength="10" autocomplete="username"
                           placeholder="کد ملی ۱۰ رقمی" autofocus
                           class="w-full px-4 py-3 text-sm text-left text-white placeholder:text-right placeholder:text-slate-400 rounded-xl bg-white/10 border
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white/15 transition
                                  @error('national_code') border-red-400 @else border-white/20 @enderror">
                    @error('national_code')
                        <p class="mt-1.5 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-slate-200">رمز عبور</label>
                    <input type="password" id="password" name="password"
                           dir="ltr" autocomplete="current-password" placeholder="رمز عبور خود را وارد کنید"
                           class="w-full px-4 py-3 text-sm text-left text-white placeholder:text-right placeholder:text-slate-400 rounded-xl bg-white/10 border
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white/15 transition
                                  @error('password') border-red-400 @else border-white/20 @enderror">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <label class="flex items-center gap-2 text-sm text-slate-300 cursor-pointer">
                    <input type="checkbox" name="remember"
                           class="w-4 h-4 rounded border-white/30 bg-white/10 text-indigo-500 focus:ring-indigo-400">
                    مرا به خاطر بسپار
                </label>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3.5 text-sm font-bold text-white rounded-xl bg-gradient-to-l from-indigo-500 to-fuchsia-500
                               shadow-lg shadow-indigo-900/40 hover:shadow-xl hover:-translate-y-0.5
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-indigo-400
                               transition-all">
                    ورود
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-300">
                حساب کاربری ندارید؟
                <a href="{{ route('register') }}"
                   class="font-semibold text-indigo-300 hover:text-white underline-offset-4 hover:underline transition-colors">
                    ثبت‌نام کنید
                </a>
            </p>
        </div>
    </div>
</section>
@endsection