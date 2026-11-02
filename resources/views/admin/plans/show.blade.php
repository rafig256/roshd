@extends('admin.layouts.app')

@section('title', 'جزئیات طرح')
@section('page-title', 'جزئیات طرح')

@section('content')
@php
    $statusStyles = [
        'pending'  => ['class' => 'bg-amber-100 text-amber-800 border-amber-200',       'dot' => 'bg-amber-500'],
        'approved' => ['class' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500'],
        'rejected' => ['class' => 'bg-red-100 text-red-800 border-red-200',             'dot' => 'bg-red-500'],
        'archived' => ['class' => 'bg-slate-100 text-slate-700 border-slate-200',       'dot' => 'bg-slate-400'],
    ];

    // مقدار رشته‌ای وضعیت (با پشتیبانی از enum و رشته)
    $statusValue = $plan->status instanceof \BackedEnum ? $plan->status->value : $plan->status;
    $statusLabel = $plan->status instanceof \App\Enums\PlanStatus
        ? $plan->status->label()
        : ($statusValue ?: 'نامشخص');
    $st = $statusStyles[$statusValue] ?? ['class' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-400'];

    $user     = $plan->innovator;
    $fullName = trim(($user?->name ?? '') . ' ' . ($user?->family ?? ''));
@endphp

<div class="max-w-4xl mx-auto">

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-2 px-4 py-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">جزئیات طرح</h2>
            <p class="mt-1 text-sm text-slate-500">
                شناسه‌ی طرح: <span class="font-semibold text-slate-700">#{{ $plan->id }}</span>
                @if ($plan->created_at)
                    <span class="mx-1 text-slate-300">|</span>
                    ثبت‌شده در
                    <span dir="ltr" class="inline-block">{{ \Morilog\Jalali\Jalalian::fromCarbon($plan->created_at)->format('Y/m/d H:i') }}</span>
                @endif
            </p>
        </div>

        <span class="inline-flex items-center gap-2 self-start px-4 py-1.5 text-sm font-bold rounded-full border {{ $st['class'] }}">
            <span class="w-2 h-2 rounded-full {{ $st['dot'] }}"></span>
            {{ $statusLabel }}
        </span>
    </div>

    <div class="space-y-6">

        {{-- Innovator info --}}
        <section class="rounded-xl border border-slate-200 bg-white">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0116 0"/></svg>
                </span>
                <h3 class="text-base font-semibold text-slate-800">اطلاعات فناور</h3>
            </div>
            <dl class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                <div>
                    <dt class="text-xs text-slate-400">نام و نام خانوادگی</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800">{{ $fullName !== '' ? $fullName : 'ثبت نشده' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">کد ملی</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800" dir="ltr" style="text-align:right">{{ $user?->national_code ?? 'ثبت نشده' }}</dd>
                </div>
            </dl>
        </section>

        {{-- Plan info --}}
        <section class="rounded-xl border border-slate-200 bg-white">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                <span class="w-9 h-9 rounded-lg bg-fuchsia-50 text-fuchsia-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-4 2-4-2-3 2V6a2 2 0 012-2z"/></svg>
                </span>
                <h3 class="text-base font-semibold text-slate-800">اطلاعات طرح</h3>
            </div>

            <dl class="p-6 space-y-6">
                <div>
                    <dt class="text-xs text-slate-400">عنوان طرح</dt>
                    <dd class="mt-1 text-base font-bold text-slate-900">{{ $plan->title }}</dd>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-8 gap-y-5">
                    <div>
                        <dt class="text-xs text-slate-400">دسته فعالیت</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-800">{{ $plan->activityCategory->name ?? 'ثبت نشده' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">مدت زمان تقریبی</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-800">
                            @if (!is_null($plan->estimated_duration_months))
                                {{ number_format($plan->estimated_duration_months) }} ماه
                            @else
                                <span class="text-slate-400">ثبت نشده</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">بودجه مورد نیاز</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-800">
                            @if (!is_null($plan->estimated_budget))
                                {{ number_format($plan->estimated_budget) }} ریال
                                <span class="block mt-0.5 text-xs font-normal text-slate-400">
                                    معادل {{ number_format(intdiv((int) $plan->estimated_budget, 10)) }} تومان
                                </span>
                            @else
                                <span class="text-slate-400">ثبت نشده</span>
                            @endif
                        </dd>
                    </div>
                </div>

                <div>
                    <dt class="text-xs text-slate-400">توضیحات مفصل</dt>
                    <dd class="mt-2 p-4 text-sm leading-8 text-slate-700 bg-slate-50 rounded-lg border border-slate-100 whitespace-pre-line">{{ $plan->description ?: 'ثبت نشده' }}</dd>
                </div>
            </dl>
        </section>

        {{-- Rejection reason --}}
        @if ($statusValue === 'rejected')
            <section class="rounded-xl border border-red-200 bg-red-50 p-6">
                <h3 class="flex items-center gap-2 text-sm font-bold text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 9v4M12 17h.01M10.3 3.9L2.4 18a2 2 0 001.7 3h15.8a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/></svg>
                    دلیل رد طرح
                </h3>
                <p class="mt-3 text-sm leading-8 text-red-800 whitespace-pre-line">{{ $plan->rejection_reason ?: 'دلیلی ثبت نشده است.' }}</p>
            </section>
        @endif

        {{-- Actions --}}
        @if ($statusValue === 'pending')
            <section class="rounded-xl border border-slate-200 bg-slate-50 p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">تعیین وضعیت طرح</h3>
                <div class="flex flex-col sm:flex-row gap-3">

                    <form method="POST" action="{{ route('admin.plans.approve', $plan->id) }}"
                          onsubmit="return confirm('آیا از تأیید این طرح مطمئن هستید؟');">
                        @csrf
                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-lg
                                       hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            تأیید طرح
                        </button>
                    </form>

                    <button type="button" id="open-reject-modal"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg
                                   hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
                        رد طرح
                    </button>
                </div>
            </section>
        @endif

        {{-- Back --}}
        <div>
            <a href="{{ route('admin.plans.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                بازگشت به لیست
            </a>
        </div>
    </div>
</div>

{{-- Reject modal (native <dialog>) --}}
@if ($statusValue === 'pending')
    <dialog id="reject-modal"
            class="w-full max-w-lg p-0 rounded-2xl shadow-2xl backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm"
            style="margin:auto">
        <form method="POST" action="{{ route('admin.plans.reject', $plan->id) }}" dir="rtl" class="font-sans">
            @csrf

            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">رد طرح</h3>
                <button type="button" id="close-reject-modal" aria-label="بستن"
                        class="p-1.5 text-slate-400 rounded-lg hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>

            <div class="p-6">
                <label for="rejection_reason" class="block mb-2 text-sm font-medium text-slate-700">
                    دلیل رد طرح <span class="text-red-500">*</span>
                </label>
                <textarea id="rejection_reason" name="rejection_reason" rows="5" required
                          placeholder="دلیل رد طرح را برای فناور بنویسید..."
                          class="w-full px-4 py-3 text-sm leading-7 rounded-lg border bg-slate-50 resize-y
                                 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition
                                 @error('rejection_reason') border-red-400 @else border-slate-300 @enderror">{{ old('rejection_reason') }}</textarea>
                @error('rejection_reason')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="px-6 py-4 bg-slate-50 rounded-b-2xl flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button" id="cancel-reject-modal"
                        class="px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg hover:bg-white transition-colors">
                    انصراف
                </button>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    ثبت رد طرح
                </button>
            </div>
        </form>
    </dialog>

    @push('scripts')
    <script>
        (function () {
            const modal = document.getElementById('reject-modal');
            if (!modal) return;

            document.getElementById('open-reject-modal')?.addEventListener('click', () => modal.showModal());
            document.getElementById('close-reject-modal')?.addEventListener('click', () => modal.close());
            document.getElementById('cancel-reject-modal')?.addEventListener('click', () => modal.close());

            // کلیک روی پس‌زمینه، مودال را ببندد
            modal.addEventListener('click', (e) => {
                const r = modal.getBoundingClientRect();
                const inside = e.clientX >= r.left && e.clientX <= r.right && e.clientY >= r.top && e.clientY <= r.bottom;
                if (!inside) modal.close();
            });

            // اگر اعتبارسنجی دلیل رد شکست خورد، مودال دوباره باز شود
            @if ($errors->has('rejection_reason'))
                modal.showModal();
            @endif
        })();
    </script>
    @endpush
@endif
@endsection