@extends('layouts.public')

@section('title', 'ویرایش پروفایل')

@section('content')
@php
    $user  = auth()->user();
    $input = 'w-full px-4 py-3 text-sm rounded-xl border bg-slate-50 placeholder:text-slate-400
              focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition';
    $label = 'block mb-2 text-sm font-medium text-slate-700';
    $ltr   = 'text-left placeholder:text-right';
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white min-h-[calc(100vh-5rem)]">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Back --}}
        <a href="{{ route('user.dashboard') }}"
           class="inline-flex items-center gap-1.5 mb-6 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            بازگشت به پنل کاربری
        </a>

        {{-- Page header --}}
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0113-6.2M15 21l1-4 5-5a1.4 1.4 0 012 2l-5 5-3 1z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">ویرایش پروفایل</h1>
                <p class="mt-2 text-sm sm:text-base leading-8 text-slate-600">
                    اطلاعات شخصی و رمز عبور حساب خود را به‌روز نگه دارید.
                </p>
            </div>
        </div>

        {{-- Flash / error summary --}}
        @if (session('success'))
            <div class="mt-8 flex items-center gap-2 px-4 py-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mt-8 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl">
                لطفاً خطاهای مشخص‌شده در فرم را برطرف کنید و دوباره تلاش کنید.
            </div>
        @endif

        {{-- Form card --}}
        <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5">
            <form method="POST" action="{{ route('user.profile.update') }}" class="p-6 sm:p-8 space-y-6" novalidate>
                @csrf
                @method('PUT')

                {{-- Personal info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="{{ $label }}">نام <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                               maxlength="100" autocomplete="given-name"
                               class="{{ $input }} @error('name') border-red-400 @else border-slate-300 @enderror">
                        @error('name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="family" class="{{ $label }}">نام خانوادگی <span class="text-red-500">*</span></label>
                        <input type="text" id="family" name="family" value="{{ old('family', $user->family) }}"
                               maxlength="100" autocomplete="family-name"
                               class="{{ $input }} @error('family') border-red-400 @else border-slate-300 @enderror">
                        @error('family') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="mobile" class="{{ $label }}">شماره موبایل <span class="text-red-500">*</span></label>
                        <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}"
                               dir="ltr" inputmode="tel" maxlength="11" autocomplete="tel" placeholder="09123456789"
                               class="{{ $input }} {{ $ltr }} @error('mobile') border-red-400 @else border-slate-300 @enderror">
                        @error('mobile') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="{{ $label }}">ایمیل <span class="text-xs font-normal text-slate-400">(اختیاری)</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                               dir="ltr" inputmode="email" maxlength="255" autocomplete="email" placeholder="name@example.com"
                               class="{{ $input }} {{ $ltr }} @error('email') border-red-400 @else border-slate-300 @enderror">
                        @error('email') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Password --}}
                <div class="pt-6 border-t border-slate-100">
                    <h2 class="text-base font-bold text-slate-900">تغییر رمز عبور</h2>
                    <p class="mt-1 text-xs leading-6 text-slate-500">اگر نمی‌خواهید رمز عبور را تغییر دهید، این دو فیلد را خالی بگذارید.</p>

                    <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="{{ $label }}">رمز عبور جدید</label>
                            <input type="password" id="password" name="password"
                                   dir="ltr" autocomplete="new-password" placeholder="در صورت تمایل به تغییر، وارد کنید"
                                   class="{{ $input }} {{ $ltr }} @error('password') border-red-400 @else border-slate-300 @enderror">
                            @error('password') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="{{ $label }}">تکرار رمز عبور جدید</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   dir="ltr" autocomplete="new-password" placeholder="رمز عبور جدید را دوباره وارد کنید"
                                   class="{{ $input }} {{ $ltr }} @error('password_confirmation') border-red-400 @else border-slate-300 @enderror">
                            @error('password_confirmation') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                    <a href="{{ route('user.dashboard') }}"
                       class="px-6 py-3 text-center text-sm font-semibold text-slate-600 rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                        بازگشت
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3 text-sm font-bold text-white rounded-xl
                                   bg-gradient-to-l from-indigo-600 to-fuchsia-500 shadow-lg shadow-indigo-500/30
                                   hover:shadow-xl hover:-translate-y-0.5
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        ذخیره تغییرات
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection