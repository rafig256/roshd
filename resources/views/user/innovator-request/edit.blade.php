@extends('layouts.public')

@section('title', 'ویرایش درخواست فناوری')

@section('content')
<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white min-h-[calc(100vh-5rem)]">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Breadcrumb / back --}}
        <a href="{{ route('user.dashboard') }}"
           class="inline-flex items-center gap-1.5 mb-6 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M9 5l7 7-7 7"/>
            </svg>
            بازگشت به پنل کاربری
        </a>

        {{-- Page header --}}
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">ویرایش درخواست فناوری</h1>
                <p class="mt-2 text-sm sm:text-base leading-8 text-slate-600">
                    درخواست شما در وضعیت <strong>{{ $innovatorRequest->status === 'approved' ? 'تأیید شده' : 'رد شده' }}</strong> است.
                    با ویرایش درخواست، وضعیت آن به <strong>«در انتظار بررسی»</strong> تغییر می‌کند تا کارشناسان مجدداً آن را بررسی نمایند.
                </p>
            </div>
        </div>

        {{-- General error summary --}}
        @if ($errors->any())
            <div class="mt-8 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl">
                لطفاً خطاهای مشخص‌شده در فرم را برطرف کنید و دوباره تلاش کنید.
            </div>
        @endif

        {{-- Form card --}}
        <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5">
            <form method="POST" action="{{ route('user.innovator-request.update', $innovatorRequest->id) }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6" novalidate>
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- Activity category --}}
                    <div>
                        <label for="activity_category_id" class="block mb-2 text-sm font-medium text-slate-700">
                            دسته فعالیت <span class="text-red-500">*</span>
                        </label>
                        <select id="activity_category_id" name="activity_category_id"
                                class="w-full px-4 py-3 text-sm rounded-xl border bg-slate-50
                                       focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                       @error('activity_category_id') border-red-400 @else border-slate-300 @enderror">
                            <option value="">یک دسته را انتخاب کنید</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $innovatorRequest->activity_category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name ?? $category->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('activity_category_id')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Entry date (Persian datepicker) --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-slate-700">
                            تاریخ ورود به پارک
                        </label>

                        {{-- مقدار واقعی که همراه فرم ارسال می‌شود (YYYY/MM/DD با ارقام لاتین) --}}
                        <input type="hidden" id="entry_date" name="entry_date" value="{{ old('entry_date', $innovatorRequest->entry_date ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($innovatorRequest->entry_date))->format('Y/m/d') : '') }}">

                        <div class="datepicker-wrap rounded-xl border bg-slate-50 transition
                                    focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500
                                    @error('entry_date') border-red-400 @else border-slate-300 @enderror">
                                <persian-datepicker-element
                                    id="entry_date_picker"
                                    placeholder="1402/01/01"
                                    format="YYYY/MM/DD"
                                    rtl
                                    class="block w-full"></persian-datepicker-element>
                        </div>

                        <p class="mt-1.5 text-xs text-slate-400">تاریخ را از تقویم انتخاب کنید.</p>
                        @error('entry_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Bio --}}
                <div>
                    <label for="bio" class="block mb-2 text-sm font-medium text-slate-700">
                        معرفی خود <span class="text-red-500">*</span>
                    </label>
                    <textarea id="bio" name="bio" rows="5"
                              placeholder="درباره‌ی تحصیلات، سوابق کاری و تخصص‌های خود بنویسید..."
                              class="w-full px-4 py-3 text-sm leading-7 rounded-xl border bg-slate-50 placeholder:text-slate-400 resize-y
                                     focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                     @error('bio') border-red-400 @else border-slate-300 @enderror">{{ old('bio', $innovatorRequest->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Motivation --}}
                <div>
                    <label for="motivation" class="block mb-2 text-sm font-medium text-slate-700">
                        انگیزه از فناور شدن <span class="text-red-500">*</span>
                    </label>
                    <textarea id="motivation" name="motivation" rows="5"
                              placeholder="چرا می‌خواهید در مرکز نوآوری شهری فعالیت کنید و انتظار شما از این همکاری چیست؟"
                              class="w-full px-4 py-3 text-sm leading-7 rounded-xl border bg-slate-50 placeholder:text-slate-400 resize-y
                                     focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                     @error('motivation') border-red-400 @else border-slate-300 @enderror">{{ old('motivation', $innovatorRequest->motivation) }}</textarea>
                    @error('motivation')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Resume file --}}
                <div>
                    <label for="resume_file_path" class="block mb-2 text-sm font-medium text-slate-700">فایل رزومه</label>
                    @if($innovatorRequest->resume_file_path)
                        <p class="text-sm text-slate-600">
                            فایل فعلی: 
                            <a href="{{ route('user.download.resume', basename($innovatorRequest->resume_file_path)) }}" target="_blank" class="text-indigo-600 hover:underline font-bold">
                                {{ basename($innovatorRequest->resume_file_path) }}
                            </a>
                        </p>
                    @endif
                    <label for="resume_file_path"
                           class="flex flex-col items-center justify-center gap-2 px-4 py-8 text-center rounded-xl border-2 border-dashed cursor-pointer
                                  bg-slate-50 hover:bg-indigo-50/50 hover:border-indigo-300 transition-colors
                                  @error('resume_file_path') border-red-400 @else border-slate-300 @enderror">
                        <svg class="w-9 h-9 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.6"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M12 16V4M7 9l5-5 5 5M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3"/>
                        </svg>
                        <span id="resume-file-name" class="text-sm font-medium text-slate-600">برای انتخاب فایل جدید کلیک کنید</span>
                        <span class="text-xs text-slate-400">فرمت‌های مجاز: PDF، DOC، DOCX</span>
                    </label>
                    <input type="file" id="resume_file_path" name="resume_file_path" accept=".pdf,.doc,.docx" class="sr-only">
                    @error('resume_file_path')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                    <a href="{{ route('user.dashboard') }}"
                       class="px-6 py-3 text-center text-sm font-semibold text-slate-600 rounded-xl border border-slate-300 hover:bg-slate-50 transition-colors">
                        انصراف
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3 text-sm font-bold text-white rounded-xl
                                   bg-gradient-to-l from-indigo-600 to-fuchsia-500 shadow-lg shadow-indigo-500/30
                                   hover:shadow-xl hover:-translate-y-0.5
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        ذخیره تغییرات
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M19 12H5M11 6l-6 6 6 6"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script type="module" src="https://cdn.jsdelivr.net/npm/persian-datepicker-element@1.1.17/dist/persian-datepicker-element.min.js"></script>

<style>
    /* کامپوننت تمام عرض ستون را بگیرد */
    persian-datepicker-element { display: block; width: 100%; font-family: 'Vazirmatn', Tahoma, sans-serif; }
    /* هماهنگی با ظاهر بقیه فیلدها (فقط روی المان بیرونی اثر دارد، نه داخل Shadow DOM) */
    .datepicker-wrap { padding: 0.25rem 0.5rem; }
</style>

<script>
    (function () {
        const picker = document.getElementById('entry_date_picker');
        const hidden = document.getElementById('entry_date');
        if (!picker || !hidden) return;

        const pad = (n) => String(n).padStart(2, '0');

        // تبدیل ارقام فارسی/عربی به لاتین
        const toLatin = (s) => String(s)
            .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
            .replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));

        // هر بار کاربر تاریخی انتخاب کرد، فیلد مخفی به‌روز شود
        picker.addEventListener('change', (e) => {
            const j = e.detail && e.detail.jalali;
            hidden.value = Array.isArray(j) && j.length === 3
                ? `${j[0]}/${pad(j[1])}/${pad(j[2])}`
                : '';
        });

        // بازگرداندن old('entry_date') بعد از خطای اعتبارسنجی
        const initial = toLatin(hidden.value || '').trim();
        const m = initial.match(/^(\d{4})\/(\d{1,2})\/(\d{1,2})$/);
        if (m) {
            customElements.whenDefined('persian-datepicker-element').then(() => {
                picker.setValue(+m[1], +m[2], +m[3]);
            });
        }
    })();
</script>
<script>
    // Show selected file name in the upload box
    document.getElementById('resume_file_path')?.addEventListener('change', function () {
        const label = document.getElementById('resume-file-name');
        label.textContent = this.files.length ? this.files[0].name : 'برای انتخاب فایل جدید کلیک کنید';
    });
</script>
@endpush