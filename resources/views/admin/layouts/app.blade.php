<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'پنل مدیریت') | مرکز نوآوری شهری</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
<body class="bg-slate-100 font-sans text-slate-800 antialiased">

<div x-data class="min-h-screen flex">

    {{-- Sidebar (right side in RTL) --}}
    <aside class="w-64 shrink-0 bg-slate-900 text-slate-300 flex flex-col min-h-screen">

        {{-- Logo placeholder --}}
        <div class="h-16 flex items-center justify-center gap-3 border-b border-slate-800 px-4">
            <div class="w-10 h-10 rounded-lg bg-indigo-500 flex items-center justify-center text-white font-bold">
                لوگو
            </div>
            <span class="text-white font-semibold text-sm leading-tight">
                مرکز نوآوری شهری
            </span>
        </div>

        {{-- Menu --}}
        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
            @php
                $menu = [
                    ['label' => 'داشبورد',  'icon' => 'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3v-6h6v6h3a1 1 0 001-1V10'],
                    ['label' => 'کاربران',  'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['label' => 'طرح‌ها',   'icon' => 'M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-4 2-4-2-3 2V6a2 2 0 012-2z'],
                    ['label' => 'سازمان‌ها', 'icon' => 'M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6'],
                    ['label' => 'اخبار',    'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2zM7 8h6M7 12h10M7 16h10'],
                    ['label' => 'رویدادها', 'icon' => 'M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z'],
                    ['label' => 'تنظیمات',  'icon' => 'M10.3 4.3a1.7 1.7 0 013.4 0 1.7 1.7 0 002.5 1l.1-.1a1.7 1.7 0 012.4 2.4l-.1.1a1.7 1.7 0 001 2.5 1.7 1.7 0 010 3.4 1.7 1.7 0 00-1 2.5l.1.1a1.7 1.7 0 01-2.4 2.4l-.1-.1a1.7 1.7 0 00-2.5 1 1.7 1.7 0 01-3.4 0 1.7 1.7 0 00-2.5-1l-.1.1a1.7 1.7 0 01-2.4-2.4l.1-.1a1.7 1.7 0 00-1-2.5 1.7 1.7 0 010-3.4 1.7 1.7 0 001-2.5l-.1-.1a1.7 1.7 0 012.4-2.4l.1.1a1.7 1.7 0 002.5-1zM12 9a3 3 0 100 6 3 3 0 000-6z'],
                ];
            @endphp

            @foreach ($menu as $index => $item)
                <a href="#"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                          {{ $index === 0 ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="{{ $item['icon'] }}"/>
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="px-4 py-4 border-t border-slate-800 text-xs text-slate-500 text-center">
            نسخه ۱.۰
        </div>
    </aside>

    {{-- Main column --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top navigation bar --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6">
            <h1 class="text-base font-semibold text-slate-700">
                @yield('page-title', 'پنل مدیریت')
            </h1>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                        م
                    </div>
                    <div class="leading-tight">
                        <div class="text-sm font-semibold text-slate-800">مدیر سیستم</div>
                        <div class="text-xs text-slate-500">مدیر کل</div>
                    </div>
                </div>

                <span class="w-px h-8 bg-slate-200"></span>

                <form method="POST" action="#">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                        </svg>
                        خروج
                    </button>
                </form>
            </div>
        </header>

        {{-- Main content --}}
        <main class="flex-1 p-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 min-h-[calc(100vh-10rem)]">
                @yield('content')
            </div>
        </main>

        <footer class="px-6 py-4 text-xs text-slate-400 text-center">
            © مرکز نوآوری شهری — تمامی حقوق محفوظ است.
        </footer>
    </div>
</div>

@stack('scripts')
</body>
</html>