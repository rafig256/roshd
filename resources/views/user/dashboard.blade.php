@extends('layouts.public')

@section('title', 'پنل کاربری')

@section('content')
<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white min-h-[calc(100vh-5rem)]">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Welcome header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 shrink-0 rounded-2xl bg-gradient-to-br from-indigo-600 to-fuchsia-500 text-white flex items-center justify-center text-2xl font-extrabold shadow-lg shadow-indigo-500/30">
                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">پنل کاربری</h1>
                    <p class="mt-1 text-slate-600">خوش آمدید، {{ auth()->user()->name }} عزیز</p>
                </div>
            </div>

            {{-- Logout (header) --}}
            <form method="POST" action="{{ route('user.logout') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-red-600 bg-white border border-red-200 rounded-xl
                               hover:bg-red-50 hover:border-red-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                    خروج از حساب
                </button>
            </form>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="mt-8 flex items-center gap-2 px-4 py-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Cards grid --}}
        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- 1. Innovator request --}}
            <div class="group flex flex-col p-7 bg-white rounded-2xl border border-slate-200
                        hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9 18h6M10 21h4M12 3a6 6 0 00-3.5 10.9c.6.5 1 1.2 1 2V16h5v-.1c0-.8.4-1.5 1-2A6 6 0 0012 3z"/>
                    </svg>
                </div>
                <h2 class="mt-5 text-lg font-bold text-slate-900">درخواست فناوری</h2>

                @if(!$latestRequest || $latestRequest->status === 'rejected')
                    {{-- حالت اول: کاربر درخواستی ندارد یا درخواستش رد شده است --}}
                    <p class="mt-2 text-sm leading-7 text-slate-600">
                        @if($latestRequest && $latestRequest->status === 'rejected')
                            <span class="block mb-2 text-red-600 font-bold">درخواست قبلی شما رد شده است.</span>
                        @endif
                        برای تبدیل شدن به فناور، درخواست خود را ثبت کنید تا پس از بررسی،
                        به امکانات و خدمات ویژه‌ی فناوران دسترسی پیدا کنید.
                    </p>
                    <a href="{{ $latestRequest && $latestRequest->status === 'rejected' ? route('user.innovator-request.edit', $latestRequest->id) : route('user.innovator-request.create') }}"
                       class="mt-6 inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl
                              bg-gradient-to-l from-indigo-600 to-fuchsia-500 shadow-md shadow-indigo-500/30
                              hover:shadow-lg hover:-translate-y-0.5 transition-all sm:self-start">
                        {{ $latestRequest && $latestRequest->status === 'rejected' ? 'ویرایش درخواست رد شده' : 'ثبت درخواست' }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M19 12H5M11 6l-6 6 6 6"/>
                        </svg>
                    </a>

                @else
                    {{-- حالت دوم: درخواست Pending یا Approved است --}}
                    <div class="mt-4 space-y-3">
                        <p class="text-sm text-slate-600">وضعیت درخواست شما:
                            @if($latestRequest->status === 'pending')
                                <span class="text-yellow-600 font-bold ml-2 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-yellow-50 text-xs">در حال بررسی</span>
                            @else
                                <span class="text-green-600 font-bold ml-2 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-50 text-xs">تأیید شده</span>
                            @endif
                        </p>
                        <p class="text-xs text-slate-500">تاریخ ثبت: {{ \Morilog\Jalali\Jalalian::fromCarbon($latestRequest->created_at)->format('Y/m/d') }}</p>
                        
                        @if($latestRequest->status === 'approved')
                            <p class="text-xs text-emerald-600">شما اکنون به‌عنوان فناور عضو هستید.</p>
                        @endif
                    </div>
                @endif
            </div>


{{-- 2. My Plan --}}
<div class="group flex flex-col p-7 bg-white rounded-2xl border border-slate-200
            hover:border-fuchsia-200 hover:shadow-xl hover:shadow-fuchsia-500/10 hover:-translate-y-1 transition-all">

    {{-- آیکون --}}
    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-pink-600 text-white flex items-center justify-center shadow-lg shadow-fuchsia-500/30">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-4 2-4-2-3 2V6a2 2 0 012-2z"/>
        </svg>
    </div>

    @if(!auth()->user()->is_innovator)

        {{-- کاربر هنوز فناور نیست --}}
        <h2 class="mt-5 text-lg font-bold text-slate-900">ثبت طرح (ایده محوری)</h2>

        <p class="mt-2 text-sm leading-7 text-slate-600">
            برای ثبت ایده محوری، ابتدا باید درخواست فناوری شما تأیید شود.
        </p>

    @elseif(!$latestPlan)

        {{-- حالت ۱: هنوز طرحی ثبت نکرده --}}
        <h2 class="mt-5 text-lg font-bold text-slate-900">ثبت طرح (ایده محوری)</h2>

        <p class="mt-2 text-sm leading-7 text-slate-600">
            اگر یک ایده نوآورانه دارید، طرح خود را ثبت کنید تا پس از بررسی، امکانات پشتیبانی و سرمایه‌گذاری در اختیار شما قرار گیرد.
        </p>

        <a href="{{ route('user.plans.create') }}"
           class="mt-6 inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl
                  bg-gradient-to-l from-fuchsia-500 to-pink-600 shadow-md shadow-fuchsia-500/30
                  hover:shadow-lg hover:-translate-y-0.5 transition-all sm:self-start">
            ثبت طرح جدید
        </a>

    @else

        {{-- مشخصات مشترک طرح --}}
        <h2 class="mt-5 text-lg font-bold text-slate-900">طرح (ایده) من</h2>

        <div class="mt-4 space-y-3">

            <p class="text-sm text-slate-600">
                عنوان طرح:
                <span class="font-semibold text-slate-800 ml-2">
                    {{ $latestPlan->title }}
                </span>
            </p>

            <p class="text-xs text-slate-500">
                تاریخ ثبت:
                {{ \Morilog\Jalali\Jalalian::fromCarbon($latestPlan->created_at)->format('Y/m/d') }}
            </p>

            {{-- حالت ۲: در انتظار بررسی --}}
            @if($latestPlan->status === \App\Enums\PlanStatus::Pending)

                <p class="text-sm text-slate-600">
                    وضعیت طرح:
                    <span class="text-yellow-600 font-bold inline-flex px-2.5 py-1 rounded-full bg-yellow-50 text-xs">
                        در حال بررسی
                    </span>
                </p>

                <p class="text-xs text-amber-600 mt-2">
                    طرح شما توسط کارشناسان در حال بررسی است. لطفاً منتظر اعلام نتیجه باشید.
                </p>

            {{-- حالت ۳: تأیید شده --}}
            @elseif($latestPlan->status === \App\Enums\PlanStatus::Approved)

                <p class="text-sm text-slate-600">
                    وضعیت طرح:
                    <span class="text-green-600 font-bold inline-flex px-2.5 py-1 rounded-full bg-green-50 text-xs">{{$latestPlan->status->label()}}</span>
                </p>

                <p class="text-xs text-emerald-600 mt-2">
                    طرح شما تأیید شده است. هر فناور فقط مجاز به داشتن یک طرح تأیید شده است.
                </p>

            {{-- حالت ۴: رد شده --}}
            @elseif($latestPlan->status === \App\Enums\PlanStatus::Rejected)

                <p class="text-sm text-slate-600">
                    وضعیت طرح:
                    <span class="text-red-600 font-bold inline-flex px-2.5 py-1 rounded-full bg-red-50 text-xs">{{ $latestPlan->status->label() }}</span>
                </p>

                @if($latestPlan->rejection_reason)
                    <p class="text-sm text-red-700 bg-red-50 p-3 rounded-lg border border-red-100 whitespace-pre-line">
                        <strong>دلیل رد:</strong>
                        {{ $latestPlan->rejection_reason }}
                    </p>
                @endif

                <a href="{{ route('user.plans.edit', $latestPlan->id) }}"
                   class="mt-4 inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-fuchsia-700 bg-fuchsia-50 border border-fuchsia-200 rounded-xl
                          hover:bg-fuchsia-100 hover:border-fuchsia-300 transition-colors sm:self-start">
                    ویرایش طرح
                </a>
            @elseif($latestPlan->status === \App\Enums\PlanStatus::Archived)

                <p class="text-sm text-gray-600">
                    وضعیت طرح:
                    <span class="text-gray-600 bg-gray-100 px-2.5 py-1 rounded-full text-xs">
                        {{ $latestPlan->status->label() }}
                    </span>
                </p>

            @else

                {{-- وضعیت ناشناخته --}}
                <p class="text-sm text-slate-500">
                    وضعیت طرح مشخص نیست. لطفاً با پشتیبانی تماس بگیرید.
                </p>

            @endif

        </div>
    @endif

</div>


            {{-- 3. Edit profile --}}
            <div class="group flex flex-col p-7 bg-white rounded-2xl border border-slate-200
                        hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-1 transition-all">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0113-6.2M15 21l1-4 5-5a1.4 1.4 0 012 2l-5 5-3 1z"/>
                    </svg>
                </div>
                <h2 class="mt-5 text-lg font-bold text-slate-900">ویرایش پروفایل</h2>
                <p class="mt-2 text-sm leading-7 text-slate-600">
                    اطلاعات شخصی، شماره موبایل و رمز عبور خود را به‌روز نگه دارید.
                </p>
<a href="{{ route('user.profile.edit') }}"
                    class="mt-6 inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-xl
                           hover:bg-emerald-100 transition-colors sm:self-start">
                    ویرایش اطلاعات
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M19 12H5M11 6l-6 6 6 6"/>
                    </svg>
                </a>
            </div>

            {{-- Team card --}}
@php
    $user = auth()->user();
    $team        = $latestOrganization ?? null;
    $expiresAt     = $team?->team_expires_at ? \Carbon\Carbon::parse($team->team_expires_at) : null;
    $isExpired     = $expiresAt ? $expiresAt->copy()->endOfDay()->isPast() : false;
    $expiresJalali = $expiresAt ? \Morilog\Jalali\Jalalian::fromCarbon($expiresAt)->format('Y/m/d') : null;
    $planStatus = $latestPlan?->status instanceof \BackedEnum ? $latestPlan->status->value : $latestPlan?->status;
    $canCreate  = $user->is_innovator && $planStatus === 'approved';

    $orgStatusValue = $team?->status instanceof \BackedEnum ? $team->status->value : ($team?->status ?? null);
    $orgStatusLabel = $team?->status instanceof \BackedEnum && method_exists($team->status, 'label')
        ? $team->status->label()
        : match ($orgStatusValue) {
            'pending'  => 'در انتظار تأیید',
            'approved' => 'تأیید شده',
            'rejected' => 'رد شده',
            default    => $orgStatusValue ?: 'نامشخص',
        };
    $orgStatusClass = match ($orgStatusValue) {
        'approved' => 'bg-emerald-100 text-emerald-800',
        'pending'  => 'bg-amber-100 text-amber-800',
        'rejected' => 'bg-red-100 text-red-800',
        default    => 'bg-slate-100 text-slate-700',
    };
@endphp

<div class="group flex flex-col p-7 bg-white rounded-2xl border border-slate-200
            hover:border-cyan-200 hover:shadow-xl hover:shadow-cyan-500/10 hover:-translate-y-1 transition-all">
    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 text-white flex items-center justify-center shadow-lg shadow-cyan-500/30">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </div>

    <h2 class="mt-5 text-lg font-bold text-slate-900">تیم من</h2>

    @if ($team)
        {{-- حالت ۳: کاربر تیم دارد --}}
        <div class="mt-3 flex items-center gap-3">
            @if (!empty($latestOrganization->logo_path))
                <img src="{{ route('file.logo', basename($latestOrganization->logo_path)) }}" alt="{{ $latestOrganization->name }}"
                    class="w-11 h-11 rounded-xl object-cover border border-slate-200">
            @endif
            <div class="min-w-0">
                <p class="text-sm font-bold text-slate-800 truncate">{{ $team->name }}</p>
                <span class="mt-1 inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $orgStatusClass }}">
                    {{ $orgStatusLabel }}
                </span>
            </div>
        </div>
        @if ($expiresJalali)
                <p class="mt-0.5 text-xs {{ $isExpired ? 'font-bold text-red-600' : 'text-slate-500' }}">
                    اعتبار تیم تا:
                    <span dir="ltr" class="inline-block">{{ $expiresJalali }}</span>
                    @if ($isExpired) (منقضی شده) @endif
                </p>
            @endif
        <p class="mt-3 text-sm leading-7 text-slate-600">
            اطلاعات واحد فناور، اعضا و فعالیت‌های خود را از اینجا مدیریت کنید.
        </p>
<a href="{{ route('user.organizations.show', $team->id) }}"
           class="mt-6 inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-cyan-700 bg-cyan-50 rounded-xl
                 hover:bg-cyan-100 transition-colors sm:self-start">
            مدیریت تیم
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M19 12H5M11 6l-6 6 6 6"/>
            </svg>
        </a>

    @elseif ($canCreate)
        {{-- حالت ۲: فناور با طرح تأییدشده و بدون تیم --}}
        <p class="mt-2 text-sm leading-7 text-slate-600">
            طرح شما تأیید شده است. اکنون می‌توانید تیم (واحد فناور) خود را ایجاد کنید و فعالیت رسمی‌تان را آغاز نمایید.
        </p>
        <a href="{{ route('user.organizations.create') }}"
           class="mt-6 inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl
                  bg-gradient-to-l from-cyan-500 to-blue-600 shadow-md shadow-cyan-500/30
                  hover:shadow-lg hover:-translate-y-0.5 transition-all sm:self-start">
            ایجاد تیم
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M19 12H5M11 6l-6 6 6 6"/>
            </svg>
        </a>

    @else
        {{-- حالت ۱: فناور نیست یا طرح تأییدشده ندارد --}}
        <p class="mt-2 text-sm leading-7 text-slate-600">
            برای ایجاد تیم، ابتدا باید طرح شما تأیید شود.
        </p>
        <span class="mt-6 inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-slate-400 bg-slate-100 rounded-xl cursor-not-allowed sm:self-start">
            هنوز امکان‌پذیر نیست
        </span>
    @endif
</div>

            {{-- 5. My Events --}}
            <div class="group flex flex-col p-7 bg-white rounded-2xl border border-slate-200
                        hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                    </svg>
                </div>
                <h2 class="mt-5 text-lg font-bold text-slate-900">رویدادهای من</h2>
                <p class="mt-2 text-sm leading-7 text-slate-600">
                    لیست رویدادهایی که در آن‌ها ثبت‌نام کرده‌ید را مشاهده کنید.
                </p>
                <a href="{{ route('user.events.index') }}"
                   class="mt-6 inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl
                          bg-gradient-to-l from-indigo-500 to-indigo-700 shadow-md shadow-indigo-500/30
                          hover:shadow-lg hover:-translate-y-0.5 transition-all sm:self-start">
                    مشاهده رویدادها
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M19 12H5M11 6l-6 6 6 6"/>
                    </svg>
                </a>
            </div>

            {{-- 6. Logout (red card) --}}
            <div class="group flex flex-col p-7 bg-red-50/60 rounded-2xl border border-red-200
                        hover:border-red-300 hover:shadow-xl hover:shadow-red-500/10 hover:-translate-y-1 transition-all">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 text-white flex items-center justify-center shadow-lg shadow-red-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                </div>
                <h2 class="mt-5 text-lg font-bold text-red-700">خروج از حساب</h2>
                <p class="mt-2 text-sm leading-7 text-red-700/80">
                    برای حفظ امنیت حساب‌تان، پس از پایان کار از پنل خارج شوید؛ مخصوصاً اگر از دستگاه عمومی استفاده می‌کنید.
                </p>
                <form method="POST" action="{{ route('user.logout') }}" class="mt-6">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-red-600 rounded-xl
                                   hover:bg-red-700 shadow-md shadow-red-500/30 transition-colors">
                        خروج
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection