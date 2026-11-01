@extends('admin.layouts.app')

@section('title', 'مدیریت کاربران')
@section('page-title', 'مدیریت کاربران')

@section('content')
    <div class="max-w-7xl">

        {{-- Page header with search --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">مدیریت کاربران</h2>
                <p class="mt-1 text-sm text-slate-500">لیست کاربران ثبت‌شده در سیستم را اینجا مدیریت کنید.</p>
            </div>

            <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="جستجو بر اساس نام، کد ملی یا موبایل..."
                       class="px-4 py-2.5 text-sm rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none w-full md:w-80">
                <button type="submit"
                        class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    جستجو
                </button>
            </form>
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

        {{-- Users table --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">نام و نام خانوادگی</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">کد ملی</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">موبایل</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">وضعیت</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">نقش‌ها</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">فناور؟</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-slate-700 w-20">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-800">{{ $user->name }} {{ $user->family }}</td>
                            <td class="px-6 py-4 text-slate-800" dir="ltr">{{ $user->national_code }}</td>
                            <td class="px-6 py-4 text-slate-800" dir="ltr">{{ $user->mobile }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium
                                           @if ($user->status === 'active') bg-emerald-100 text-emerald-700 @else bg-red-100 text-red-700 @endif">
                                    @if ($user->status === 'active')
                                        فعال
                                    @else
                                        مسدود
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-800">
                                {{ $user->getRoleNames()->isNotEmpty() ? $user->getRoleNames()->implode(', ') : 'کاربر عادی' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium
                                           {{ $user->is_innovator ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $user->is_innovator ? 'بله' : 'خیر' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="text-indigo-600 hover:text-indigo-800 hover:underline text-sm font-medium">ویرایش</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">کاربری یافت نشد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $users->links() }}
        </div>

    </div>
@endsection
