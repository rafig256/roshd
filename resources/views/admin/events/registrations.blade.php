@extends('admin.layouts.app')

@section('title', 'ثبت‌نام‌کنندگان رویداد')
@section('page-title', 'ثبت‌نام‌کنندگان رویداد')

@section('content')
@php
    $search = $search ?? '';

    $statusLabels = [
        'confirmed' => 'تأیید شده',
        'pending'   => 'در انتظار',
        'cancelled' => 'لغو شده',
    ];
    $statusStyles = [
        'confirmed' => ['class' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500'],
        'pending'   => ['class' => 'bg-amber-100 text-amber-800 border-amber-200',       'dot' => 'bg-amber-500'],
        'cancelled' => ['class' => 'bg-red-100 text-red-800 border-red-200',             'dot' => 'bg-red-500'],
    ];
@endphp

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="min-w-0">
            <h2 class="text-xl font-bold text-slate-800">ثبت‌نام‌کنندگان در: {{ $event->title }}</h2>
            <p class="mt-1 text-sm text-slate-500">
                @if (method_exists($registrations, 'total'))
                    مجموع <span class="font-semibold text-slate-700">{{ number_format($registrations->total()) }}</span> ثبت‌نام
                    @if ($search !== '') مطابق جست‌وجوی شما @endif
                @endif
            </p>
        </div>

        <a href="{{ route('admin.events.index') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg
                  hover:bg-slate-50 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            بازگشت به رویدادها
        </a>
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

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.events.registrations', $event->id) }}" class="mb-4 flex flex-col sm:flex-row gap-2">
        <div class="relative flex-1">
            <svg class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="جست‌وجو بر اساس نام، نام خانوادگی، موبایل یا کد ملی..."
                   aria-label="جست‌وجو"
                   class="w-full pr-12 pl-4 py-2.5 text-sm rounded-lg border border-slate-300 bg-white placeholder:text-slate-400
                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
        </div>
        <div class="flex gap-2">
            <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors">
                جستجو
            </button>
            @if ($search !== '')
                <a href="{{ route('admin.events.registrations', $event->id) }}"
                   class="px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                    پاک کردن
                </a>
            @endif
        </div>
    </form>

    {{-- Table card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">نام و نام خانوادگی</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">کد ملی</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">شماره موبایل</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">تاریخ ثبت‌نام</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">وضعیت ثبت‌نام</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($registrations as $registration)
                        @php
                            $user     = $registration->user;
                            $fullName = trim(($user?->name ?? '') . ' ' . ($user?->family ?? ''));

                            // وضعیت (پشتیبانی از enum و رشته)
                            $statusRaw   = $registration->status;
                            $statusValue = $statusRaw instanceof \BackedEnum ? $statusRaw->value : $statusRaw;
                            $statusLabel = ($statusRaw instanceof \BackedEnum && method_exists($statusRaw, 'label'))
                                ? $statusRaw->label()
                                : ($statusLabels[$statusValue] ?? ($statusValue ?: 'نامشخص'));
                            $st = $statusStyles[$statusValue] ?? ['class' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-400'];

                            $registeredAt = $registration->registered_at
                                ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($registration->registered_at))->format('Y/m/d')
                                : null;
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 shrink-0 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">
                                        {{ mb_substr($fullName !== '' ? $fullName : '؟', 0, 1) }}
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $fullName !== '' ? $fullName : 'کاربر حذف‌شده' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                                @if ($user?->national_code)
                                    <span dir="ltr" class="inline-block">{{ $user->national_code }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                                @if ($user?->mobile)
                                    <span dir="ltr" class="inline-block">{{ $user->mobile }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                                @if ($registeredAt)
                                    <span dir="ltr" class="inline-block">{{ $registeredAt }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full border {{ $st['class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="mx-auto w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <p class="mt-4 text-sm font-medium text-slate-500">
                                    {{ $search !== '' ? 'ثبت‌نام‌کننده‌ای با این مشخصات پیدا نشد.' : 'هنوز کسی در این رویداد ثبت‌نام نکرده است.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($registrations->hasPages())
            <div class="px-5 py-4 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-xs text-slate-500">
                    نمایش {{ $registrations->firstItem() }} تا {{ $registrations->lastItem() }} از {{ $registrations->total() }} ثبت‌نام
                </p>
                <div dir="ltr">
                    {{ $registrations->appends(['search' => $search])->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection