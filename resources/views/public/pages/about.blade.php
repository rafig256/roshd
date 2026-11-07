@extends('layouts.public')

@section('title', 'درباره ما')

@section('content')
<section class="mx-auto max-w-3xl px-4 py-12 sm:py-16" dir="rtl">
    <header class="mb-8 border-b border-gray-200 pb-6">
        <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">درباره ما</h1>
    </header>

    <article class="text-base leading-loose text-gray-700 sm:text-lg">
        {!! nl2br(e($settings['about_us'] ?? '')) !!}
    </article>
</section>
@endsection
