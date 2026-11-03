@extends('admin.layouts.app')

@section('title', 'مدیریت دسته‌بندی اخبار')
@section('page-title', 'مدیریت دسته‌بندی اخبار')

@section('content')
<div class="max-w-7xl">

    {{-- Page header with search --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">مدیریت دسته‌بندی اخبار</h2>
            <p class="mt-1 text-sm text-slate-500">لیست دسته‌بندی‌های اخبار را اینجا مدیریت کنید.</p>
        </div>

        <a href="{{ route('admin.news-categories.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg
                 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M12 5v14m-7-7h14"/>
            </svg>
            دسته‌بندی جدید
        </a>

        <form method="GET" action="{{ route('admin.news-categories.index') }}" class="flex items-center gap-2">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="جستجو بر اساس نام یا اسلاگ..."
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

    {{-- Error message --}}
    @if (session('error'))
        <div class="mb-6 flex items-center gap-2 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Categories table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">نام</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">اسلاگ</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">دسته والد</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">ترتیب</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">وضعیت</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-slate-700 w-24">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($categories as $category)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-800">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-slate-800" dir="ltr">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-slate-800">
                            @if($category->parent)
                                {{ $category->parent->name }}
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-800" dir="ltr">{{ $category->order ?? 0 }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium
                               {{ $category->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $category->is_active ? 'فعال' : 'غیرفعال' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('admin.news-categories.edit', $category->id) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    ویرایش
                                </a>
                                <form action="{{ route('admin.news-categories.destroy', $category->id) }}" method="POST"
                                      onsubmit="return confirm('آیا از حذف این دسته‌بندی مطمئن هستید؟')"
                                      class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M3 6h18"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">دسته‌بندی‌ای یافت نشد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $categories->links() }}
    </div>

</div>
@endsection