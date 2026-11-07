@extends('layouts.public')

@section('title', 'تماس با ما')

@section('content')
<section class="mx-auto max-w-3xl px-4 py-12 sm:py-16" dir="rtl">
    <header class="mb-8 border-b border-gray-200 pb-6">
        <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">تماس با ما</h1>
    </header>

    <div class="mb-10 text-base leading-loose text-gray-700 sm:text-lg">
        {!! nl2br(e($settings['contact_us'] ?? '')) !!}
    </div>

    @if(!empty($settings['contact_address']) || !empty($settings['contact_phone']))
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
        <ul class="space-y-6">
            @if(!empty($settings['contact_address']))
            <li class="flex items-start gap-4">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <div>
                    <h2 class="mb-1 text-sm font-semibold text-gray-500">آدرس</h2>
                    <p class="leading-relaxed text-gray-900">{{ $settings['contact_address'] }}</p>
                </div>
            </li>
            @endif

            @if(!empty($settings['contact_phone']))
            <li class="flex items-start gap-4">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.95.68l1.5 4.49a1 1 0 01-.5 1.21l-2.26 1.13a11 11 0 005.52 5.52l1.13-2.26a1 1 0 011.21-.5l4.49 1.5a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.72 21 3 14.28 3 6V5z"/>
                    </svg>
                </span>
                <div>
                    <h2 class="mb-1 text-sm font-semibold text-gray-500">تلفن</h2>
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $settings['contact_phone']) }}"
                       class="text-gray-900 hover:text-indigo-600" dir="ltr">{{ $settings['contact_phone'] }}</a>
                </div>
            </li>
            @endif
        </ul>
    </div>
    @endif
</section>
@endsection
