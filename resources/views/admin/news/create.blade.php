@extends('admin.layouts.app')

@section('title', 'ایجاد خبر')
@section('page-title', 'ایجاد خبر')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
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
        .ts-wrapper.multi .ts-control {
            background-position: left 0.75rem center !important;
            padding-left: 2rem !important;
        }
    </style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">فرم ایجاد خبر</h2>
        <p class="mt-1 text-sm text-slate-500">اطلاعات خبر جدید را در این صفحه ثبت کنید.</p>
    </div>

    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Title --}}
        <div>
            <label for="title" class="block mb-2 text-sm font-medium text-slate-700">عنوان خبر <span class="text-red-500">*</span></label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('title') border-red-400 @else border-slate-300 @enderror">
            @error('title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Categories --}}
        <div>
            <label for="categories" class="block mb-2 text-sm font-medium text-slate-700">دسته‌بندی‌ها <span class="text-red-500">*</span></label>
            <select id="categories" name="categories[]" multiple placeholder="جستجوی دسته‌بندی‌ها..."
                    class="w-full @error('categories') border-red-400 @else border-slate-300 @enderror">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <p class="mt-1.5 text-xs text-slate-400">حداقل یک دسته‌بندی انتخاب کنید.</p>
            @error('categories') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Excerpt --}}
        <div>
            <label for="excerpt" class="block mb-2 text-sm font-medium text-slate-700">خلاصه (Excerpt)</label>
            <textarea id="excerpt" name="excerpt" rows="3"
                      class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('excerpt') border-red-400 @else border-slate-300 @enderror">{{ old('excerpt') }}</textarea>
            <p class="mt-1.5 text-xs text-slate-400">خلاصه کوتاه خبر برای نمایش در لیست‌ها و کارت‌ها.</p>
            @error('excerpt') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Body --}}
        <div>
            <label for="body" class="block mb-2 text-sm font-medium text-slate-700">متن کامل خبر <span class="text-red-500">*</span></label>
            <textarea id="body" name="body" rows="15"
                      class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('body') border-red-400 @else border-slate-300 @enderror">{{ old('body') }}</textarea>
            <p class="mt-1.5 text-xs text-slate-400">متن کامل خبر. ویرایشگر متن غنی در آینده اضافه خواهد شد.</p>
            @error('body') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Cover Image --}}
        <div>
            <label for="cover_image" class="block mb-2 text-sm font-medium text-slate-700">تصویر کاور</label>
            <label for="cover_image"
                   class="flex flex-col items-center justify-center gap-2 px-4 py-8 text-center rounded-xl border-2 border-dashed cursor-pointer
                          bg-slate-50 hover:bg-indigo-50/50 hover:border-indigo-300 transition-colors
                          @error('cover_image') border-red-400 @else border-slate-300 @enderror">
                <img id="cover-preview" alt="پیش‌نمایش کاور" class="hidden max-w-xs rounded-xl object-cover border border-slate-200">
                <svg id="cover-icon" class="w-9 h-9 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.6"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM3 16l5-5 4 4 3-3 6 6M8.5 8.5h.01"/>
                </svg>
                <span id="cover-file-name" class="text-sm font-medium text-slate-600">برای انتخاب تصویر کلیک کنید</span>
                <span class="text-xs text-slate-400">فرمت‌های مجاز: PNG، JPG، WEBP (حداکثر ۲ مگابایت)</span>
            </label>
            <input type="file" id="cover_image" name="cover_image" accept=".png,.jpg,.jpeg,.webp" class="sr-only">
            @error('cover_image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Status --}}
            <div>
                <label for="status" class="block mb-2 text-sm font-medium text-slate-700">وضعیت <span class="text-red-500">*</span></label>
                <select id="status" name="status"
                        class="w-full px-4 py-2.5 text-sm rounded-lg border bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none @error('status') border-red-400 @else border-slate-300 @enderror">
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>منتشر شده</option>
                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>بایگانی شده</option>
                </select>
                @error('status') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Is Featured --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-slate-700">تنظیمات</label>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1"
                           {{ old('is_featured') ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-2 focus:ring-indigo-500">
                    <label for="is_featured" class="text-sm font-medium text-slate-700 cursor-pointer">ویژه</label>
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
                ذخیره خبر
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // TomSelect for categories
            new TomSelect('#categories', {
                plugins: ['remove_button'],
                maxItems: null,
                create: false,
                placeholder: 'جستجوی دسته‌بندی‌ها...',
                dir: 'rtl',
            });

            // Cover image preview
            document.getElementById('cover_image')?.addEventListener('change', function () {
                const name    = document.getElementById('cover-file-name');
                const preview = document.getElementById('cover-preview');
                const icon    = document.getElementById('cover-icon');
                const file    = this.files[0];

                if (!file) {
                    name.textContent = 'برای انتخاب تصویر کلیک کنید';
                    preview.classList.add('hidden');
                    icon.classList.remove('hidden');
                    return;
                }

                name.textContent = file.name;
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            });
        });
    </script>
@endpush