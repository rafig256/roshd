@extends('layouts.public')

@section('title', 'دسترسی غیرمجاز')

@section('content')
<section class="relative min-h-[70vh] flex items-center justify-center bg-gradient-to-b from-indigo-50 via-white to-white px-4 sm:px-6 lg:px-8 py-20">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-md mx-auto text-center">

        {{-- Error Code --}}
        <div class="mb-6">
            <span class="font-extrabold text-9xl sm:text-12xl bg-gradient-to-r from-red-500 via-rose-500 to-orange-400 bg-clip-text text-transparent leading-none tracking-tight">
                403
            </span>
        </div>

        {{-- Title --}}
        <h1 class="mb-4 text-3xl sm:text-4xl font-extrabold text-slate-900">
            دسترسی غیرمجاز
        </h1>

        {{-- Message --}}
        <p class="mb-8 text-base sm:text-lg leading-8 text-slate-600">
            شما دسترسی لازم برای مشاهده این صفحه را ندارید. در صورت نیاز با مدیر سیستم تماس بگیرید.
        </p>

        {{-- Action Button --}}
        <a href="{{ route('home') }}"
           class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-bold text-white rounded-xl
                 bg-gradient-to-l from-red-500 to-rose-500 shadow-xl shadow-red-500/30
                 hover:shadow-2xl hover:shadow-red-500/40 hover:-translate-y-0.5 transition-all">
            بازگشت به صفحه اصلی
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</section>
@endsection