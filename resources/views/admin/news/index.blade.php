@extends('admin.layouts.app')

@section('title', 'مدیریت اخبار')
@section('page-title', 'مدیریت اخبار')

@section('content')
<div class="max-w-7xl">

    {{-- Page header with search --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">مدیریت اخبار</h2>
            <p class="mt-1 text-sm text-slate-500">لیست اخبار منتشر شده و پیش‌نویس‌ها را اینجا مدیریت کنید.</p>
        </div>

        <a href="{{ route('admin.news.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg
                 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M12 5v14m-7-7h14"/>
            </svg>
            خبر جدید
        </a>

        <form method="GET" action="{{ route('admin.news.index') }}" class="flex items-center gap-2">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="جستجو بر اساس عنوان..."
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

    {{-- News table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">تصویر کاور</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">عنوان</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">دسته‌بندی‌ها</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">نویسنده</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">وضعیت</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700 hidden lg:table-cell">نشر شده در</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-slate-700 w-24">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($news as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            @if($item->cover_image_path)
                                <img src="{{ route('file.news-cover', basename($item->cover_image_path)) }}"
                                     alt="{{ $item->title }}"
                                     class="w-16 h-10 rounded-lg object-cover border border-slate-200">
                            @else
                                <div class="w-16 h-10 rounded-lg bg-slate-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM3 16l5-5 4 4 3-3 6 6M8.5 8.5h.01"/>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-800 truncate max-w-xs">{{ $item->title }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $item->slug }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->categories->isNotEmpty())
                                @foreach($item->categories as $cat)
                                    <span class="inline-block mr-1.5 mb-1 px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 text-indigo-700">
                                        {{ $cat->name }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-800">
                            {{ $item->author->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'draft'      => ['label' => 'پیش‌نویس',      'class' => 'bg-amber-100 text-amber-800'],
                                    'published'  => ['label' => 'منتشر شده',    'class' => 'bg-emerald-100 text-emerald-800'],
                                    'archived'   => ['label' => 'بایگانی شده',  'class' => 'bg-slate-100 text-slate-700'],
                                ];
                                $sc = $statusColors[$item->status->value ?? $item->status] ?? ['label' => $item->status ?? 'نامشخص', 'class' => 'bg-slate-100 text-slate-700'];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $sc['class'] }} border-opacity-30">
                                {{ $sc['label'] }}
                            </span>
                            @if($item->is_featured)
                                <span class="ml-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    ویژه
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 hidden lg:table-cell text-slate-500">
                            @if($item->published_at)
                                {{ \Morilog\Jalali\Jalalian::fromCarbon($item->published_at)->format('Y/m/d H:i') }}
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('admin.news.edit', $item->id) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    ویرایش
                                </a>
                                <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST"
                                      onsubmit="return confirm('آیا از حذف این خبر مطمئن هستید؟')"
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
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">خبری یافت نشد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $news->links() }}
    </div>

</div>
@endsection