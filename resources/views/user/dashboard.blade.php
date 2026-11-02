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
                            
                            {{-- دکمه ویرایش فقط برای درخواست‌های تایید شده --}}
                            <a href="{{ route('user.innovator-request.edit', $latestRequest->id) }}"
                               class="mt-4 inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl
                                      hover:bg-indigo-100 hover:border-indigo-300 transition-colors sm:self-start">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/>
                                </svg>
                                ویرایش درخواست
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- 2. My ideas --}}
            <div class="group flex flex-col p-7 bg-white rounded-2xl border border-slate-200
                        hover:border-fuchsia-200 hover:shadow-xl hover:shadow-fuchsia-500/10 hover:-translate-y-1 transition-all">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-pink-600 text-white flex items-center justify-center shadow-lg shadow-fuchsia-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-3-2-4 2-4-2-3 2V6a2 2 0 012-2z"/>
                    </svg>
                </div>
                <h2 class="mt-5 text-lg font-bold text-slate-900">ایده‌های من</h2>
                <p class="mt-2 text-sm leading-7 text-slate-600">
                    طرح‌ها و ایده‌هایی که ثبت کرده‌اید را مشاهده کنید و وضعیت بررسی هر کدام را پیگیری نمایید.
                </p>
                <a href="#"
                   class="mt-6 inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-fuchsia-700 bg-fuchsia-50 rounded-xl
                          hover:bg-fuchsia-100 transition-colors sm:self-start">
                    مشاهده ایده‌ها
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M19 12H5M11 6l-6 6 6 6"/>
                    </svg>
                </a>
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
                <a href="#"
                   class="mt-6 inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-xl
                          hover:bg-emerald-100 transition-colors sm:self-start">
                    ویرایش اطلاعات
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M19 12H5M11 6l-6 6 6 6"/>
                    </svg>
                </a>
            </div>

            {{-- 4. Logout (red card) --}}
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