@extends('layouts.public')

@section('title', 'رویدادهای من')

@section('content')
@php
    // ارقام فارسی و تاریخ شمسی
    $fa    = fn ($v) => strtr((string) $v, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']);
    $jFull = function ($d) use ($fa) {
        if (!$d) return null;
        $c = \Carbon\Carbon::parse($d);
        return $fa(\Morilog\Jalali\Jalalian::fromCarbon($c)->format('j F Y')) . ' - ساعت ' . $fa($c->format('H:i'));
    };

    $regStatusMap = [
        'confirmed' => ['label' => 'ثبت‌نام شده', 'class' => 'bg-emerald-100 text-emerald-800', 'dot' => 'bg-emerald-500'],
        'pending'   => ['label' => 'در انتظار',   'class' => 'bg-amber-100 text-amber-800',     'dot' => 'bg-amber-500'],
        'cancelled' => ['label' => 'لغو شده',     'class' => 'bg-red-100 text-red-800',         'dot' => 'bg-red-500'],
    ];

    $locationLabels = ['physical' => 'حضوری', 'online' => 'آنلاین', 'hybrid' => 'ترکیبی'];
    $isPaginator    = method_exists($registrations, 'hasPages');
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white min-h-[calc(100vh-5rem)]">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Back --}}
        <a href="{{ route('user.dashboard') }}"
           class="inline-flex items-center gap-1.5 mb-6 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            بازگشت به پنل کاربری
        </a>

        {{-- Page header --}}
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-pink-600 text-white flex items-center justify-center shadow-lg shadow-fuchsia-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">رویدادهای من</h1>
                <p class="mt-2 text-sm sm:text-base leading-8 text-slate-600">
                    رویدادهایی که در آن‌ها ثبت‌نام کرده‌اید را اینجا می‌بینید.
                </p>
            </div>
        </div>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mt-8 flex items-center gap-2 px-4 py-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mt-8 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        @if ($registrations->isEmpty())
            {{-- Empty state --}}
            <div class="mt-10 bg-white rounded-2xl border border-dashed border-slate-300 py-16 px-6 text-center">
                <div class="mx-auto w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                </div>
                <h2 class="mt-5 text-lg font-bold text-slate-800">هنوز در هیچ رویدادی ثبت‌نام نکرده‌اید</h2>
                <p class="mt-2 text-sm leading-7 text-slate-500">رویدادهای پیش‌رو را ببینید و در اولین رویداد شرکت کنید.</p>
                <a href="{{ route('public.events.index') }}"
                   class="mt-6 inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-white rounded-xl
                          bg-gradient-to-l from-indigo-600 to-fuchsia-500 shadow-md shadow-indigo-500/30
                          hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    مشاهده تمام رویدادها
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                </a>
            </div>
        @else
            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($registrations as $registration)
                    @php
                        $event = $registration->event;
                    @endphp
                    @continue(!$event)

                    @php
                        $regRaw   = $registration->status;
                        $regValue = $regRaw instanceof \BackedEnum ? $regRaw->value : $regRaw;
                        $regState = $regStatusMap[$regValue] ?? ['label' => $regValue ?: 'ثبت‌نام شده', 'class' => 'bg-slate-100 text-slate-700', 'dot' => 'bg-slate-400'];

                        $locValue = $event->location_type instanceof \BackedEnum ? $event->location_type->value : $event->location_type;
                        $locLabel = $locationLabels[$locValue] ?? null;

                        // مکان
                        $place = match (true) {
                            $locValue === 'online'  => 'برگزاری آنلاین' . ($event->platform ? ' - ' . $event->platform : ''),
                            $locValue === 'hybrid'  => trim(($event->location_address ?: '') . ($event->platform ? ' / آنلاین: ' . $event->platform : ' / آنلاین'), ' /'),
                            default                 => $event->location_address,
                        };

                        $startsAt   = $jFull($event->starts_at);
                        $isFinished = $event->ends_at ? \Carbon\Carbon::parse($event->ends_at)->isPast() : false;
                    @endphp

                    <article class="group flex flex-col p-6 bg-white rounded-2xl border border-slate-200
                                    hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all">

                        {{-- Badges --}}
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full {{ $regState['class'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $regState['dot'] }}"></span>{{ $regState['label'] }}
                            </span>
                            @if ($locLabel)
                                <span class="px-3 py-1 text-xs font-semibold text-slate-700 bg-slate-100 rounded-full">{{ $locLabel }}</span>
                            @endif
                            @if ($isFinished)
                                <span class="px-3 py-1 text-xs font-semibold text-slate-500 bg-slate-100 rounded-full">برگزار شده</span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h2 class="mt-4 text-lg font-bold leading-8 text-slate-900 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                            {{ $event->title }}
                        </h2>

                        {{-- Details --}}
                        <dl class="mt-4 space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="mt-0.5 w-8 h-8 shrink-0 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 6v6l4 2M12 21a9 9 0 100-18 9 9 0 000 18z"/></svg>
                                </span>
                                <div class="min-w-0">
                                    <dt class="text-xs text-slate-400">زمان برگزاری</dt>
                                    <dd class="text-sm font-medium leading-6 text-slate-700">{{ $startsAt ?? 'به‌زودی اعلام می‌شود' }}</dd>
                                </div>
                            </div>

                            @if (!empty($place))
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 w-8 h-8 shrink-0 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11zM12 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/></svg>
                                    </span>
                                    <div class="min-w-0">
                                        <dt class="text-xs text-slate-400">مکان</dt>
                                        <dd class="text-sm font-medium leading-6 text-slate-700 line-clamp-2">{{ $place }}</dd>
                                    </div>
                                </div>
                            @endif
                        </dl>

                        {{-- Action --}}
                        <div class="mt-auto pt-6">
                            <a href="{{ route('public.events.show', $event->slug) }}"
                               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-indigo-700 bg-indigo-50 rounded-xl
                                      hover:bg-indigo-100 transition-colors">
                                مشاهده رویداد
                                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($isPaginator && $registrations->hasPages())
                <div class="mt-10" dir="ltr">
                    {{ $registrations->links() }}
                </div>
            @endif
        @endif
    </div>
</section>
@endsection