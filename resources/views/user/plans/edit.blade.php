@extends('layouts.public')

@section('title', 'ویرایش طرح (ایده محوری)')

@section('content')
<section class="relative overflow-hidden bg-gradient-to-b from-fuchsia-50 via-white to-white min-h-[calc(100vh-5rem)]">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-fuchsia-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-indigo-300/20 blur-3xl"></div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Breadcrumb / back --}}
        <a href="{{ route('user.dashboard') }}"
           class="inline-flex items-center gap-1.5 mb-6 text-sm font-medium text-slate-500 hover:text-fuchsia-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"/>
            </svg>
            بازگشت به پنل کاربری
        </a>

        {{-- Page header --}}
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-pink-600 text-white flex items-center justify-center shadow-lg shadow-fuchsia-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">ویرایش طرح (ایده محوری)</h1>
                <p class="mt-2 text-sm sm:text-base leading-8 text-slate-600">
                    طرح شما در وضعیت <strong>رد شده</strong> است. با ویرایش طرح، وضعیت آن به <strong>«در انتظار بررسی»</strong> تغییر می‌کند تا کارشناسان مجدداً آن را بررسی نمایند.
                </p>
            </div>
        </div>

        {{-- General error summary --}}
        @if ($errors->any())
            <div class="mt-8 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl">
                لطفاً خطاهای مشخص‌شده در فرم را برطرف کنید و دوباره تلاش کنید.
            </div>
        @endif

        {{-- Form card --}}
        <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5">
            <form method="POST" action="{{ route('user.plans.update', $plan->id) }}" class="p-6 sm:p-8 space-y-6" novalidate>
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div>
                    <label for="title" class="block mb-2 text-sm font-medium text-slate-700">
                        عنوان طرح <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title"
                           placeholder="مثال: سیستم هوشمند مدیریت ترافیک شهری"
                           class="w-full px-4 py-3 text-sm rounded-xl border bg-slate-50 placeholder:text-slate-400
                                  focus:bg-white focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:border-fuchsia-500 transition
                                  @error('title') border-red-400 @else border-slate-300 @enderror"
                           value="{{ old('title', $plan->title) }}">
                    @error('title')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Activity category --}}
                <div>
                    <label for="activity_category_id" class="block mb-2 text-sm font-medium text-slate-700">
                        دسته فعالیت <span class="text-red-500">*</span>
                    </label>
                    <select id="activity_category_id" name="activity_category_id"
                            class="w-full px-4 py-3 text-sm rounded-xl border bg-slate-50
                                   focus:bg-white focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:border-fuchsia-500 transition
                                   @error('activity_category_id') border-red-400 @else border-slate-300 @enderror">
                        <option value="">یک دسته را انتخاب کنید</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $plan->activity_category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name ?? $category->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('activity_category_id')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estimated duration --}}
                <div>
                    <label for="estimated_duration_months" class="block mb-2 text-sm font-medium text-slate-700">
                        زمان تخمینی اجرا (ماه)
                    </label>
                    <input type="number" id="estimated_duration_months" name="estimated_duration_months"
                           min="1" placeholder="مثال: 12"
                           class="w-full px-4 py-3 text-sm rounded-xl border bg-slate-50 placeholder:text-slate-400
                                  focus:bg-white focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:border-fuchsia-500 transition
                                  @error('estimated_duration_months') border-red-400 @else border-slate-300 @enderror"
                           value="{{ old('estimated_duration_months', $plan->estimated_duration_months) }}">
                    <p class="mt-1.5 text-xs text-slate-400">مقدار را به ماه وارد کنید. در صورت عدم اطمینان، این فیلد را خالی بگذارید.</p>
                    @error('estimated_duration_months')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estimated budget --}}
                <div>
                    <label for="estimated_budget" class="block mb-2 text-sm font-medium text-slate-700">
                        بودجه تخمینی (ریال)
                    </label>
                    <input type="number" id="estimated_budget" name="estimated_budget"
                           min="0" placeholder="مثال: 500000000"
                           class="w-full px-4 py-3 text-sm rounded-xl border bg-slate-50 placeholder:text-slate-400
                                  focus:bg-white focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:border-fuchsia-500 transition
                                  @error('estimated_budget') border-red-400 @else border-slate-300 @enderror"
                           value="{{ old('estimated_budget', $plan->estimated_budget) }}">
                    <p class="mt-1.5 text-xs text-slate-400">مقدار را به ریال وارد کنید. در صورت عدم اطمینان، این فیلد را خالی بگذارید.</p>
                    @error('estimated_budget')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-slate-700">
                        توضیحات طرح <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="8"
                              placeholder="شامل: شرح مسئله، راهکار پیشنهادی، نوآوری‌های کلیدی، بازار هدف، مدل درآمدی، تیم پیشنهادی، و هر اطلاعات دیگری که برای ارزیابی طرح مفید باشد..."
                              class="w-full px-4 py-3 text-sm leading-7 rounded-xl border bg-slate-50 placeholder:text-slate-400 resize-y
                                     focus:bg-white focus:outline-none focus:ring-2 focus:ring-fuchsia-500 focus:border-fuchsia-500 transition
                                     @error('description') border-red-400 @else border-slate-300 @enderror">{{ old('description', $plan->description) }}</textarea>
                    @error('description')
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
                                   bg-gradient-to-l from-fuchsia-500 to-pink-600 shadow-lg shadow-fuchsia-500/30
                                   hover:shadow-xl hover:-translate-y-0.5
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-fuchsia-500 transition-all">
                        ذخیره تغییرات
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection