@extends('admin.layouts.app')

@section('title', 'مدیریت رویدادها')
@section('page-title', 'مدیریت رویدادها')

@section('content')
@php
    $locationLabels = [
        'physical' => 'حضوری',
        'online'   => 'آنلاین',
        'hybrid'   => 'ترکیبی',
    ];
    $locationStyles = [
        'physical' => 'bg-indigo-50 text-indigo-700',
        'online'   => 'bg-cyan-50 text-cyan-700',
        'hybrid'   => 'bg-fuchsia-50 text-fuchsia-700',
    ];

    // رنگ‌ها به‌صورت کلاس کامل (برای سازگاری با build تیلویند)
    $statusStyles = [
        'draft'     => ['class' => 'bg-amber-100 text-amber-800 border-amber-200',       'dot' => 'bg-amber-500'],
        'published' => ['class' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500'],
        'cancelled' => ['class' => 'bg-red-100 text-red-800 border-red-200',             'dot' => 'bg-red-500'],
        'finished'  => ['class' => 'bg-slate-100 text-slate-700 border-slate-200',       'dot' => 'bg-slate-400'],
    ];
@endphp

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">رویدادها</h2>
            <p class="mt-1 text-sm text-slate-500">رویدادهای مرکز را ایجاد، ویرایش و مدیریت کنید.</p>
        </div>
        <a href="{{ route('admin.events.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg
                  hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
            ایجاد رویداد جدید
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
    <form method="GET" action="{{ route('admin.events.index') }}" class="mb-4 flex flex-col sm:flex-row gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="جست‌وجو در عنوان یا نشانک (slug)..."
               aria-label="جست‌وجو"
               class="flex-1 px-4 py-2.5 text-sm rounded-lg border border-slate-300 bg-white placeholder:text-slate-400
                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
        <div class="flex gap-2">
            <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors">
                جست‌وجو
            </button>
            @if (request()->filled('search'))
                <a href="{{ route('admin.events.index') }}"
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
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">عنوان</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">تاریخ شروع</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">نوع برگزاری</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">وضعیت</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($events as $event)
                        @php
                            $startsJalali = $event->starts_at
                                ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($event->starts_at))->format('Y/m/d H:i')
                                : null;

                            $locValue = $event->location_type instanceof \BackedEnum ? $event->location_type->value : $event->location_type;
                            $locLabel = $locationLabels[$locValue] ?? ($locValue ?: 'نامشخص');
                            $locClass = $locationStyles[$locValue] ?? 'bg-slate-100 text-slate-700';

                            $statusValue = $event->status instanceof \BackedEnum ? $event->status->value : $event->status;
                            $statusLabel = $event->status instanceof \App\Enums\EventStatus
                                ? $event->status->label()
                                : ($statusValue ?: 'نامشخص');
                            $st = $statusStyles[$statusValue] ?? ['class' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-400'];
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4 max-w-xs">
                                <span class="font-semibold text-slate-800 line-clamp-2">{{ $event->title }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                                @if ($startsJalali)
                                    <span dir="ltr" class="inline-block">{{ $startsJalali }}</span>
                                @else
                                    <span class="text-slate-400">ثبت نشده</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $locClass }}">{{ $locLabel }}</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full border {{ $st['class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.events.edit', $event->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 3l5 5L8 21H3v-5L16 3z"/></svg>
                                        ویرایش
                                    </a>

                                    <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}"
                                          onsubmit="return confirm('آیا از حذف این رویداد مطمئن هستید؟ این عمل قابل بازگشت نیست.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 12a2 2 0 002 2h6a2 2 0 002-2l1-12M9 7V4h6v3"/></svg>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="mx-auto w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                                    </svg>
                                </div>
                                <p class="mt-4 text-sm font-medium text-slate-500">
                                    {{ request()->filled('search') ? 'رویدادی با این مشخصات پیدا نشد.' : 'هنوز رویدادی ثبت نشده است.' }}
                                </p>
                                @unless (request()->filled('search'))
                                    <a href="{{ route('admin.events.create') }}" class="mt-3 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                        ایجاد اولین رویداد
                                    </a>
                                @endunless
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($events->hasPages())
            <div class="px-5 py-4 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-xs text-slate-500">
                    نمایش {{ $events->firstItem() }} تا {{ $events->lastItem() }} از {{ $events->total() }} رویداد
                </p>
                <div dir="ltr">
                    {{ $events->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection