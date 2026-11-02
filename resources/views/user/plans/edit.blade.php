@extends('layouts.public')

@section('title', 'ویرایش طرح')

@section('content')
@php
    // پشتیبانی از enum و رشته برای وضعیت طرح
    $statusValue = $plan->status instanceof \BackedEnum ? $plan->status->value : $plan->status;
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
            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-pink-600 text-white flex items-center justify-center shadow-lg shadow-fuchsia-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M16 3l5 5L8 21H3v-5L16 3z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">ویرایش طرح (ایده محوری)</h1>
                <p class="mt-2 text-sm sm:text-base leading-8 text-slate-600">
                    اطلاعات طرح خود را اصلاح کنید و تغییرات را ذخیره نمایید.
                </p>
            </div>
        </div>

        {{-- Re-review notice --}}
        <div class="mt-8 flex items-start gap-3 px-4 py-3 text-sm leading-7 text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">
            <svg class="w-5 h-5 mt-1 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M12 9v4M12 17h.01M10.3 3.9L2.4 18a2 2 0 001.7 3h15.8a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/>
            </svg>
            <p>توجه: پس از ویرایش، طرح شما مجدداً در صف بررسی قرار می‌گیرد.</p>
        </div>

        {{-- Previous rejection reason (if any) --}}
        @if ($statusValue === 'rejected' && !empty($plan->rejection_reason))
            <div class="mt-4 px-4 py-3 text-sm leading-7 text-red-800 bg-red-50 border border-red-200 rounded-xl">
                <p class="font-bold">دلیل رد قبلی طرح:</p>
                <p class="mt-1 whitespace-pre-line">{{ $plan->rejection_reason }}</p>
            </div>
        @endif

        {{-- Error summary --}}
        @if ($errors->any())
            <div class="mt-4 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl">
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
                    <input type="text" id="title" name="title" value="{{ old('title', $plan->title) }}"
                           maxlength="255" placeholder="مثلاً: سامانه هوشمند مدیریت پسماند شهری"
                           class="w-full px-4 py-3 text-sm rounded-xl border bg-slate-50 placeholder:text-slate-400
                                  focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                  @error('title') border-red-400 @else border-slate-300 @enderror">
                    @error('title')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="activity_category_id" class="block mb-2 text-sm font-medium text-slate-700">
                        دسته فعالیت <span class="text-red-500">*</span>
                    </label>
                    <select id="activity_category_id" name="activity_category_id"
                            class="w-full px-4 py-3 text-sm rounded-xl border bg-slate-50
                                   focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                   @error('activity_category_id') border-red-400 @else border-slate-300 @enderror">
                        <option value="">یک دسته را انتخاب کنید</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                @selected(old('activity_category_id', $plan->activity_category_id) == $category->id)>
                                {{ $category->name ?? $category->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('activity_category_id')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- Duration --}}
                    <div>
                        <label for="estimated_duration_months" class="block mb-2 text-sm font-medium text-slate-700">
                            مدت زمان تقریبی (ماه)
                        </label>
                        <div class="relative">
                            <input type="number" id="estimated_duration_months" name="estimated_duration_months"
                                   value="{{ old('estimated_duration_months', $plan->estimated_duration_months) }}"
                                   min="1" step="1" inputmode="numeric" dir="ltr" placeholder="12"
                                   class="w-full pl-14 pr-4 py-3 text-sm text-left rounded-xl border bg-slate-50 placeholder:text-right placeholder:text-slate-400
                                          focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                          @error('estimated_duration_months') border-red-400 @else border-slate-300 @enderror">
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-xs text-slate-400">ماه</span>
                        </div>
                        @error('estimated_duration_months')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Budget --}}
                    <div>
                        <label for="estimated_budget" class="block mb-2 text-sm font-medium text-slate-700">
                            بودجه مورد نیاز (ریال)
                        </label>
                        <div class="relative">
                            <input type="number" id="estimated_budget" name="estimated_budget"
                                   value="{{ old('estimated_budget', $plan->estimated_budget) }}"
                                   min="0" step="1" inputmode="numeric" dir="ltr" placeholder="500000000"
                                   class="w-full pl-14 pr-4 py-3 text-sm text-left rounded-xl border bg-slate-50 placeholder:text-right placeholder:text-slate-400
                                          focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                          @error('estimated_budget') border-red-400 @else border-slate-300 @enderror">
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-xs text-slate-400">ریال</span>
                        </div>
                        <p id="budget-hint" class="mt-1.5 text-xs text-slate-400 min-h-[1rem]"></p>
                        @error('estimated_budget')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-slate-700">
                        توضیحات مفصل <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="6"
                              placeholder="مسئله‌ای که حل می‌کنید، راهکار پیشنهادی، مخاطبان هدف، مزیت رقابتی و مراحل اجرای طرح را شرح دهید..."
                              class="w-full px-4 py-3 text-sm leading-7 rounded-xl border bg-slate-50 placeholder:text-slate-400 resize-y
                                     focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
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
                                   bg-gradient-to-l from-indigo-600 to-fuchsia-500 shadow-lg shadow-indigo-500/30
                                   hover:shadow-xl hover:-translate-y-0.5
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        ذخیره تغییرات
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7"/>
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
    // نمایش مبلغ با جداکننده‌ی هزار و معادل تومان زیر فیلد بودجه
    (function () {
        const input = document.getElementById('estimated_budget');
        const hint  = document.getElementById('budget-hint');
        if (!input || !hint) return;

        const fmt = new Intl.NumberFormat('fa-IR');

        function update() {
            const v = parseInt(input.value, 10);
            if (!v || v < 0) { hint.textContent = ''; return; }
            hint.textContent = fmt.format(v) + ' ریال (معادل ' + fmt.format(Math.floor(v / 10)) + ' تومان)';
        }

        input.addEventListener('input', update);
        update();
    })();
</script>
@endpush