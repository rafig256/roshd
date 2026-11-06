@extends('layouts.public')

@section('title', 'خطای سرور')

@section('content')
<section class="relative min-h-[70vh] flex items-center justify-center bg-gradient-to-b from-indigo-50 via-white to-white px-4 sm:px-6 lg:px-8 py-20">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-md mx-auto text-center">

        {{-- Error Code --}}
        <div class="mb-6">
            <span class="font-extrabold text-9xl sm:text-12xl bg-gradient-to-r from-slate-600 via-slate-700 to-slate-800 bg-clip-text text-transparent leading-none tracking-tight">
                500
            </span>
        </div>

        {{-- Title --}}
        <h1 class="mb-4 text-3xl sm:text-4xl font-extrabold text-slate-900">
            خطای سرور
        </h1>

        {{-- Message --}}
        <p class="mb-8 text-base sm:text-lg leading-8 text-slate-600">
            متأسفانه در پردازش درخواست شما خطایی رخ داد. لطفاً بعداً دوباره تلاش کنید.
        </p>

        {{-- Action Button --}}
        <a href="{{ route('home') }}"
           class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-bold text-white rounded-xl
                 bg-gradient-to-l from-slate-600 to-slate-700 shadow-xl shadow-slate-500/30
                 hover:shadow-2xl hover:shadow-slate-500/40 hover:-translate-y-0.5 transition-all">
            بازگشت به صفحه اصلی
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</section>
@endsection