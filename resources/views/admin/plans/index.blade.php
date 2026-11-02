@extends('admin.layouts.app')

@section('title', 'مدیریت طرح‌ها')
@section('page-title', 'مدیریت طرح‌ها')

@section('content')
@php
    // فقط استایل‌ها اینجا تعریف می‌شوند؛ هیچ اشاره‌ای به $plan در این بلوک نباشد
    $statusStyles = [
        'pending'  => ['class' => 'bg-amber-100 text-amber-800 border-amber-200',       'dot' => 'bg-amber-500'],
        'approved' => ['class' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500'],
        'rejected' => ['class' => 'bg-red-100 text-red-800 border-red-200',             'dot' => 'bg-red-500'],
        'archived' => ['class' => 'bg-slate-100 text-slate-700 border-slate-200',       'dot' => 'bg-slate-400'],
    ];
@endphp

<div>
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">طرح‌های ثبت‌شده</h2>
        <p class="mt-1 text-sm text-slate-500">طرح‌ها و ایده‌های ارسال‌شده توسط فناوران را بررسی و مدیریت کنید.</p>
    </div>

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

    {{-- Table card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">نام فناور</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">عنوان طرح</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">دسته فعالیت</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">تاریخ ثبت</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">وضعیت</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($plans as $plan)
                        @php
                            $statusValue = $plan->status instanceof \BackedEnum ? $plan->status->value : $plan->status;
                            $statusLabel = $plan->status instanceof \App\Enums\PlanStatus
                                ? $plan->status->label()
                                : ($statusValue ?: 'نامشخص');
                            $st = $statusStyles[$statusValue] ?? [
                                'class' => 'bg-slate-100 text-slate-700 border-slate-200',
                                'dot'   => 'bg-slate-400',
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4 font-medium text-slate-800 whitespace-nowrap">
                                {{ trim(($plan->innovator?->name ?? '') . ' ' . ($plan->innovator?->family ?? '')) ?: 'نامشخص' }}
                            </td>
                            <td class="px-5 py-4 text-slate-700 max-w-xs">
                                <span class="line-clamp-2">{{ $plan->title }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                                {{ $plan->activityCategory->name ?? '—' }}
                            </td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                                @if ($plan->created_at)
                                    <span dir="ltr" class="inline-block">{{ \Morilog\Jalali\Jalalian::fromCarbon($plan->created_at)->format('Y/m/d') }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full border {{ $st['class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.plans.show', $plan->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-lg
                                          hover:bg-indigo-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    مشاهده جزئیات
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="mx-auto w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-4 2-4-2-3 2V6a2 2 0 012-2z"/>
                                    </svg>
                                </div>
                                <p class="mt-4 text-sm font-medium text-slate-500">هنوز طرحی ثبت نشده است.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($plans->hasPages())
            <div class="px-5 py-4 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-xs text-slate-500">
                    نمایش {{ $plans->firstItem() }} تا {{ $plans->lastItem() }} از {{ $plans->total() }} طرح
                </p>
                <div dir="ltr">
                    {{ $plans->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection