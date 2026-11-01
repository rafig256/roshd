@extends('admin.layouts.app')

@section('title', 'ایجاد کاربر جدید')
@section('page-title', 'ایجاد کاربر جدید')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        /* همان استایل‌های تمیز فایل ویرایش */
        .ts-wrapper .ts-control {
            @apply w-full px-4 py-2.5 text-sm rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none;
            min-height: 46px;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .ts-wrapper.focus .ts-control {
            @apply ring-2 ring-indigo-500 border-indigo-500;
        }
        .ts-dropdown {
            @apply text-sm rounded-lg border border-slate-300 shadow-lg;
            direction: rtl;
            text-align: right;
        }
        .ts-dropdown .option {
            @apply px-3 py-2 text-slate-700 hover:bg-slate-50 cursor-pointer;
        }
        .ts-dropdown .active {
            @apply bg-indigo-50 text-indigo-700;
        }
        .ts-wrapper.single .ts-control, .ts-wrapper.multi .ts-control {
            background-position: left 0.75rem center !important;
            padding-left: 2rem !important;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-3xl mx-auto">

        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-800">فرم ایجاد کاربر جدید</h2>
            <p class="mt-1 text-sm text-slate-500">اطلاعات کاربر جدید را در این صفحه ثبت کنید.</p>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Name --}}
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-slate-700">نام</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('name') border-red-400 @else border-slate-300 @enderror">
                    @error('name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Family --}}
                <div>
                    <label for="family" class="block mb-2 text-sm font-medium text-slate-700">نام خانوادگی</label>
                    <input type="text" id="family" name="family" value="{{ old('family') }}"
                           class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('family') border-red-400 @else border-slate-300 @enderror">
                    @error('family') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- National Code --}}
                <div>
                    <label for="national_code" class="block mb-2 text-sm font-medium text-slate-700">کد ملی</label>
                    <input type="text" id="national_code" name="national_code" dir="ltr" value="{{ old('national_code') }}"
                           class="w-full px-4 py-2.5 text-sm text-left rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('national_code') border-red-400 @else border-slate-300 @enderror">
                    @error('national_code') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Mobile --}}
                <div>
                    <label for="mobile" class="block mb-2 text-sm font-medium text-slate-700">موبایل</label>
                    <input type="text" id="mobile" name="mobile" dir="ltr" value="{{ old('mobile') }}"
                           class="w-full px-4 py-2.5 text-sm text-left rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('mobile') border-red-400 @else border-slate-300 @enderror">
                    @error('mobile') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-slate-700">رمز عبور</label>
                    <input type="password" id="password" name="password"
                           class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('password') border-red-400 @else border-slate-300 @enderror">
                    @error('password') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-slate-700">وضعیت</label>
                    <select id="status" name="status"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('status') border-red-400 @else border-slate-300 @enderror">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>فعال</option>
                        <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>مسدود</option>
                    </select>
                    @error('status') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Roles --}}
                <div class="col-span-2">
                    <label class="block mb-2 text-sm font-medium text-slate-700">نقش‌ها</label>
                    <select id="roles-select" name="roles[]" multiple placeholder="جستجوی نقش...">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->label ?? $role->name }}</option>
                        @endforeach
                    </select>
                    @error('roles') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-4">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                    ذخیره کاربر
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#roles-select', {
                plugins: ['remove_button'],
                maxItems: null,
                create: false,
                placeholder: 'جستجوی نقش...',
                dir: 'rtl',
            });
        });
    </script>
@endpush