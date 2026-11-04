@extends('layouts.public')

@section('title', 'اخبار مرکز')
@section('meta_description', 'آخرین اخبار و اطلاعیه‌های مرکز نوآوری شهری')

@section('content')
@php
    $categories = $categories ?? collect();
    $hasFilter  = request()->filled('search') || request()->filled('category');
@endphp

{{-- ============ Header + search ============ --}}
<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white">
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-24 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-10 lg:pt-20 text-center">
        <span class="text-sm font-bold text-indigo-600">تازه‌ترین‌ها</span>
        <h1 class="mt-3 text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900">اخبار مرکز</h1>
        <p class="mt-4 max-w-2xl mx-auto text-slate-600 leading-8">
            از آخرین رویدادها، اطلاعیه‌ها و دستاوردهای مرکز نوآوری شهری باخبر شوید.
        </p>

        {{-- Search form --}}
<form method="GET" action="{{ route('public.news.index') }}"
      class="mt-10 max-w-3xl mx-auto p-3 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5
                     flex flex-col sm:flex-row gap-3">

    {{-- حفظ دسته‌ی انتخاب‌شده هنگام جست‌وجو --}}
    @if (request()->filled('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif

    <div class="relative flex-1">
        <svg class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="جست‌وجو در عنوان و متن اخبار..."
               aria-label="جست‌وجو"
               class="w-full pr-12 pl-4 py-3 text-sm rounded-xl border border-slate-200 bg-slate-50 placeholder:text-slate-400
                      focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
    </div>

    <div class="flex gap-2">
        <button type="submit"
                class="flex-1 sm:flex-none px-7 py-3 text-sm font-bold text-white rounded-xl bg-gradient-to-l from-indigo-600 to-fuchsia-500
                       shadow-md shadow-indigo-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
            جست‌وجو
        </button>
        @if ($hasFilter)
            <a href="{{ route('public.news.index') }}"
               class="px-5 py-3 text-sm font-semibold text-slate-600 rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                پاک کردن
            </a>
        @endif
    </div>
</form>

        {{-- Category pills --}}
        @if ($categories->isNotEmpty())
            <div class="mt-6 flex flex-wrap items-center justify-center gap-2">
                <a href="{{ route('public.news.index', array_filter(['search' => request('search')])) }}"
                   class="px-4 py-1.5 text-sm font-semibold rounded-full border transition-colors
                          {{ request()->filled('category') ? 'text-slate-600 bg-white border-slate-200 hover:border-indigo-300 hover:text-indigo-600' : 'text-white bg-indigo-600 border-indigo-600' }}">
                    همه
                </a>
                @foreach ($categories as $category)
                    @php $active = (string) request('category') === (string) $category->slug; @endphp
                    <a href="{{ route('public.news.index', array_filter(['search' => request('search'), 'category' => $category->slug])) }}"
                       class="px-4 py-1.5 text-sm font-semibold rounded-full border transition-colors
                              {{ $active ? 'text-white bg-indigo-600 border-indigo-600' : 'text-slate-600 bg-white border-slate-200 hover:border-indigo-300 hover:text-indigo-600' }}">
                        {{ $category->name ?? $category->title }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ============ Grid ============ --}}
<section class="pb-20 lg:pb-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if ($newsItems->isEmpty())
            <div class="py-20 text-center">
                <div class="mx-auto w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2zM7 8h6M7 12h10M7 16h10"/>
                    </svg>
                </div>
                <p class="mt-5 text-base font-semibold text-slate-600">
                    {{ $hasFilter ? 'خبری با این مشخصات پیدا نشد.' : 'هنوز خبری منتشر نشده است.' }}
                </p>
                @if ($hasFilter)
                    <a href="{{ route('public.news.index') }}"
                       class="mt-4 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                        مشاهده‌ی همه‌ی اخبار
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($newsItems as $news)
                    @php
                        $excerptSource = !empty($news->excerpt) ? $news->excerpt : strip_tags((string) $news->body);
                        $excerpt = \Illuminate\Support\Str::limit(trim($excerptSource), 100);
                        $published = $news->published_at
                            ? \Morilog\Jalali\Jalalian::fromCarbon($news->published_at)->format('j F Y')
                            : null;
                    @endphp

                    <article class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-slate-200
                                    hover:shadow-xl hover:shadow-slate-900/10 hover:-translate-y-1 transition-all">

                        {{-- Cover --}}
                        <a href="{{ route('public.news.show', $news->slug) }}" class="block relative h-48 overflow-hidden bg-gradient-to-br from-indigo-400 to-fuchsia-500">
                            @if (!empty($news->cover_image_path))
                                <img src="{{ route('file.news-cover', basename($news->cover_image_path)) }}"
                                     alt="{{ $news->title }}" loading="lazy"
                                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/15"></div>
                                <div class="absolute -bottom-12 -left-8 w-36 h-36 rounded-full bg-white/10"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-14 h-14 text-white/80" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2zM7 8h6M7 12h10M7 16h10"/>
                                    </svg>
                                </div>
                            @endif
                        </a>

                        <div class="flex-1 flex flex-col p-6">

                            {{-- Categories + date --}}
                            <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($news->categories->take(2) as $cat)
                                        <span class="px-2.5 py-1 font-semibold text-indigo-700 bg-indigo-50 rounded-full">{{ $cat->name ?? $cat->title }}</span>
                                    @endforeach
                                </div>
                                @if ($published)
                                    <span class="text-slate-400">{{ $published }}</span>
                                @endif
                            </div>

                            <h2 class="mt-4 text-base font-bold leading-7 text-slate-900 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                <a href="{{ route('public.news.show', $news->slug) }}">{{ $news->title }}</a>
                            </h2>

                            @if ($excerpt !== '')
                                <p class="mt-2 text-sm leading-7 text-slate-600">{{ $excerpt }}</p>
                            @endif

                            <a href="{{ route('public.news.show', $news->slug) }}"
                               class="mt-auto pt-5 border-t border-slate-100 inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600">
                                ادامه مطلب
                                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M19 12H5M11 6l-6 6 6 6"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($newsItems->hasPages())
                <div class="mt-12" dir="ltr">
                    {{ $newsItems->links() }}
                </div>
            @endif
        @endif
    </div>
</section>
@endsection