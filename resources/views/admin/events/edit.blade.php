@extends('admin.layouts.app')

@section('title', 'ویرایش رویداد')
@section('page-title', 'ویرایش رویداد')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">ویرایش رویداد</h2>
        <p class="mt-1 text-sm text-slate-500">{{ $event->title }}</p>
    </div>

    @if (session('success'))
        <div class="mb-6 px-4 py-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-6 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg">{{ session('error') }}</div>
    @endif

    @include('admin.events._form', [
        'action' => route('admin.events.update', $event->id),
        'method' => 'PUT',
        'event'  => $event,
    ])
</div>
@endsection