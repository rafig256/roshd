@extends('layouts.public')

@section('title', $news->title)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) ($news->excerpt ?: $news->body)), 150))

@section('content')
@php
    $published = $news->published_at
        ? \Morilog\Jalali\Jalalian::fromCarbon($news->published_at)->format('j F Y')
        : null;

    $author     = $news->author;
    $authorName = trim(($author?->name ?? '') . ' ' . ($author?->family ?? ''));
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white">
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <article class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Back (top) --}}
        <a href="{{ route('public.news.index') }}"
           class="inline-flex items-center gap-1.5 mb-6 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            بازگشت به اخبار
        </a>

        {{-- Categories --}}
        @if ($news->categories->isNotEmpty())
            <div class="flex flex-wrap gap-2">
                @foreach ($news->categories as $cat)
                    <a href="{{ route('public.news.index', ['category' => $cat->slug]) }}"
                       class="px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-full hover:bg-indigo-100 transition-colors">
                        {{ $cat->name ?? $cat->title }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Title --}}
        <h1 class="mt-4 text-3xl sm:text-4xl font-extrabold leading-snug text-slate-900">{{ $news->title }}</h1>

        {{-- Meta --}}
        <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-500">
            @if ($published)
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                    </svg>
                    {{ $published }}
                </span>
            @endif
            @if ($authorName !== '')
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0116 0"/>
                    </svg>
                    {{ $authorName }}
                </span>
            @endif
        </div>

        {{-- Cover --}}
        @if (!empty($news->cover_image_path))
            <figure class="mt-8">
                <img src="{{ route('file.news-cover', basename($news->cover_image_path)) }}"
                     alt="{{ $news->title }}"
                     class="w-full max-h-[28rem] object-cover rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/10">
            </figure>
        @endif

        {{-- Body --}}
        <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <div class="text-base leading-9 text-slate-700 break-words">
                {!! nl2br(e($news->body)) !!}
            </div>
        </div>

        {{-- Back (bottom) --}}
        <div class="mt-10">
            <a href="{{ route('public.news.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-indigo-700 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                بازگشت به اخبار
            </a>
        </div>
    </article>
</section>
@endsection