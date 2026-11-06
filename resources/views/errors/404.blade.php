@extends('layouts.public')

@section('title', 'صفحه یافت نشد')

@section('content')
<section class="relative min-h-[70vh] flex items-center justify-center bg-gradient-to-b from-indigo-50 via-white to-white px-4 sm:px-6 lg:px-8 py-20">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-md mx-auto text-center">

        {{-- Error Code --}}
        <div class="mb-6">
            <span class="font-extrabold text-9xl sm:text-12xl bg-gradient-to-r from-indigo-600 via-fuchsia-500 to-orange-400 bg-clip-text text-transparent leading-none tracking-tight">
                404
            </span>
        </div>

        {{-- Title --}}
        <h1 class="mb-4 text-3xl sm:text-4xl font-extrabold text-slate-900">
            صفحه یافت نشد
        </h1>

        {{-- Message --}}
        <p class="mb-8 text-base sm:text-lg leading-8 text-slate-600">
            متأسفانه صفحه‌ای که به دنبال آن بودید پیدا نشد. ممکن است آدرس را اشتباه وارد کرده باشید یا صفحه حذف شده باشد.
        </p>

        {{-- Action Button --}}
        <a href="{{ route('home') }}"
           class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-bold text-white rounded-xl
                 bg-gradient-to-l from-indigo-600 to-fuchsia-500 shadow-xl shadow-indigo-500/30
                 hover:shadow-2xl hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all">
            بازگشت به صفحه اصلی
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</section>
@endsection