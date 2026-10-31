@php
    $menu = [
        ['label' => 'داشبورد',  'route' => 'admin.dashboard', 'icon' => 'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3v-6h6v6h3a1 1 0 001-1V10'],
        ['label' => 'کاربران',  'route' => 'admin.users', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z'],
        ['label' => 'طرح‌ها',   'route' => 'admin.plans', 'icon' => 'M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-4 2-4-2-3 2V6a2 2 0 012-2z'],
        ['label' => 'سازمان‌ها', 'route' => 'admin.organizations', 'icon' => 'M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6'],
        ['label' => 'اخبار',    'route' => 'admin.news', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2zM7 8h6M7 12h10M7 16h10'],
        ['label' => 'رویدادها', 'route' => 'admin.events', 'icon' => 'M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z'],
        ['label' => 'تنظیمات',  'route' => 'admin.settings.index', 'icon' => 'M10.3 4.3a1.7 1.7 0 013.4 0 1.7 1.7 0 002.5 1l.1-.1a1.7 1.7 0 012.4 2.4l-.1.1a1.7 1.7 0 001 2.5 1.7 1.7 0 010 3.4 1.7 1.7 0 00-1 2.5l.1.1a1.7 1.7 0 01-2.4 2.4l-.1-.1a1.7 1.7 0 00-2.5 1 1.7 1.7 0 01-3.4 0 1.7 1.7 0 00-2.5-1l-.1.1a1.7 1.7 0 01-2.4-2.4l.1-.1a1.7 1.7 0 00-1-2.5 1.7 1.7 0 010-3.4 1.7 1.7 0 001-2.5l-.1-.1a1.7 1.7 0 012.4-2.4l.1.1a1.7 1.7 0 002.5-1zM12 9a3 3 0 100 6 3 3 0 000-6z'],
    ];
@endphp

<nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
    @foreach ($menu as $item)
        @php
            // بررسی می‌کنیم آیا روت فعلی با این آیتم مطابقت دارد یا خیر
            // از * استفاده می‌کنیم تا صفحات زیرمجموعه (مثل admin.news.create) هم فعال شوند
            $isActive = request()->routeIs($item['route'] . '*');
        @endphp
        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ $isActive ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="{{ $item['icon'] }}"/>
            </svg>
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>