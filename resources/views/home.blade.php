@extends('layouts.public')

@section('title', 'صفحه اصلی')

@section('content')

{{-- ============ 1. Hero ============ --}}
<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white">

    {{-- Subtle grid pattern --}}
    <div class="pointer-events-none absolute inset-0 opacity-60"
         style="background-image:
                    linear-gradient(to right, rgba(99,102,241,.08) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(99,102,241,.08) 1px, transparent 1px);
                background-size: 44px 44px;
                -webkit-mask-image: radial-gradient(ellipse at center, black 40%, transparent 75%);
                mask-image: radial-gradient(ellipse at center, black 40%, transparent 75%);"></div>

    {{-- Gradient blobs --}}
    <div class="pointer-events-none absolute -top-32 -right-24 w-[28rem] h-[28rem] rounded-full bg-indigo-400/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-40 -left-24 w-96 h-96 rounded-full bg-fuchsia-400/25 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-28 lg:pb-32 text-center">

        <span class="inline-flex items-center gap-2 px-4 py-1.5 mb-6 text-xs sm:text-sm font-medium text-indigo-700 bg-white rounded-full border border-indigo-100 shadow-sm">
            <span class="relative flex h-2 w-2">
                <span class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75 animate-ping"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
            </span>
            ثبت‌نام دوره جدید شتابدهی آغاز شد
        </span>

        <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold leading-tight text-slate-900">
            ایده خود را به
            <span class="bg-gradient-to-l from-indigo-600 via-fuchsia-500 to-orange-400 bg-clip-text text-transparent">واقعیت</span>
            تبدیل کنید
        </h1>

        <p class="mt-6 max-w-2xl mx-auto text-base sm:text-lg leading-8 text-slate-600">
            مرکز نوآوری شهری، خانه‌ی استارتاپ‌ها و کارآفرینان است؛ با منتورینگ تخصصی، فضای کاری مشترک
            و اتصال به شبکه‌ی سرمایه‌گذاران، مسیر رشد کسب‌وکار شما را کوتاه‌تر می‌کنیم.
        </p>

        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
            <a href="/register"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-bold text-white rounded-xl
                      bg-gradient-to-l from-indigo-600 to-fuchsia-500 shadow-xl shadow-indigo-500/30
                      hover:shadow-2xl hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all">
                ثبت‌نام فناور
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M19 12H5M11 6l-6 6 6 6"/>
                </svg>
            </a>
            <a href="#events"
               class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-slate-700 bg-white rounded-xl
                      border border-slate-200 shadow-sm hover:border-indigo-300 hover:text-indigo-600 hover:-translate-y-0.5 transition-all">
                مشاهده رویدادها
            </a>
        </div>

        {{-- Trust line --}}
        <div class="mt-12 flex items-center justify-center gap-3 text-sm text-slate-500">
            <div class="flex -space-x-2 space-x-reverse">
                <span class="w-8 h-8 rounded-full bg-indigo-400 border-2 border-white"></span>
                <span class="w-8 h-8 rounded-full bg-fuchsia-400 border-2 border-white"></span>
                <span class="w-8 h-8 rounded-full bg-orange-400 border-2 border-white"></span>
                <span class="w-8 h-8 rounded-full bg-emerald-400 border-2 border-white"></span>
            </div>
            <span>بیش از ۵۰ تیم نوآور تاکنون به ما اعتماد کرده‌اند</span>
        </div>
    </div>
</section>

{{-- ============ 2. Stats ============ --}}
<section class="relative -mt-10 z-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-px bg-slate-200 rounded-2xl overflow-hidden shadow-xl shadow-slate-900/5 border border-slate-200">
            @php
                $stats = [
                    ['value' => '۵۰+',    'label' => 'تیم فعال'],
                    ['value' => '۱۲۰+',   'label' => 'رویداد برگزار شده'],
                    ['value' => '۱۰۰۰+',  'label' => 'ایده محوری'],
                    ['value' => '۳۰+',    'label' => 'سرمایه‌گذار و مربی'],
                ];
            @endphp
            @foreach ($stats as $stat)
                <div class="bg-white px-6 py-8 text-center">
                    <div class="text-3xl sm:text-4xl font-extrabold bg-gradient-to-l from-indigo-600 to-fuchsia-500 bg-clip-text text-transparent">
                        {{ $stat['value'] }}
                    </div>
                    <div class="mt-2 text-sm font-medium text-slate-500">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ 3. Features ============ --}}
<section class="py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-2xl mx-auto">
            <span class="text-sm font-bold text-indigo-600">چرا مرکز نوآوری شهری؟</span>
            <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-slate-900">هر آنچه برای رشد استارتاپ‌تان نیاز دارید</h2>
            <p class="mt-4 text-slate-600 leading-8">
                از اولین ایده تا جذب سرمایه، در تمام مراحل کنار شما هستیم.
            </p>
        </div>

        <div class="mt-14 grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

            {{-- Mentoring --}}
            <div class="group relative p-8 bg-white rounded-2xl border border-slate-200 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9 18h6M10 21h4M12 3a6 6 0 00-3.5 10.9c.6.5 1 1.2 1 2V16h5v-.1c0-.8.4-1.5 1-2A6 6 0 0012 3z"/>
                    </svg>
                </div>
                <h3 class="mt-6 text-lg font-bold text-slate-900">منتورینگ تخصصی</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    از تجربه‌ی مربیان و کارآفرینان باتجربه بهره ببرید و مسیر رشد کسب‌وکارتان را با راهنمایی تخصصی هموار کنید.
                </p>
            </div>

            {{-- Co-working --}}
            <div class="group relative p-8 bg-white rounded-2xl border border-slate-200 hover:border-fuchsia-200 hover:shadow-xl hover:shadow-fuchsia-500/10 hover:-translate-y-1 transition-all">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-pink-600 text-white flex items-center justify-center shadow-lg shadow-fuchsia-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-5h6v5M9 11h.01M15 11h.01"/>
                    </svg>
                </div>
                <h3 class="mt-6 text-lg font-bold text-slate-900">فضای کار اشتراکی</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    در محیطی پویا و مجهز کار کنید، با سایر تیم‌ها شبکه‌سازی کنید و هزینه‌های اولیه‌ی راه‌اندازی را کاهش دهید.
                </p>
            </div>

            {{-- Investors --}}
            <div class="group relative p-8 bg-white rounded-2xl border border-slate-200 hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/10 hover:-translate-y-1 transition-all">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-400 to-rose-500 text-white flex items-center justify-center shadow-lg shadow-orange-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3 17l6-6 4 4 8-8M15 7h6v6"/>
                    </svg>
                </div>
                <h3 class="mt-6 text-lg font-bold text-slate-900">دسترسی به سرمایه‌گذار</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">
                    ایده و محصول خود را در رویدادهای اختصاصی به سرمایه‌گذاران و شرکای تجاری معرفی کنید.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ============ 4. Latest news & events ============ --}}
<section id="events" class="py-20 lg:py-28 bg-slate-100/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <span class="text-sm font-bold text-indigo-600">جدیدترین‌ها</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-slate-900">اخبار و رویدادهای اخیر</h2>
            </div>
            <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                مشاهده همه
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M19 12H5M11 6l-6 6 6 6"/>
                </svg>
            </a>
        </div>

        @php
            $items = [
                [
                    'type'  => 'رویداد',
                    'title' => 'رویداد معرفی استارتاپ‌ها به سرمایه‌گذاران',
                    'date'  => '۱۵ مهر ۱۴۰۴',
                    'text'  => 'فرصتی ویژه برای ارائه‌ی ایده و محصول خود به جمعی از سرمایه‌گذاران و مربیان حرفه‌ای.',
                    'color' => 'from-indigo-400 to-indigo-600',
                    'badge' => 'bg-indigo-50 text-indigo-700',
                ],
                [
                    'type'  => 'خبر',
                    'title' => 'آغاز دوره جدید شتابدهی تیم‌های فناور',
                    'date'  => '۱۰ مهر ۱۴۰۴',
                    'text'  => 'ثبت‌نام دوره‌ی جدید شتابدهی آغاز شد؛ تیم‌های علاقه‌مند می‌توانند درخواست خود را ثبت کنند.',
                    'color' => 'from-fuchsia-400 to-pink-600',
                    'badge' => 'bg-fuchsia-50 text-fuchsia-700',
                ],
                [
                    'type'  => 'رویداد',
                    'title' => 'کارگاه آموزشی مدل کسب‌وکار و اعتبارسنجی ایده',
                    'date'  => '۴ مهر ۱۴۰۴',
                    'text'  => 'در این کارگاه عملی، روش‌های اعتبارسنجی ایده و طراحی مدل کسب‌وکار را یاد می‌گیرید.',
                    'color' => 'from-orange-400 to-rose-500',
                    'badge' => 'bg-orange-50 text-orange-700',
                ],
            ];
        @endphp

        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach ($items as $item)
                <article class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-slate-200 hover:shadow-xl hover:shadow-slate-900/10 hover:-translate-y-1 transition-all">

                    {{-- Dummy image --}}
                    <div class="relative h-48 bg-gradient-to-br {{ $item['color'] }} flex items-center justify-center overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/15"></div>
                        <div class="absolute -bottom-12 -left-8 w-36 h-36 rounded-full bg-white/10"></div>
                        <svg class="relative w-14 h-14 text-white/80" fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM3 16l5-5 4 4 3-3 6 6M8.5 8.5h.01"/>
                        </svg>
                    </div>

                    <div class="flex-1 flex flex-col p-6">
                        <div class="flex items-center justify-between text-xs">
                            <span class="px-2.5 py-1 font-semibold rounded-full {{ $item['badge'] }}">{{ $item['type'] }}</span>
                            <span class="text-slate-400">{{ $item['date'] }}</span>
                        </div>
                        <h3 class="mt-4 text-base font-bold leading-7 text-slate-900 group-hover:text-indigo-600 transition-colors">
                            {{ $item['title'] }}
                        </h3>
                        <p class="mt-2 text-sm leading-7 text-slate-600">{{ $item['text'] }}</p>
                        <a href="#" class="mt-5 pt-4 border-t border-slate-100 inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600 mt-auto">
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
    </div>
</section>

{{-- ============ 5. Events (slider) ============ --}}
<section id="events" class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header + slider controls --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <span class="text-sm font-bold text-fuchsia-600">برنامه‌های پیش‌رو</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold text-slate-900">رویدادهای مرکز نوآوری</h2>
                <p class="mt-3 text-slate-600 leading-8">در رویدادهای تخصصی ما شرکت کنید، یاد بگیرید و شبکه‌ی ارتباطی خود را بسازید.</p>
            </div>

            <div class="flex items-center gap-3">
                <div id="events-controls" class="flex items-center gap-2">
                    <button type="button" id="events-prev" aria-label="رویدادهای قبلی"
                            class="w-11 h-11 rounded-full border border-slate-200 bg-white text-slate-600 flex items-center justify-center
                                   hover:border-indigo-300 hover:text-indigo-600 disabled:opacity-40 disabled:pointer-events-none transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <button type="button" id="events-next" aria-label="رویدادهای بعدی"
                            class="w-11 h-11 rounded-full border border-slate-200 bg-white text-slate-600 flex items-center justify-center
                                   hover:border-indigo-300 hover:text-indigo-600 disabled:opacity-40 disabled:pointer-events-none transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 5l-7 7 7 7"/></svg>
                    </button>
                </div>
                <a href="#" class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                    همه‌ی رویدادها
                </a>
            </div>
        </div>

        @php
            /*
             * نمونه داده‌ی ثابت. هر فیلد اختیاری است (null یا خالی = نمایش داده نمی‌شود).
             * status: planning | registering | closed | finished
             * fee: null = نامشخص (نمایش داده نمی‌شود) | 0 = رایگان | عدد = مبلغ به تومان
             */
            $events = collect([
                [
                    'title'      => 'رویداد معرفی استارتاپ‌ها به سرمایه‌گذاران',
                    'image'      => null,
                    'gradient'   => 'from-indigo-500 to-indigo-800',
                    'status'     => 'registering',
                    'format'     => 'حضوری',
                    'start_date' => '۱۵ آبان ۱۴۰۴', 'start_time' => '۱۶:۰۰',
                    'end_date'   => '۱۵ آبان ۱۴۰۴', 'end_time'   => '۲۰:۰۰',
                    'speakers'   => 'دکتر احمدی، مهندس کریمی، خانم رضایی',
                    'topic'      => 'جذب سرمایه و Pitch Deck',
                    'partners'   => 'پارک علم و فناوری، صندوق نوآوری',
                    'location'   => 'سالن همایش‌های مرکز نوآوری شهری',
                    'fee'        => 0,
                    'capacity'   => '۱۵۰ نفر',
                ],
                [
                    'title'      => 'کارگاه مدل کسب‌وکار و اعتبارسنجی ایده',
                    'image'      => null,
                    'gradient'   => 'from-fuchsia-500 to-pink-700',
                    'status'     => 'registering',
                    'format'     => 'ترکیبی',
                    'start_date' => '۲۲ آبان ۱۴۰۴', 'start_time' => '۰۹:۰۰',
                    'end_date'   => '۲۳ آبان ۱۴۰۴', 'end_time'   => '۱۷:۰۰',
                    'speakers'   => 'مهندس صالحی',
                    'topic'      => 'Business Model Canvas',
                    'partners'   => 'دانشگاه صنعتی',
                    'location'   => 'کارگاه شماره ۲ - طبقه‌ی دوم',
                    'fee'        => 450000,
                    'capacity'   => '۴۰ نفر',
                ],
                [
                    'title'      => 'هفته‌ی نوآوری شهری',
                    'image'      => null,
                    'gradient'   => 'from-emerald-500 to-teal-700',
                    'status'     => 'planning',
                    'format'     => null,
                    'start_date' => '۵ آذر ۱۴۰۴', 'start_time' => null,
                    'end_date'   => null, 'end_time' => null,
                    'speakers'   => null,
                    'topic'      => 'فناوری‌های شهری هوشمند',
                    'partners'   => null,
                    'location'   => null,
                    'fee'        => null,
                    'capacity'   => null,
                ],
                [
                    'title'      => 'مسابقه‌ی هکاتون حل مسائل شهری',
                    'image'      => null,
                    'gradient'   => 'from-orange-400 to-rose-600',
                    'status'     => 'closed',
                    'format'     => 'حضوری',
                    'start_date' => '۱ آذر ۱۴۰۴', 'start_time' => '۰۸:۰۰',
                    'end_date'   => '۳ آذر ۱۴۰۴', 'end_time'   => '۱۸:۰۰',
                    'speakers'   => 'داوران: دکتر موسوی، مهندس نظری',
                    'topic'      => 'حمل‌ونقل و محیط زیست',
                    'partners'   => 'شهرداری، شرکت دانش‌بنیان آرمان',
                    'location'   => 'مرکز نوآوری شهری',
                    'fee'        => 200000,
                    'capacity'   => '۱۲۰ نفر',
                ],
                [
                    'title'      => 'نشست تخصصی هوش مصنوعی در کسب‌وکار',
                    'image'      => null,
                    'gradient'   => 'from-cyan-500 to-blue-700',
                    'status'     => 'finished',
                    'format'     => 'آنلاین',
                    'start_date' => '۱۰ مهر ۱۴۰۴', 'start_time' => '۱۸:۰۰',
                    'end_date'   => null, 'end_time' => '۲۰:۳۰',
                    'speakers'   => 'دکتر فرهمند',
                    'topic'      => 'کاربرد AI در محصولات نوپا',
                    'partners'   => null,
                    'location'   => 'برگزاری آنلاین',
                    'fee'        => 0,
                    'capacity'   => null,
                ],
                [
                    'title'      => 'رویداد شبکه‌سازی کارآفرینان',
                    'image'      => null,
                    'gradient'   => 'from-violet-500 to-purple-800',
                    'status'     => 'finished',
                    'format'     => 'حضوری',
                    'start_date' => null, 'start_time' => null,
                    'end_date'   => null, 'end_time' => null,
                    'speakers'   => null,
                    'topic'      => null,
                    'partners'   => null,
                    'location'   => null,
                    'fee'        => null,
                    'capacity'   => null,
                ],
            ])->take(6); // حداکثر ۶ رویداد

            $statusMap = [
                'planning'    => ['label' => 'در حال برنامه‌ریزی', 'badge' => 'bg-amber-100 text-amber-800',     'dot' => 'bg-amber-500'],
                'registering' => ['label' => 'در حال ثبت‌نام',      'badge' => 'bg-emerald-100 text-emerald-800', 'dot' => 'bg-emerald-500 animate-pulse'],
                'closed'      => ['label' => 'پایان ثبت‌نام',       'badge' => 'bg-rose-100 text-rose-800',       'dot' => 'bg-rose-500'],
                'finished'    => ['label' => 'برگزار شده',          'badge' => 'bg-slate-200 text-slate-700',     'dot' => 'bg-slate-500'],
            ];

            // آیکون‌ها (مسیر SVG)
            $icons = [
                'clock'    => 'M12 6v6l4 2M12 21a9 9 0 100-18 9 9 0 000 18z',
                'mic'      => 'M12 14a3 3 0 003-3V6a3 3 0 10-6 0v5a3 3 0 003 3zM19 11a7 7 0 01-14 0M12 18v3',
                'topic'    => 'M7 8h10M7 12h10M7 16h6M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z',
                'partners' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z',
                'pin'      => 'M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11zM12 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z',
                'ticket'   => 'M3 9a2 2 0 002-2h14a2 2 0 002 2v0a2 2 0 000 6v0a2 2 0 00-2 2H5a2 2 0 00-2-2v0a2 2 0 000-6zM13 7v10',
                'users'    => 'M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0116 0',
            ];

            // ترکیب تاریخ و ساعت به‌صورت امن
            $joinDateTime = function ($date, $time) {
                if ($date && $time) return $date . ' - ساعت ' . $time;
                if ($date)          return $date;
                if ($time)          return 'ساعت ' . $time;
                return null;
            };
        @endphp

        {{-- Slider --}}
        <div class="mt-12 -mx-4 sm:mx-0">
            <div id="events-slider"
                 class="events-scroller flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth px-4 sm:px-0 pb-6">

                @foreach ($events as $event)
                    @php
                        $st = $statusMap[$event['status'] ?? 'planning'] ?? $statusMap['planning'];

                        $start = $joinDateTime($event['start_date'] ?? null, $event['start_time'] ?? null);
                        $end   = $joinDateTime($event['end_date']   ?? null, $event['end_time']   ?? null);

                        // ساخت ردیف‌ها؛ فقط ردیف‌هایی که مقدار دارند نمایش داده می‌شوند
                        $rows = [];
                        if ($start) $rows[] = ['icon' => 'clock',    'label' => 'شروع',        'value' => $start];
                        if ($end)   $rows[] = ['icon' => 'clock',    'label' => 'پایان',       'value' => $end];
                        if (!empty($event['topic']))    $rows[] = ['icon' => 'topic',    'label' => 'موضوع اصلی',   'value' => $event['topic']];
                        if (!empty($event['speakers'])) $rows[] = ['icon' => 'mic',      'label' => 'سخنرانان',     'value' => $event['speakers']];
                        if (!empty($event['partners'])) $rows[] = ['icon' => 'partners', 'label' => 'همکاران برگزاری', 'value' => $event['partners']];
                        if (!empty($event['location'])) $rows[] = ['icon' => 'pin',      'label' => 'مکان',         'value' => $event['location']];
                        if (!empty($event['capacity'])) $rows[] = ['icon' => 'users',    'label' => 'ظرفیت',        'value' => $event['capacity']];

                        $feeText = null;
                        if (array_key_exists('fee', $event) && $event['fee'] !== null) {
                            $feeText = ((int) $event['fee'] === 0)
                                ? 'رایگان'
                                : number_format((int) $event['fee']) . ' تومان';
                        }
                        if ($feeText) $rows[] = ['icon' => 'ticket', 'label' => 'هزینه‌ی شرکت', 'value' => $feeText];

                        $isFinished = ($event['status'] ?? '') === 'finished';
                    @endphp

                    <article class="events-card group shrink-0 snap-start flex flex-col
                                    w-[85%] sm:w-[calc((100%-1.5rem)/2)] lg:w-[calc((100%-3rem)/3)]
                                    bg-white rounded-2xl overflow-hidden border border-slate-200
                                    hover:shadow-xl hover:shadow-slate-900/10 hover:-translate-y-1 transition-all">

                        {{-- Banner --}}
                        <div class="relative h-48 overflow-hidden bg-gradient-to-br {{ $event['gradient'] ?? 'from-slate-500 to-slate-700' }}">
                            @if (!empty($event['image']))
                                <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}"
                                     class="absolute inset-0 w-full h-full object-cover {{ $isFinished ? 'grayscale-[40%]' : '' }}">
                            @else
                                <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/15"></div>
                                <div class="absolute -bottom-12 -left-8 w-36 h-36 rounded-full bg-white/10"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-14 h-14 text-white/70" fill="none" stroke="currentColor" stroke-width="1.5"
                                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Status badge --}}
                            <span class="absolute top-3 right-3 inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full shadow-sm {{ $st['badge'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span>
                                {{ $st['label'] }}
                            </span>

                            {{-- Format badge (optional) --}}
                            @if (!empty($event['format']))
                                <span class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold text-white bg-black/40 backdrop-blur rounded-full">
                                    {{ $event['format'] }}
                                </span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="flex-1 flex flex-col p-6">
                            <h3 class="text-base font-bold leading-7 text-slate-900 line-clamp-2 min-h-[3.5rem] group-hover:text-indigo-600 transition-colors">
                                {{ $event['title'] }}
                            </h3>

                            @if (count($rows))
                                <dl class="mt-4 space-y-3">
                                    @foreach ($rows as $row)
                                        <div class="flex items-start gap-3">
                                            <span class="mt-0.5 w-8 h-8 shrink-0 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                    <path d="{{ $icons[$row['icon']] }}"/>
                                                </svg>
                                            </span>
                                            <div class="min-w-0">
                                                <dt class="text-xs text-slate-400">{{ $row['label'] }}</dt>
                                                <dd class="text-sm font-medium leading-6 text-slate-700 line-clamp-2
                                                           {{ $row['label'] === 'هزینه‌ی شرکت' && $row['value'] === 'رایگان' ? '!text-emerald-600 font-bold' : '' }}">
                                                    {{ $row['value'] }}
                                                </dd>
                                            </div>
                                        </div>
                                    @endforeach
                                </dl>
                            @endif

                            {{-- اگر زمان برگزاری مشخص نیست --}}
                            @if (!$start && !$end)
                                <p class="mt-3 px-3 py-2 text-xs text-amber-700 bg-amber-50 rounded-lg">
                                    زمان برگزاری به‌زودی اعلام می‌شود.
                                </p>
                            @endif

                            {{-- Action (همیشه پایین کارت) --}}
                            <div class="mt-auto pt-6">
                                @switch($event['status'] ?? 'planning')
                                    @case('registering')
                                        <a href="#"
                                           class="block w-full py-2.5 text-center text-sm font-bold text-white rounded-xl bg-gradient-to-l from-indigo-600 to-fuchsia-500
                                                  shadow-md shadow-indigo-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                            ثبت‌نام در رویداد
                                        </a>
                                        @break
                                    @case('closed')
                                        <span class="block w-full py-2.5 text-center text-sm font-semibold text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed">
                                            ثبت‌نام بسته شد
                                        </span>
                                        @break
                                    @case('finished')
                                        <a href="#"
                                           class="block w-full py-2.5 text-center text-sm font-semibold text-slate-700 border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                                            مشاهده گزارش و تصاویر
                                        </a>
                                        @break
                                    @default
                                        <span class="block w-full py-2.5 text-center text-sm font-semibold text-amber-700 bg-amber-50 rounded-xl cursor-not-allowed">
                                            به‌زودی
                                        </span>
                                @endswitch
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============ 6. Final CTA ============ --}}
<section class="py-20 lg:py-28">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-900 to-indigo-700 px-6 py-16 sm:px-14 sm:py-20 text-center shadow-2xl shadow-indigo-900/30">

            <div class="pointer-events-none absolute -top-20 -right-20 w-80 h-80 rounded-full bg-fuchsia-500/30 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-16 w-80 h-80 rounded-full bg-cyan-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute inset-0 opacity-30"
                 style="background-image: radial-gradient(rgba(255,255,255,.25) 1px, transparent 1px); background-size: 24px 24px;"></div>

            <div class="relative">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight">
                    آماده‌اید قدم اول را بردارید؟
                </h2>
                <p class="mt-5 max-w-xl mx-auto text-base sm:text-lg leading-8 text-indigo-100">
                    همین امروز ثبت‌نام کنید و به جامعه‌ی نوآوران و کارآفرینان مرکز نوآوری شهری بپیوندید.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
                    <a href="/register"
                       class="w-full sm:w-auto px-8 py-3.5 text-base font-bold text-indigo-700 bg-white rounded-xl shadow-xl
                              hover:bg-indigo-50 hover:-translate-y-0.5 transition-all">
                        همین حالا ثبت‌نام کنید
                    </a>
                    <a href="#"
                       class="w-full sm:w-auto px-8 py-3.5 text-base font-bold text-white rounded-xl border border-white/30
                              hover:bg-white/10 transition-colors">
                        تماس با ما
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .events-scroller { scrollbar-width: none; -ms-overflow-style: none; }
    .events-scroller::-webkit-scrollbar { display: none; }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        const slider   = document.getElementById('events-slider');
        const prev     = document.getElementById('events-prev');
        const next     = document.getElementById('events-next');
        const controls = document.getElementById('events-controls');
        if (!slider || !prev || !next) return;

        const isRtl = () => getComputedStyle(slider).direction === 'rtl';

        function step() {
            const card = slider.querySelector('.events-card');
            return card ? card.offsetWidth + 24 : slider.clientWidth * 0.9;
        }

        function scrollByStep(forward) {
            const dir = (forward ? 1 : -1) * (isRtl() ? -1 : 1);
            slider.scrollBy({ left: dir * step(), behavior: 'smooth' });
        }

        function update() {
            const max      = slider.scrollWidth - slider.clientWidth;
            const pos      = Math.abs(slider.scrollLeft);
            const overflow = max > 4;

            controls.classList.toggle('hidden', !overflow); // اگر همه‌ی کارت‌ها جا شدند، دکمه‌ها مخفی شوند
            prev.disabled = pos <= 4;
            next.disabled = pos >= max - 4;
        }

        prev.addEventListener('click', () => scrollByStep(false));
        next.addEventListener('click', () => scrollByStep(true));
        slider.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        update();
    })();
</script>
@endpush