@extends('layouts.public')

@section('title', 'ایجاد تیم')

@section('content')
@php
    // طرح تأییدشده‌ای که دسته‌ی فعالیت تیم از آن گرفته می‌شود (از کنترلر ارسال می‌شود)
    $categoryName = $plan->activityCategory->name ?? null;
    $categoryId   = $plan->activity_category_id ?? null;
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white min-h-[calc(100vh-5rem)]">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Back --}}
        <a href="{{ route('user.dashboard') }}"
           class="inline-flex items-center gap-1.5 mb-6 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"/>
            </svg>
            بازگشت به پنل کاربری
        </a>

        {{-- Page header --}}
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">ایجاد تیم (سازمان)</h1>
                <p class="mt-2 text-sm sm:text-base leading-8 text-slate-600">
                    طرح شما تأیید شده است. اکنون می‌توانید تیم خود را معرفی کنید تا فعالیت رسمی شما در مرکز نوآوری شهری آغاز شود.
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
            <form method="POST" action="{{ route('user.organizations.store') }}" enctype="multipart/form-data"
                  class="p-6 sm:p-8 space-y-6" novalidate>
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-slate-700">
                        نام تیم <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           maxlength="255" placeholder="مثلاً: تیم نوآوران شهر سبز"
                           class="w-full px-4 py-3 text-sm rounded-xl border bg-slate-50 placeholder:text-slate-400
                                  focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                  @error('name') border-red-400 @else border-slate-300 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category (locked, taken from the approved plan) --}}
                <div>
                    <label for="activity_category_display" class="block mb-2 text-sm font-medium text-slate-700">دسته فعالیت</label>
                    <div class="relative">
                        <input type="text" id="activity_category_display" value="{{ $categoryName ?? 'نامشخص' }}" disabled
                               class="w-full pl-11 pr-4 py-3 text-sm text-slate-500 rounded-xl border border-slate-200 bg-slate-100 cursor-not-allowed">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M7 11V8a5 5 0 0110 0v3M6 11h12a1 1 0 011 1v8a1 1 0 01-1 1H6a1 1 0 01-1-1v-8a1 1 0 011-1z"/>
                        </svg>
                    </div>
                    {{-- فیلد disabled ارسال نمی‌شود؛ مقدار واقعی در فیلد مخفی است --}}
                    <input type="hidden" name="activity_category_id" value="{{ $categoryId }}">
                    <p class="mt-1.5 text-xs text-slate-400">دسته‌ی فعالیت تیم از روی طرح تأییدشده‌ی شما تعیین می‌شود و قابل تغییر نیست.</p>
                    @error('activity_category_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-slate-700">توضیحات</label>
                    <textarea id="description" name="description" rows="5"
                              placeholder="درباره‌ی تیم، اعضا، حوزه‌ی فعالیت و اهداف خود بنویسید..."
                              class="w-full px-4 py-3 text-sm leading-7 rounded-xl border bg-slate-50 placeholder:text-slate-400 resize-y
                                     focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                     @error('description') border-red-400 @else border-slate-300 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Logo --}}
                <div>
                    <label for="logo" class="block mb-2 text-sm font-medium text-slate-700">لوگو</label>
                    <label for="logo"
                           class="flex flex-col items-center justify-center gap-2 px-4 py-8 text-center rounded-xl border-2 border-dashed cursor-pointer
                                  bg-slate-50 hover:bg-indigo-50/50 hover:border-indigo-300 transition-colors
                                  @error('logo') border-red-400 @else border-slate-300 @enderror">
                        <img id="logo-preview" alt="پیش‌نمایش لوگو" class="hidden w-20 h-20 rounded-xl object-cover border border-slate-200">
                        <svg id="logo-icon" class="w-9 h-9 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.6"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM3 16l5-5 4 4 3-3 6 6M8.5 8.5h.01"/>
                        </svg>
                        <span id="logo-file-name" class="text-sm font-medium text-slate-600">برای انتخاب لوگو کلیک کنید</span>
                        <span class="text-xs text-slate-400">فرمت‌های مجاز: PNG، JPG، WEBP</span>
                    </label>
                    <input type="file" id="logo" name="logo" accept=".png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp" class="sr-only">
                    @error('logo')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                    <a href="{{ route('user.dashboard') }}"
                       class="px-6 py-3 text-center text-sm font-semibold text-slate-600 rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                        انصراف
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3 text-sm font-bold text-white rounded-xl
                                   bg-gradient-to-l from-indigo-600 to-fuchsia-500 shadow-lg shadow-indigo-500/30
                                   hover:shadow-xl hover:-translate-y-0.5
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        ایجاد تیم
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

@push('scripts')
<script>
    // نمایش نام فایل و پیش‌نمایش لوگو
    document.getElementById('logo')?.addEventListener('change', function () {
        const name    = document.getElementById('logo-file-name');
        const preview = document.getElementById('logo-preview');
        const icon    = document.getElementById('logo-icon');
        const file    = this.files[0];

        if (!file) {
            name.textContent = 'برای انتخاب لوگو کلیک کنید';
            preview.classList.add('hidden');
            icon.classList.remove('hidden');
            return;
        }

        name.textContent = file.name;
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        icon.classList.add('hidden');
    });
</script>
@endpush