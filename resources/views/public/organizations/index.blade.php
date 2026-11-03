@extends('layouts.public')

@section('title', 'واحدهای فناور')
@section('meta_description', 'فهرست تیم‌ها و واحدهای فناور مرکز نوآوری شهری')

@section('content')
@php
    $categories = $categories ?? collect();
    $hasFilter  = request()->filled('q') || request()->filled('category');
@endphp

{{-- ============ Header + search ============ --}}
<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white">
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-24 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-10 lg:pt-20 text-center">
        <span class="text-sm font-bold text-indigo-600">جامعه‌ی نوآوران</span>
        <h1 class="mt-3 text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900">واحدهای فناور</h1>
        <p class="mt-4 max-w-2xl mx-auto text-slate-600 leading-8">
            با تیم‌ها و استارتاپ‌های فعال در مرکز نوآوری شهری آشنا شوید.
        </p>

        {{-- Search form --}}
        <form method="GET" action="{{ route('public.organizations.index') }}"
              class="mt-10 max-w-4xl mx-auto p-3 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5
                     flex flex-col md:flex-row gap-3">

            <div class="relative flex-1">
                <svg class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="جست‌وجوی نام تیم یا نام نماینده..."
                       aria-label="جست‌وجو"
                       class="w-full pr-12 pl-4 py-3 text-sm rounded-xl border border-slate-200 bg-slate-50 placeholder:text-slate-400
                              focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>

            <select name="category" aria-label="دسته فعالیت"
                    class="md:w-56 px-4 py-3 text-sm rounded-xl border border-slate-200 bg-slate-50
                           focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                <option value="">همه‌ی دسته‌ها</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>
                        {{ $category->name ?? $category->title }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 md:flex-none px-7 py-3 text-sm font-bold text-white rounded-xl bg-gradient-to-l from-indigo-600 to-fuchsia-500
                               shadow-md shadow-indigo-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    جست‌وجو
                </button>
                @if ($hasFilter)
                    <a href="{{ route('public.organizations.index') }}"
                       class="px-5 py-3 text-sm font-semibold text-slate-600 rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                        پاک کردن
                    </a>
                @endif
            </div>
        </form>
    </div>
</section>

{{-- ============ Grid ============ --}}
<section class="pb-20 lg:pb-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if ($organizations->total() > 0)
            <p class="mb-6 text-sm text-slate-500">
                {{ $organizations->total() }} واحد فناور یافت شد.
            </p>
        @endif

        @if ($organizations->isEmpty())
            <div class="py-20 text-center">
                <div class="mx-auto w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="mt-5 text-base font-semibold text-slate-600">
                    {{ $hasFilter ? 'تیمی با این مشخصات پیدا نشد.' : 'هنوز واحد فناوری ثبت نشده است.' }}
                </p>
                @if ($hasFilter)
                    <a href="{{ route('public.organizations.index') }}"
                       class="mt-4 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                        مشاهده‌ی همه‌ی تیم‌ها
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($organizations as $organization)
                    @php
                        $rep     = $organization->representative;
                        $repName = trim(($rep?->name ?? '') . ' ' . ($rep?->family ?? ''));
                    @endphp

                    <article class="group flex flex-col p-6 bg-white rounded-2xl border border-slate-200
                                    hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all">

                        {{-- Logo --}}
                        <div class="flex items-center gap-4">
                            @if (!empty($organization->logo_path))
                                <img src="{{ route('file.logo', basename($organization->logo_path)) }}"
                                     alt="لوگوی {{ $organization->name }}" loading="lazy"
                                     class="w-16 h-16 shrink-0 rounded-2xl object-cover border border-slate-200 bg-white">
                            @else
                                <div class="w-16 h-16 shrink-0 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 text-white flex items-center justify-center shadow-lg shadow-cyan-500/20">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="min-w-0">
                                <h2 class="text-base font-bold leading-7 text-slate-900 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                    {{ $organization->name }}
                                </h2>
                            </div>
                        </div>

                        {{-- Category --}}
                        @if (!empty($organization->activityCategory?->name))
                            <div class="mt-4">
                                <span class="inline-block px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-full">
                                    {{ $organization->activityCategory->name }}
                                </span>
                            </div>
                        @endif

                        {{-- Representative --}}
                        <div class="mt-4 flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0116 0"/>
                            </svg>
                            <span class="text-xs text-slate-400">نماینده:</span>
                            <span class="font-medium truncate">{{ $repName !== '' ? $repName : 'نامشخص' }}</span>
                        </div>

                        <a href="{{ route('public.organizations.show', $organization->id) }}"
                           class="mt-6 pt-5 border-t border-slate-100 mt-auto inline-flex items-center justify-between text-sm font-semibold text-indigo-600">
                            <span>مشاهده جزئیات</span>
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M19 12H5M11 6l-6 6 6 6"/>
                            </svg>
                        </a>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($organizations->hasPages())
                <div class="mt-12" dir="ltr">
                    {{ $organizations->links() }}
                </div>
            @endif
        @endif
    </div>
</section>
@endsection