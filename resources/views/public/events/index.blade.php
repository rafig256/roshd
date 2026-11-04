@extends('layouts.public')

@section('title', 'رویدادها')
@section('meta_description', 'رویدادها، کارگاه‌ها و نشست‌های تخصصی مرکز نوآوری شهری')

@section('content')
@php
    $filter    = $filter ?? request('filter', 'all');
    $hasFilter = request()->filled('q') || in_array($filter, ['upcoming', 'past'], true);

    // ارقام فارسی و تاریخ شمسی
    $fa    = fn ($v) => strtr((string) $v, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']);
    $jFull = function ($d) use ($fa) {
        if (!$d) return null;
        $c = \Carbon\Carbon::parse($d);
        return $fa(\Morilog\Jalali\Jalalian::fromCarbon($c)->format('j F Y')) . ' - ساعت ' . $fa($c->format('H:i'));
    };

    $locationLabels = ['physical' => 'حضوری', 'online' => 'آنلاین', 'hybrid' => 'ترکیبی'];
    $locationStyles = ['physical' => 'bg-white/90 text-indigo-700', 'online' => 'bg-white/90 text-cyan-700', 'hybrid' => 'bg-white/90 text-fuchsia-700'];

    $statusMap = [
        'registering' => ['label' => 'در حال ثبت‌نام', 'badge' => 'bg-emerald-100 text-emerald-800', 'dot' => 'bg-emerald-500 animate-pulse'],
        'open'        => ['label' => 'ورود آزاد',      'badge' => 'bg-indigo-100 text-indigo-800',   'dot' => 'bg-indigo-500'],
        'closed'      => ['label' => 'پایان ثبت‌نام',  'badge' => 'bg-rose-100 text-rose-800',       'dot' => 'bg-rose-500'],
        'finished'    => ['label' => 'برگزار شده',     'badge' => 'bg-slate-200 text-slate-700',     'dot' => 'bg-slate-500'],
        'cancelled'   => ['label' => 'لغو شده',        'badge' => 'bg-red-100 text-red-800',         'dot' => 'bg-red-500'],
    ];

    $gradients = [
        'from-indigo-500 to-indigo-800', 'from-fuchsia-500 to-pink-700', 'from-emerald-500 to-teal-700',
        'from-orange-400 to-rose-600',   'from-cyan-500 to-blue-700',    'from-violet-500 to-purple-800',
    ];
@endphp

{{-- ============ Header + filters ============ --}}
<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white">
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-24 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-10 lg:pt-20 text-center">
        <span class="text-sm font-bold text-fuchsia-600">برنامه‌های مرکز</span>
        <h1 class="mt-3 text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900">رویدادها</h1>
        <p class="mt-4 max-w-2xl mx-auto text-slate-600 leading-8">
            در رویدادهای تخصصی مرکز نوآوری شهری شرکت کنید، یاد بگیرید و شبکه‌ی ارتباطی خود را بسازید.
        </p>

        {{-- Search --}}
        <form method="GET" action="{{ route('public.events.index') }}"
              class="mt-10 max-w-3xl mx-auto p-3 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5 flex flex-col sm:flex-row gap-3">
            @if (in_array($filter, ['upcoming', 'past'], true))
                <input type="hidden" name="filter" value="{{ $filter }}">
            @endif

            <div class="relative flex-1">
                <svg class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="جست‌وجو در عنوان رویدادها..." aria-label="جست‌وجو"
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
                    <a href="{{ route('public.events.index') }}"
                       class="px-5 py-3 text-sm font-semibold text-slate-600 rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                        پاک کردن
                    </a>
                @endif
            </div>
        </form>

        {{-- Time filter pills --}}
        <div class="mt-6 flex flex-wrap items-center justify-center gap-2">
            @foreach (['all' => 'همه', 'upcoming' => 'آینده', 'past' => 'گذشته'] as $key => $text)
                <a href="{{ route('public.events.index', array_filter(['q' => request('q'), 'filter' => $key === 'all' ? null : $key])) }}"
                   class="px-5 py-1.5 text-sm font-semibold rounded-full border transition-colors
                          {{ $filter === $key ? 'text-white bg-indigo-600 border-indigo-600' : 'text-slate-600 bg-white border-slate-200 hover:border-indigo-300 hover:text-indigo-600' }}">
                    {{ $text }}
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ Grid ============ --}}
<section class="pb-20 lg:pb-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if ($events->isEmpty())
            <div class="py-20 text-center">
                <div class="mx-auto w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                </div>
                <p class="mt-5 text-base font-semibold text-slate-600">
                    {{ $hasFilter ? 'رویدادی با این مشخصات پیدا نشد.' : 'هنوز رویدادی منتشر نشده است.' }}
                </p>
                @if ($hasFilter)
                    <a href="{{ route('public.events.index') }}" class="mt-4 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">مشاهده‌ی همه‌ی رویدادها</a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($events as $event)
                    @php
                        $state = $statusMap[$event->display_status] ?? $statusMap['open'];

                        $locValue = $event->location_type instanceof \BackedEnum ? $event->location_type->value : $event->location_type;
                        $locLabel = $locationLabels[$locValue] ?? null;
                        $locClass = $locationStyles[$locValue] ?? 'bg-white/90 text-slate-700';

                        $cover = (!empty($event->cover_image_path) && \Illuminate\Support\Facades\Route::has('file.event-cover'))
                            ? route('file.event-cover', basename($event->cover_image_path)) : null;

                        $speakerNames = $event->speakers
                            ->map(fn ($s) => trim(($s->user->name ?? '') . ' ' . ($s->user->family ?? '')))
                            ->filter()->values();

                        $isFinishedLike = in_array($event->display_status, ['finished', 'cancelled'], true);
                    @endphp

                    <article class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-slate-200
                                    hover:shadow-xl hover:shadow-slate-900/10 hover:-translate-y-1 transition-all">

                        {{-- Cover --}}
                        <a href="{{ route('public.events.show', $event->slug) }}"
                           class="block relative h-48 overflow-hidden bg-gradient-to-br {{ $gradients[$event->id % count($gradients)] }}">
                            @if ($cover)
                                <img src="{{ $cover }}" alt="{{ $event->title }}" loading="lazy"
                                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $isFinishedLike ? 'grayscale-[40%]' : '' }}">
                            @else
                                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/15"></div>
                                <div class="absolute -bottom-12 -left-8 w-36 h-36 rounded-full bg-white/10"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-14 h-14 text-white/70" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                                </div>
                            @endif

                            <span class="absolute top-3 right-3 inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full shadow-sm {{ $state['badge'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $state['dot'] }}"></span>{{ $state['label'] }}
                            </span>
                            @if ($locLabel)
                                <span class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold rounded-full shadow-sm {{ $locClass }}">{{ $locLabel }}</span>
                            @endif
                        </a>

                        <div class="flex-1 flex flex-col p-6">
                            <h2 class="text-base font-bold leading-7 text-slate-900 line-clamp-2 min-h-[3.5rem] group-hover:text-indigo-600 transition-colors">
                                <a href="{{ route('public.events.show', $event->slug) }}">{{ $event->title }}</a>
                            </h2>

                            {{-- Date --}}
                            <p class="mt-3 flex items-start gap-2 text-sm text-slate-600">
                                <svg class="w-4 h-4 mt-1 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 6v6l4 2M12 21a9 9 0 100-18 9 9 0 000 18z"/></svg>
                                <span>{{ $jFull($event->starts_at) ?? 'زمان برگزاری به‌زودی اعلام می‌شود.' }}</span>
                            </p>

                            {{-- Speakers (names only) --}}
                            @if ($speakerNames->isNotEmpty())
                                <div class="mt-4">
                                    <p class="text-xs text-slate-400">سخنرانان</p>
                                    <ul class="mt-1.5 space-y-1">
                                        @foreach ($speakerNames->take(4) as $name)
                                            <li class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>{{ $name }}
                                            </li>
                                        @endforeach
                                        @if ($speakerNames->count() > 4)
                                            <li class="text-xs text-slate-400">و {{ $fa($speakerNames->count() - 4) }} نفر دیگر</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            <div class="mt-auto pt-6">
                                <a href="{{ route('public.events.show', $event->slug) }}"
                                   class="block w-full py-2.5 text-center text-sm font-bold text-white rounded-xl bg-gradient-to-l from-indigo-600 to-fuchsia-500
                                          shadow-md shadow-indigo-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                    جزئیات و ثبت‌نام
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($events->hasPages())
                <div class="mt-12" dir="ltr">{{ $events->links() }}</div>
            @endif
        @endif
    </div>
</section>
@endsection