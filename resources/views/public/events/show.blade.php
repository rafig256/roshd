@extends('layouts.public')

@section('title', $event->title)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $event->description), 150))

@section('content')
@php
    $fa    = fn ($v) => strtr((string) $v, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']);
    $jDate = fn ($d) => $d ? $fa(\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($d))->format('j F Y')) : null;
    $jTime = fn ($d) => $d ? $fa(\Carbon\Carbon::parse($d)->format('H:i')) : null;
    $jFull = fn ($d) => $d ? $jDate($d) . ' - ساعت ' . $jTime($d) : null;

    $locationLabels = ['physical' => 'حضوری', 'online' => 'آنلاین', 'hybrid' => 'ترکیبی'];
    $locValue = $event->location_type instanceof \BackedEnum ? $event->location_type->value : $event->location_type;
    $locLabel = $locationLabels[$locValue] ?? null;

    $statusMap = [
        'registering' => ['label' => 'در حال ثبت‌نام', 'badge' => 'bg-emerald-100 text-emerald-800'],
        'open'        => ['label' => 'ورود آزاد',      'badge' => 'bg-indigo-100 text-indigo-800'],
        'closed'      => ['label' => 'پایان ثبت‌نام',  'badge' => 'bg-rose-100 text-rose-800'],
        'finished'    => ['label' => 'برگزار شده',     'badge' => 'bg-slate-200 text-slate-700'],
        'cancelled'   => ['label' => 'لغو شده',        'badge' => 'bg-red-100 text-red-800'],
    ];
    $display = $event->display_status;
    $state   = $statusMap[$display] ?? $statusMap['open'];

    $cover = (!empty($event->cover_image_path) && \Illuminate\Support\Facades\Route::has('file.event-cover'))
        ? route('file.event-cover', basename($event->cover_image_path)) : null;

    // هزینه (ریال -> تومان)
    $feeText = null;
    if ($event->requires_payment && !is_null($event->price_amount)) {
        $feeText = $fa(number_format(intdiv((int) $event->price_amount, 10))) . ' تومان';
    } elseif (!$event->requires_payment) {
        $feeText = 'رایگان';
    }

    // مکان (لینک آنلاین عمداً نمایش داده نمی‌شود)
    $placeRows = [];
    if (in_array($locValue, ['physical', 'hybrid'], true) && $event->location_address) $placeRows[] = ['label' => 'آدرس', 'value' => $event->location_address];
    if (in_array($locValue, ['online', 'hybrid'], true) && $event->platform)            $placeRows[] = ['label' => 'پلتفرم', 'value' => $event->platform];

    $speakers = $event->speakers;
    $sessions = $event->sessions;
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white">
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-64 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        <a href="{{ route('public.events.index') }}"
           class="inline-flex items-center gap-1.5 mb-6 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            بازگشت به رویدادها
        </a>

        {{-- ============ Header ============ --}}
        <header>
            <div class="relative h-56 sm:h-80 rounded-2xl overflow-hidden border border-slate-200 shadow-xl shadow-slate-900/10 bg-gradient-to-br from-indigo-500 to-fuchsia-600">
                @if ($cover)
                    <img src="{{ $cover }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover">
                @else
                    <div class="absolute -top-10 -right-10 w-56 h-56 rounded-full bg-white/15"></div>
                    <div class="absolute -bottom-16 -left-10 w-48 h-48 rounded-full bg-white/10"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-20 h-20 text-white/70" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                    </div>
                @endif
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $state['badge'] }}">{{ $state['label'] }}</span>
                @if ($locLabel)
                    <span class="px-3 py-1 text-xs font-semibold text-slate-700 bg-slate-100 rounded-full">{{ $locLabel }}</span>
                @endif
                @foreach ($event->categories as $cat)
                    <span class="px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-full">{{ $cat->name ?? $cat->title }}</span>
                @endforeach
            </div>

            <h1 class="mt-4 text-3xl sm:text-4xl font-extrabold leading-snug text-slate-900">{{ $event->title }}</h1>

            <p class="mt-4 flex items-center gap-2 text-sm text-slate-600">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                {{ $jFull($event->starts_at) ?? 'زمان برگزاری به‌زودی اعلام می‌شود.' }}
            </p>
        </header>

        <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- ============ Main column ============ --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Description --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-slate-900">درباره‌ی رویداد</h2>
                    @if (!empty($event->description))
                        <div class="mt-4 text-base leading-9 text-slate-700 break-words">{!! nl2br(e($event->description)) !!}</div>
                    @else
                        <p class="mt-4 text-sm text-slate-400">توضیحاتی برای این رویداد ثبت نشده است.</p>
                    @endif
                </div>

                {{-- Speakers --}}
                @if ($speakers->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                        <h2 class="text-lg font-bold text-slate-900">سخنرانان</h2>
                        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($speakers as $speaker)
                                @php
                                    $spName = trim(($speaker->user->name ?? '') . ' ' . ($speaker->user->family ?? ''));
                                @endphp
                                <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50/60">
                                    <div class="w-14 h-14 shrink-0 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 text-white flex items-center justify-center text-xl font-extrabold shadow-md shadow-indigo-500/20">
                                        {{ mb_substr($spName !== '' ? $spName : '؟', 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900">{{ $spName !== '' ? $spName : 'سخنران' }}</p>
                                        @if (!empty($speaker->title))
                                            <p class="mt-0.5 text-xs font-semibold text-indigo-600">{{ $speaker->title }}</p>
                                        @endif
                                        @if (!empty($speaker->bio))
                                            <p class="mt-2 text-xs leading-6 text-slate-600">{{ $speaker->bio }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Agenda timeline --}}
                @if ($sessions->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                        <h2 class="text-lg font-bold text-slate-900">برنامه‌ی زمانی</h2>

                        <ol class="relative mt-6 mr-2 pr-7 border-r-2 border-indigo-100 space-y-8">
                            @foreach ($sessions as $session)
                                <li class="relative">
                                    <span class="absolute -right-[2.35rem] top-1 w-4 h-4 rounded-full bg-white border-4 border-indigo-500"></span>

                                    @if (!empty($session->time_string))
                                        <span class="inline-block px-3 py-1 text-xs font-bold text-indigo-700 bg-indigo-50 rounded-full">{{ $session->time_string }}</span>
                                    @endif

                                    <h3 class="mt-2 text-base font-bold text-slate-900">{{ $session->title }}</h3>

                                    @if (!empty($session->location) || !empty($session->duration))
                                        <div class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                            @if (!empty($session->location))
                                                <span class="inline-flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11zM12 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/></svg>
                                                    {{ $session->location }}
                                                </span>
                                            @endif
                                            @if (!empty($session->duration))
                                                <span class="inline-flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 6v6l4 2M12 21a9 9 0 100-18 9 9 0 000 18z"/></svg>
                                                    {{ $session->duration }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    @if (!empty($session->description))
                                        <p class="mt-2 text-sm leading-7 text-slate-600 whitespace-pre-line">{{ $session->description }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif
            </div>

            {{-- ============ Sidebar (sticky CTA) ============ --}}
            <aside class="lg:col-span-1">
                <div class="lg:sticky lg:top-28 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5 p-6">

                    <h2 class="text-base font-bold text-slate-900">اطلاعات رویداد</h2>

                    <dl class="mt-4 space-y-4 text-sm">
                        @if ($event->starts_at)
                            <div><dt class="text-xs text-slate-400">شروع</dt><dd class="mt-0.5 font-medium text-slate-800">{{ $jFull($event->starts_at) }}</dd></div>
                        @endif
                        @if ($event->ends_at)
                            <div><dt class="text-xs text-slate-400">پایان</dt><dd class="mt-0.5 font-medium text-slate-800">{{ $jFull($event->ends_at) }}</dd></div>
                        @endif
                        @if ($event->registration_required && $event->registration_deadline)
                            <div><dt class="text-xs text-slate-400">مهلت ثبت‌نام</dt><dd class="mt-0.5 font-medium text-rose-700">{{ $jFull($event->registration_deadline) }}</dd></div>
                        @endif
                        @if ($locLabel)
                            <div><dt class="text-xs text-slate-400">نوع برگزاری</dt><dd class="mt-0.5 font-medium text-slate-800">{{ $locLabel }}</dd></div>
                        @endif
                        @foreach ($placeRows as $row)
                            <div><dt class="text-xs text-slate-400">{{ $row['label'] }}</dt><dd class="mt-0.5 font-medium text-slate-800">{{ $row['value'] }}</dd></div>
                        @endforeach
                        <div><dt class="text-xs text-slate-400">ظرفیت</dt><dd class="mt-0.5 font-medium text-slate-800">{{ $event->capacity ? $fa($event->capacity) . ' نفر' : 'نامحدود' }}</dd></div>
                        @if ($feeText)
                            <div><dt class="text-xs text-slate-400">هزینه‌ی شرکت</dt><dd class="mt-0.5 font-bold {{ $feeText === 'رایگان' ? 'text-emerald-600' : 'text-slate-800' }}">{{ $feeText }}</dd></div>
                        @endif
                    </dl>

                    <div class="mt-6 pt-6 border-t border-slate-100">
                        @if ($display === 'cancelled')
                            <p class="px-4 py-3 text-sm font-semibold leading-7 text-red-800 bg-red-100 rounded-xl">این رویداد لغو شده است.</p>

                        @elseif ($display === 'finished')
                            <p class="px-4 py-3 text-sm font-semibold leading-7 text-slate-700 bg-slate-100 rounded-xl">این رویداد برگزار شده است.</p>

                        @elseif ($display === 'open')
                            <p class="px-4 py-3 text-sm font-semibold leading-7 text-indigo-800 bg-indigo-100 rounded-xl">شرکت در این رویداد نیازی به ثبت‌نام ندارد.</p>

                        @elseif ($display === 'closed')
                            <p class="px-4 py-3 text-sm font-semibold leading-7 text-amber-800 bg-amber-100 rounded-xl">مهلت ثبت‌نام این رویداد به پایان رسیده است.</p>

                        @else
                            @guest
                                <p class="mb-3 text-sm leading-7 text-slate-600">برای ثبت‌نام ابتدا وارد شوید.</p>
                                <a href="{{ route('login') }}"
                                   class="block w-full py-3 text-center text-sm font-bold text-white rounded-xl bg-gradient-to-l from-indigo-600 to-fuchsia-500
                                          shadow-md shadow-indigo-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                    ثبت‌نام در رویداد
                                </a>
                            @endguest

                            @auth
                                @if (\Illuminate\Support\Facades\Route::has('public.events.register'))
                                    <form method="POST" action="{{ route('public.events.register', $event->slug) }}">
                                        @csrf
                                        <button type="submit"
                                                class="block w-full py-3 text-center text-sm font-bold text-white rounded-xl bg-gradient-to-l from-indigo-600 to-fuchsia-500
                                                       shadow-md shadow-indigo-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                            ثبت‌نام در رویداد
                                        </button>
                                    </form>
                                @else
                                    <a href="#"
                                       class="block w-full py-3 text-center text-sm font-bold text-white rounded-xl bg-gradient-to-l from-indigo-600 to-fuchsia-500
                                              shadow-md shadow-indigo-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                        ثبت‌نام در رویداد
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection