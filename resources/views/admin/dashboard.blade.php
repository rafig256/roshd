@extends('admin.layouts.app')

@section('title', 'داشبورد مدیریت')
@section('page-title', 'داشبورد مدیریت')

@section('content')
<div class="max-w-7xl">

    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-xl font-bold text-slate-800">داشبورد مدیریت</h2>
        <p class="mt-1 text-sm text-slate-500">
            خوش آمدید! اینجا نمای کلی از وضعیت سیستم را مشاهده می‌کنید.
        </p>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-2 px-4 py-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card 1: Users --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">کاربران</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format($stats['users_count']) }}</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0113-6.2M19 14v6M16 17h6"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 2: Plans --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">طرح‌ها</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format($stats['plans_count']) }}</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-4 2-4-2-3 2V6a2 2 0 012-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 3: Organizations --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">سازمان‌ها</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format($stats['orgs_count']) }}</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 4: Events --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">رویدادها</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format($stats['events_count']) }}</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Section: 2 Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Left: Pending Reviews --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-800">در انتظار بررسی</h3>
            </div>
            <div class="p-6 space-y-4">
                {{-- Innovator Requests --}}
                <a href="{{ route('admin.innovator-requests.index') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-100 hover:border-indigo-200 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-4 2-4-2-3 2V6a2 2 0 012-2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">درخواست‌های فناوری</p>
                            <p class="text-xs text-slate-500">در انتظار تأیید</p>
                        </div>
                    </div>
                    <div class="inline-flex items-center justify-center px-4 py-1.5 text-sm font-bold text-white bg-indigo-600 rounded-full">
                        {{ number_format($pendingReviews['innovators']) }}
                    </div>
                </a>

                {{-- Plans --}}
                <a href="{{ route('admin.plans.index') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-100 hover:border-purple-200 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-4 2-4-2-3 2V6a2 2 0 012-2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">طرح‌ها</p>
                            <p class="text-xs text-slate-500">در انتظار بررسی</p>
                        </div>
                    </div>
                    <div class="inline-flex items-center justify-center px-4 py-1.5 text-sm font-bold text-white bg-purple-600 rounded-full">
                        {{ number_format($pendingReviews['plans']) }}
                    </div>
                </a>

                {{-- Companies --}}
                <a href="{{ route('admin.company-requests.index') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-slate-100 hover:border-orange-200 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2zM7 8h6M7 12h10M7 16h10"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">درخواست‌های شرکت</p>
                            <p class="text-xs text-slate-500">در انتظار بررسی</p>
                        </div>
                    </div>
                    <div class="inline-flex items-center justify-center px-4 py-1.5 text-sm font-bold text-white bg-orange-600 rounded-full">
                        {{ number_format($pendingReviews['companies']) }}
                    </div>
                </a>
            </div>
        </div>

        {{-- Right: Recent Events --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-800">رویدادهای اخیر</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recentEvents as $event)
                    <a href="{{ route('admin.events.show', $event->id) }}"
                       class="p-4 hover:bg-slate-50 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate">{{ $event->title }}</p>
                                <p class="text-xs text-slate-400">
                                    @if($event->starts_at)
                                        {{ \Morilog\Jalali\Jalalian::fromCarbon($event->starts_at)->format('j F Y - H:i') }}
                                    @else
                                        زمان مشخص نشده
                                    @endif
                                </p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 12H9m12 0h-6"/></svg>
                    </a>
                @empty
                    <div class="p-6 text-center">
                        <p class="text-sm text-slate-500">رویدادی یافت نشد.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection