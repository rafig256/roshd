@extends('admin.layouts.app')

@section('title', 'ویرایش دسته‌بندی اخبار')
@section('page-title', 'ویرایش دسته‌بندی اخبار')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">فرم ویرایش دسته‌بندی اخبار</h2>
        <p class="mt-1 text-sm text-slate-500">اطلاعات دسته‌بندی را در این صفحه ویرایش کنید.</p>
    </div>

    <form method="POST" action="{{ route('admin.news-categories.update', $newsCategory->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-slate-700">نام دسته‌بندی <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $newsCategory->name) }}"
                   class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('name') border-red-400 @else border-slate-300 @enderror">
            @error('name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Parent Category --}}
        <div>
            <label for="parent_id" class="block mb-2 text-sm font-medium text-slate-700">دسته والد</label>
            <select id="parent_id" name="parent_id"
                    class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('parent_id') border-red-400 @else border-slate-300 @enderror">
                <option value="">بدون والد (دسته اصلی)</option>
                @foreach ($parentCategories as $cat)
                    <option value="{{ $cat->id }}" {{ old('parent_id', $newsCategory->parent_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1.5 text-xs text-slate-400">در صورت انتخاب، این دسته به عنوان زیرمجموعه دسته والد در نظر گرفته می‌شود.</p>
            @error('parent_id') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block mb-2 text-sm font-medium text-slate-700">توضیحات</label>
            <textarea id="description" name="description" rows="4"
                      class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('description') border-red-400 @else border-slate-300 @enderror">{{ old('description', $newsCategory->description) }}</textarea>
            @error('description') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Order --}}
            <div>
                <label for="order" class="block mb-2 text-sm font-medium text-slate-700">ترتیب نمایش</label>
                <input type="number" id="order" name="order" value="{{ old('order', $newsCategory->order) }}"
                       min="0" class="w-full px-4 py-2.5 text-sm text-left rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('order') border-red-400 @else border-slate-300 @enderror">
                @error('order') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Is Active --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-slate-700">وضعیت</label>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', $newsCategory->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-2 focus:ring-indigo-500">
                    <label for="is_active" class="text-sm font-medium text-slate-700 cursor-pointer">فعال</label>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end pt-4">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
                ذخیره تغییرات
            </button>
        </div>
    </form>
</div>
@endsection