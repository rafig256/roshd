<!DOCTYPE html>
<html lang="fa" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'خانه') | مرکز نوآوری شهری</title>
    <meta name="description" content="@yield('meta_description', 'مرکز نوآوری شهری؛ شتاب‌دهنده ایده‌ها و حامی کسب‌وکارهای نوپا و کارآفرینان')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Vazirmatn', 'Tahoma', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-slate-50 font-sans text-slate-800 antialiased">

@php
    $navLinks = [
        ['label' => 'صفحه اصلی',     'href' => '#'],
        ['label' => 'اخبار',          'href' => '#'],
        ['label' => 'رویدادها',       'href' => '#'],
        ['label' => 'واحدهای فناور',  'href' => '#'],
        ['label' => 'درباره ما',      'href' => '#'],
        ['label' => 'تماس با ما',     'href' => '#'],
    ];
@endphp

{{-- ============ Navbar ============ --}}
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 lg:h-20 flex items-center justify-between gap-4">

            {{-- Right: brand --}}
            <a href="/" class="flex items-center gap-3 shrink-0">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-fuchsia-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z"/>
                    </svg>
                </span>
                <span class="text-lg font-extrabold text-slate-900">مرکز نوآوری شهری</span>
            </a>

            {{-- Center: desktop menu --}}
            <nav class="hidden lg:flex items-center gap-1">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}"
                       class="px-3.5 py-2 text-sm font-medium text-slate-600 rounded-lg hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Left: auth buttons --}}
            <div class="hidden lg:flex items-center gap-2">
                <a href="#"
                   class="px-4 py-2 text-sm font-semibold text-slate-700 rounded-lg hover:bg-slate-100 transition-colors">
                    ورود
                </a>
                <a href="/register"
                   class="px-5 py-2 text-sm font-semibold text-white rounded-lg bg-gradient-to-l from-indigo-600 to-fuchsia-500
                          shadow-md shadow-indigo-500/30 hover:shadow-lg hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all">
                    ثبت‌نام
                </a>
            </div>

            {{-- Mobile toggle (pure HTML, no JS needed) --}}
            <label for="mobile-menu-toggle"
                   class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </label>
        </div>
    </div>

    {{-- Mobile menu --}}
    <input type="checkbox" id="mobile-menu-toggle" class="peer hidden">
    <div class="hidden peer-checked:block lg:hidden border-t border-slate-200 bg-white">
        <nav class="px-4 py-3 space-y-1">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}"
                   class="block px-3 py-2.5 text-sm font-medium text-slate-700 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>
        <div class="px-4 pb-4 pt-2 grid grid-cols-2 gap-2">
            <a href="#"
               class="py-2.5 text-center text-sm font-semibold text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50">
                ورود
            </a>
            <a href="/register"
               class="py-2.5 text-center text-sm font-semibold text-white rounded-lg bg-gradient-to-l from-indigo-600 to-fuchsia-500">
                ثبت‌نام
            </a>
        </div>
    </div>
</header>

{{-- ============ Main ============ --}}
<main class="flex-1">
    @yield('content')
</main>

{{-- ============ Footer ============ --}}
<footer class="bg-slate-900 text-slate-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- About --}}
            <div class="lg:col-span-2">
                <a href="/" class="inline-flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-fuchsia-500 flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z"/>
                        </svg>
                    </span>
                    <span class="text-lg font-extrabold text-white">مرکز نوآوری شهری</span>
                </a>
                <p class="mt-4 text-sm leading-7 max-w-md">
                    مرکز نوآوری شهری بستری برای رشد ایده‌ها و تیم‌های نوپا است. ما با فراهم‌کردن فضای کار، مشاوره تخصصی،
                    شبکه‌سازی و اتصال به سرمایه‌گذاران، کارآفرینان را در مسیر تبدیل ایده به کسب‌وکار همراهی می‌کنیم.
                </p>
            </div>

            {{-- Quick links --}}
            <div>
                <h3 class="mb-4 text-sm font-bold text-white">دسترسی سریع</h3>
                <ul class="space-y-2.5 text-sm">
                    @foreach ($navLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}" class="hover:text-white transition-colors">{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="mb-4 text-sm font-bold text-white">اطلاعات تماس</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2.5">
                        <svg class="w-5 h-5 shrink-0 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11z"/>
                            <circle cx="12" cy="10" r="2.5"/>
                        </svg>
                        <span>آدرس مرکز نوآوری شهری (در تنظیمات سایت ویرایش می‌شود)</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 shrink-0 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M3 5a2 2 0 012-2h3.3a1 1 0 01.95.68l1.5 4.5a1 1 0 01-.5 1.2l-2.26 1.13a11 11 0 005.52 5.52l1.13-2.26a1 1 0 011.2-.5l4.5 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/>
                        </svg>
                        <span dir="ltr">021-00000000</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 shrink-0 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7zm0 0l9 6 9-6"/>
                        </svg>
                        <span dir="ltr">info@example.com</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs">
            <p>© {{ date('Y') }} مرکز نوآوری شهری — تمامی حقوق محفوظ است.</p>
            <p class="text-slate-500">ساخته‌شده برای رشد کسب‌وکارهای نوپا</p>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>