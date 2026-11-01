@extends('admin.layouts.app')

@section('title', 'ویرایش کاربر')
@section('page-title', 'ویرایش کاربر')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <div class="max-w-3xl mx-auto">

        {{-- Page header --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-800">ویرایش کاربر</h2>
            <p class="mt-1 text-sm text-slate-500">اطلاعات کاربری و نقش‌های این کاربر را در این صفحه ویرایش کنید.</p>
        </div>

        {{-- Success message --}}
        @if (session('success'))
            <div class="mb-6 flex items-center gap-2 px-4 py-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Edit form --}}
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Name --}}
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-slate-700">نام</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none
                              @error('name') border-red-400 @else border-slate-300 @enderror">
                @error('name')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Family --}}
            <div>
                <label for="family" class="block mb-2 text-sm font-medium text-slate-700">نام خانوادگی</label>
                <input type="text"
                       id="family"
                       name="family"
                       value="{{ old('family', $user->family) }}"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none
                              @error('family') border-red-400 @else border-slate-300 @enderror">
                @error('family')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- National Code --}}
            <div>
                <label for="national_code" class="block mb-2 text-sm font-medium text-slate-700">کد ملی</label>
                <input type="text"
                       id="national_code"
                       name="national_code"
                       dir="ltr"
                       value="{{ old('national_code', $user->national_code) }}"
                       class="w-full px-4 py-2.5 text-sm text-left rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none
                              @error('national_code') border-red-400 @else border-slate-300 @enderror">
                @error('national_code')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mobile --}}
            <div>
                <label for="mobile" class="block mb-2 text-sm font-medium text-slate-700">موبایل</label>
                <input type="text"
                       id="mobile"
                       name="mobile"
                       dir="ltr"
                       value="{{ old('mobile', $user->mobile) }}"
                       class="w-full px-4 py-2.5 text-sm text-left rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none
                              @error('mobile') border-red-400 @else border-slate-300 @enderror">
                @error('mobile')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block mb-2 text-sm font-medium text-slate-700">وضعیت</label>
                <select id="status"
                        name="status"
                        class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none
                               @error('status') border-red-400 @else border-slate-300 @enderror">
                    <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>فعال</option>
                    <option value="blocked" {{ old('status', $user->status) === 'blocked' ? 'selected' : '' }}>مسدود</option>
                </select>
                @error('status')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Roles --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-slate-700">نقش‌ها</label>
                <select id="roles-select"
                        name="roles[]"
                        multiple
                        class="hidden">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                                {{ in_array($role->name, $user->getRoleNames()->toArray()) ? 'selected' : '' }}>
                            {{ $role->label ?? $role->name }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1.5 text-xs text-slate-500">نقش فناور طی روند تخصیص می یابد.</p>
                @error('roles')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            </div>

            {{-- Submit --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg
                               hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7"/>
                    </svg>
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const originalSelect = document.getElementById('roles-select');
            
            if (originalSelect) {
                const tomSelect = new TomSelect('#roles-select', {
                    plugins: ['dropdown_input'],
                    maxOptions: 100,
                    maxItems: null,
                    placeholder: 'جستجوی نقش...',
                    searchPlaceholder: 'جستجوی نقش...',
                    sortField: {
                        field: 'text',
                        direction: 'asc'
                    },
                    dropdownParent: 'body',
                    controlInput: '<input type="text" class="w-full px-4 py-2.5 text-sm rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none" placeholder="جستجوی نقش...">',
                    render: {
                        option: function(data, escapeFn) {
                            return '<div class="flex items-center px-3 py-2"><span class="text-sm text-slate-700">' + escapeFn(data.text) + '</span></div>';
                        }
                    }
                });

                // Replicate Tom Select styles to match Tailwind inputs
                const updateStyles = () => {
                    const tsControl = document.querySelector('.ts-control');
                    const tsDropdown = document.querySelector('.ts-dropdown');
                    
                    if (tsControl) {
                        tsControl.className = 'w-full px-4 py-2.5 text-sm rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none';
                        tsControl.style.minHeight = '42px';
                        tsControl.style.padding = '0.625rem 1rem';
                        tsControl.style.direction = 'rtl';
                    }
                    
                    if (tsDropdown) {
                        tsDropdown.className = 'ts-dropdown w-full border border-slate-300 rounded-lg bg-white shadow-lg';
                    }
                };
                
                // Apply styles after Tom Select initializes
                setTimeout(updateStyles, 100);
                
                // Observe DOM changes and apply styles dynamically
                const observer = new MutationObserver(updateStyles);
                const tsContainer = document.querySelector('.ts-control');
                if (tsContainer) {
                    observer.observe(tsContainer, { attributes: true, childList: true, subtree: true });
                }
            }
        });
    </script>
@endsection
