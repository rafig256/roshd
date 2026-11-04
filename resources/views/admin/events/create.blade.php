@extends('admin.layouts.app')

@section('title', 'ایجاد رویداد')
@section('page-title', 'ایجاد رویداد')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">ایجاد رویداد جدید</h2>
        <p class="mt-1 text-sm text-slate-500">اطلاعات رویداد، زمان‌بندی، سخنرانان و برنامه‌ی جلسات را وارد کنید.</p>
    </div>

    @if (session('error'))
        <div class="mb-6 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg">{{ session('error') }}</div>
    @endif

    @include('admin.events._form', [
        'action' => route('admin.events.store'),
        'method' => 'POST',
        'event'  => null,
    ])
</div>
@endsection