{{--
    Shared form for creating / editing events.

    Variables:
      $action  (string)  آدرس ارسال فرم
      $method  (string)  POST یا PUT
      $event   (Event|null)  رویداد در حالت ویرایش، null در حالت ایجاد
      $categories, $users  از کنترلر
--}}
@php

    $event  = $event ?? null;
    $method = strtoupper($method ?? 'POST');
    $isEdit = $event && $event->exists;

    $input = 'w-full px-4 py-2.5 text-sm rounded-lg border bg-slate-50 placeholder:text-slate-400
              focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition';
    $label = 'block mb-2 text-sm font-medium text-slate-700';

    // مقدار فیلد: old() اولویت دارد، بعد مقدار رویداد، بعد مقدار پیش‌فرض
    $v  = fn (string $field, $default = null) => old($field, $event ? $event->{$field} : $default);
    // فرمت مقدار datetime-local
    // ارقام فارسی/عربی -> لاتین
    $toLatin = fn ($s) => strtr((string) $s, [
        '۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9',
        '٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4','٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9',
    ]);

    // هر مقداری (Carbon میلادی، رشته‌ی میلادی، یا رشته‌ی شمسی) را به «1403/08/15 14:30» تبدیل می‌کند
    $jdt = function ($v) use ($toLatin) {
        if (!$v) return null;
        $raw = $toLatin(trim((string) $v));

        if (preg_match('/^(\d{4})[\/-](\d{1,2})[\/-](\d{1,2})(?:[ T]+(\d{1,2}):(\d{2}))?/', $raw, $m) && (int) $m[1] < 1700) {
            return sprintf('%04d/%02d/%02d', $m[1], $m[2], $m[3])
                . (isset($m[4]) ? sprintf(' %02d:%02d', $m[4], $m[5]) : '');
        }

        try {
            return $toLatin(\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($v))->format('Y/m/d H:i'));
        } catch (\Throwable $e) {
            return null;
        }
    };

    // فیلدهای تاریخ‌ـ‌ساعت‌دار
    $dateTimeFields = [
        ['name' => 'starts_at',             'label' => 'شروع',        'required' => true],
        ['name' => 'ends_at',               'label' => 'پایان',       'required' => true],
        ['name' => 'registration_deadline', 'label' => 'مهلت ثبت‌نام', 'required' => false],
    ];

    // old() برای چک‌باکس‌های تیک‌نخورده کلیدی ندارد؛ پس اگر فرم قبلاً ارسال شده، فقط به old() تکیه می‌کنیم
    $hasOld = count(old()) > 0;

    $locationTypeRaw = $event ? ($event->location_type instanceof \BackedEnum ? $event->location_type->value : $event->location_type) : 'physical';
    $locationType    = old('location_type', $locationTypeRaw);

    $registrationRequired = $hasOld ? (bool) old('registration_required') : ($event ? (bool) $event->registration_required : true);
    $requiresPayment      = $hasOld ? (bool) old('requires_payment')      : ($event ? (bool) $event->requires_payment : false);

    $selectedCategories = array_map('intval', (array) old('categories', $event ? $event->categories->pluck('id')->all() : []));
    $catError           = $errors->first('categories') ?: $errors->first('categories.*');

    $speakerRows = (array) old('speakers', $event ? $event->speakers->map(fn ($s) => [
        'user_id' => $s->user_id,
        'title'   => $s->title,
        'bio'     => $s->bio,
    ])->values()->all() : []);

    $sessionRows = (array) old('sessions', $event ? $event->sessions->map(fn ($s) => [
        'title'       => $s->title,
        'time_string' => $s->time_string,
        'location'    => $s->location,
        'duration'    => $s->duration,
        'description' => $s->description,
    ])->values()->all() : []);

    $nextSpeakerIndex = count($speakerRows) ? ((int) max(array_keys($speakerRows)) + 1) : 0;
    $nextSessionIndex = count($sessionRows) ? ((int) max(array_keys($sessionRows)) + 1) : 0;

    $userOptions = collect($users)->map(fn ($u) => [
        'id'    => $u->id,
        'label' => trim(($u->name ?? '') . ' ' . ($u->family ?? '')) ?: ('کاربر #' . $u->id),
    ]);

    $hasCover = $event && !empty($event->cover_image_path);

    $statusRaw   = $event ? ($event->status instanceof \BackedEnum ? $event->status->value : $event->status) : 'draft';
    $statusValue = old('status', $statusRaw);
@endphp

@if ($errors->any())
    <div class="mb-6 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg">
        <p class="font-semibold">لطفاً خطاهای زیر را برطرف کنید:</p>
        <ul class="mt-2 list-disc pr-5 space-y-1">
            @foreach ($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- ============ Section 1: Basic info ============ --}}
    <section class="rounded-xl border border-slate-200 bg-white">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
            <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 21a9 9 0 100-18 9 9 0 000 18zM12 8h.01M11 12h1v4h1"/></svg>
            </span>
            <h3 class="text-base font-semibold text-slate-800">اطلاعات اصلی</h3>
        </div>
        <div class="p-6 space-y-5">
            <div>
                <label for="title" class="{{ $label }}">عنوان رویداد <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ $v('title') }}" maxlength="255"
                       class="{{ $input }} @error('title') border-red-400 @else border-slate-300 @enderror">
                @error('title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="{{ $label }}">توضیحات <span class="text-red-500">*</span></label>
                <textarea id="description" name="description" rows="6"
                          class="{{ $input }} leading-7 resize-y @error('description') border-red-400 @else border-slate-300 @enderror">{{ $v('description') }} required<form</textarea>
                @error('description') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="{{ $label }}">وضعیت <span class="text-red-500">*</span></label>
                <select id="status" name="status"
                        class="{{ $input }} md:w-1/3 @error('status') border-red-400 @else border-slate-300 @enderror">
                    @foreach (\App\Enums\EventStatus::cases() as $case)
                        <option value="{{ $case->value }}" @selected($statusValue === $case->value)>{{ $case->label() }}</option>
                    @endforeach
                </select>
                @error('status') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="cover_image_path" class="{{ $label }}">تصویر بنر</label>

                @if ($hasCover && \Illuminate\Support\Facades\Route::has('file.event-cover'))
                    <img src="{{ route('file.event-cover', basename($event->cover_image_path)) }}" alt="بنر فعلی رویداد"
                         class="mb-3 w-full max-w-xs h-32 object-cover rounded-lg border border-slate-200 bg-white">
                @endif

                <input type="file" id="cover_image_path" name="cover_image_path" accept=".png,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp"
                       class="block w-full text-sm text-slate-600 border rounded-lg bg-slate-50 cursor-pointer
                              file:ml-4 file:px-4 file:py-2.5 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-semibold hover:file:bg-indigo-100
                              @error('cover_image_path') border-red-400 @else border-slate-300 @enderror">
                <p class="mt-1.5 text-xs text-slate-400">
                    @if ($hasCover)
                        برای تغییر تصویر، فایل جدید انتخاب کنید. در غیر این صورت تصویر قبلی حفظ می‌شود.
                    @else
                        فرمت‌های مجاز: PNG، JPG، WEBP
                    @endif
                </p>
                @error('cover_image_path') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    {{-- ============ Section 2: Timing & location ============ --}}
    <section class="rounded-xl border border-slate-200 bg-white">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
            <span class="w-9 h-9 rounded-lg bg-fuchsia-50 text-fuchsia-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
            </span>
            <h3 class="text-base font-semibold text-slate-800">زمان و مکان</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach ($dateTimeFields as $f)
                @php
                    $name    = $f['name'];
                    $value   = $jdt(old($name, $event ? $event->{$name} : null));
                    $timeVal = preg_match('/(\d{2}:\d{2})$/', (string) $value, $tm) ? $tm[1] : '12:00';
                    $hasErr  = $errors->has($name);
                @endphp

                <div class="md:col-span-3">
                    <label for="{{ $name }}_time" class="{{ $label }}">
                        {{ $f['label'] }} @if ($f['required']) <span class="text-red-500">*</span> @endif
                    </label>

                    {{-- مقدار نهایی که به کنترلر می‌رود: 1403/08/15 14:30 --}}
                    <input type="hidden" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}">

                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="datepicker-wrap flex-1 rounded-lg border bg-slate-50 transition
                                    focus-within:bg-white focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500
                                    {{ $hasErr ? 'border-red-400' : 'border-slate-300' }}">
                            <persian-datepicker-element id="{{ $name }}_picker"
                                                        placeholder="1403/01/01" format="YYYY/MM/DD" rtl
                                                        class="block w-full"></persian-datepicker-element>
                        </div>

                        <input type="time" id="{{ $name }}_time" name="{{ $name }}_time" value="{{ $timeVal }}" dir="ltr"
                            aria-label="ساعت {{ $f['label'] }}"
                            class="{{ $input }} sm:w-44 text-left {{ $hasErr ? 'border-red-400' : 'border-slate-300' }}">
                    </div>

                    @error($name) <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            @endforeach
            <div class="md:col-span-3">
                <label for="location_type" class="{{ $label }}">نوع برگزاری <span class="text-red-500">*</span></label>
                <select id="location_type" name="location_type"
                        class="{{ $input }} md:w-1/3 @error('location_type') border-red-400 @else border-slate-300 @enderror">
                    <option value="physical" @selected($locationType === 'physical')>حضوری</option>
                    <option value="online"   @selected($locationType === 'online')>آنلاین</option>
                    <option value="hybrid"   @selected($locationType === 'hybrid')>ترکیبی</option>
                </select>
                @error('location_type') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div id="address-wrap" class="md:col-span-3">
                <label for="location_address" class="{{ $label }}">آدرس برگزاری</label>
                <input type="text" id="location_address" name="location_address" value="{{ $v('location_address') }}" maxlength="500"
                       class="{{ $input }} @error('location_address') border-red-400 @else border-slate-300 @enderror">
                @error('location_address') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div id="link-wrap" class="md:col-span-2">
                <label for="location_link" class="{{ $label }}">لینک برگزاری آنلاین</label>
                <input type="text" id="location_link" name="location_link" value="{{ $v('location_link') }}" dir="ltr" maxlength="500" placeholder="https://"
                       class="{{ $input }} text-left placeholder:text-right @error('location_link') border-red-400 @else border-slate-300 @enderror">
                @error('location_link') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div id="platform-wrap">
                <label for="platform" class="{{ $label }}">پلتفرم</label>
                <input type="text" id="platform" name="platform" value="{{ $v('platform') }}" maxlength="100" placeholder="Skyroom, Zoom, ..."
                       class="{{ $input }} @error('platform') border-red-400 @else border-slate-300 @enderror">
                @error('platform') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    {{-- ============ Section 3: Registration & capacity ============ --}}
    <section class="rounded-xl border border-slate-200 bg-white">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
            <span class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9a2 2 0 002-2h14a2 2 0 002 2v0a2 2 0 000 6v0a2 2 0 00-2 2H5a2 2 0 00-2-2v0a2 2 0 000-6zM13 7v10"/></svg>
            </span>
            <h3 class="text-base font-semibold text-slate-800">ثبت‌نام و ظرفیت</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="capacity" class="{{ $label }}">ظرفیت</label>
                <input type="number" id="capacity" name="capacity" value="{{ $v('capacity') }}" min="1" step="1" dir="ltr"
                       placeholder="خالی بگذارید یعنی نامحدود"
                       class="{{ $input }} text-left placeholder:text-right @error('capacity') border-red-400 @else border-slate-300 @enderror">
                @error('capacity') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col justify-end gap-3">
                <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                    <input type="checkbox" id="registration_required" name="registration_required" value="1" @checked($registrationRequired)
                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    ثبت‌نام برای شرکت الزامی است
                </label>
                <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                    <input type="checkbox" id="requires_payment" name="requires_payment" value="1" @checked($requiresPayment)
                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    شرکت در رویداد هزینه دارد
                </label>
            </div>

            <div id="price-wrap" class="md:col-span-2 md:w-1/2 transition-opacity">
                <label for="price_amount" class="{{ $label }}">مبلغ شرکت (ریال)</label>
                <input type="number" id="price_amount" name="price_amount" value="{{ $v('price_amount') }}" min="0" step="1" dir="ltr"
                       @disabled(!$requiresPayment)
                       class="{{ $input }} text-left disabled:bg-slate-100 disabled:cursor-not-allowed @error('price_amount') border-red-400 @else border-slate-300 @enderror">
                @error('price_amount') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    {{-- ============ Section 4: Categories ============ --}}
    <section class="rounded-xl border border-slate-200 bg-white">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
            <span class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 7l9-4 9 4-9 4-9-4zM3 12l9 4 9-4M3 17l9 4 9-4"/></svg>
            </span>
            <h3 class="text-base font-semibold text-slate-800">دسته‌بندی</h3>
        </div>
        <div class="p-6">
            <label for="categories" class="{{ $label }}">دسته‌های رویداد</label>
            <select id="categories" name="categories[]" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(in_array($category->id, $selectedCategories))>
                        {{ $category->name ?? $category->title }}
                    </option>
                @endforeach
            </select>
            @if ($catError) <p class="mt-1.5 text-xs text-red-600">{{ $catError }}</p> @endif
        </div>
    </section>

    {{-- ============ Section 5: Speakers (dynamic) ============ --}}
    <section class="rounded-xl border border-slate-200 bg-white">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 14a3 3 0 003-3V6a3 3 0 10-6 0v5a3 3 0 003 3zM19 11a7 7 0 01-14 0M12 18v3"/></svg>
                </span>
                <h3 class="text-base font-semibold text-slate-800">سخنرانان</h3>
            </div>
            <button type="button" id="add-speaker"
                    class="px-3.5 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                + افزودن سخنران
            </button>
        </div>
        <div class="p-6">
            <div id="speakers-container" class="space-y-4"></div>
            <p id="speakers-empty" class="text-sm text-slate-400 text-center py-6">هنوز سخنرانی اضافه نشده است.</p>
        </div>
    </section>

    {{-- ============ Section 6: Sessions (dynamic) ============ --}}
    <section class="rounded-xl border border-slate-200 bg-white">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 6v6l4 2M12 21a9 9 0 100-18 9 9 0 000 18z"/></svg>
                </span>
                <h3 class="text-base font-semibold text-slate-800">برنامه و جلسات</h3>
            </div>
            <button type="button" id="add-session"
                    class="px-3.5 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                + افزودن برنامه
            </button>
        </div>
        <div class="p-6">
            <div id="sessions-container" class="space-y-4"></div>
            <p id="sessions-empty" class="text-sm text-slate-400 text-center py-6">هنوز برنامه‌ای اضافه نشده است.</p>
        </div>
    </section>

    {{-- ============ Actions ============ --}}
    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="{{ route('admin.events.index') }}"
           class="px-6 py-2.5 text-center text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
            انصراف
        </a>
        <button type="submit"
                class="inline-flex items-center justify-center gap-2 px-8 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg
                       hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
            {{ $isEdit ? 'ذخیره تغییرات' : 'ثبت رویداد' }}
        </button>
    </div>
</form>

{{-- ============ Row templates (inert until cloned by JS) ============ --}}
<template id="speaker-template">
    <div class="rounded-lg border border-slate-200 bg-slate-50/60 p-4" data-row>
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-bold text-slate-700">سخنران <span data-row-number></span></span>
            <button type="button" data-remove class="px-3 py-1.5 text-xs font-bold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">حذف</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div data-field-wrap>
                <label for="spk-__INDEX__-user" class="{{ $label }}">سخنران</label>
                <select id="spk-__INDEX__-user" name="speakers[__INDEX__][user_id]" data-field="user_id" data-tom>
                    <option value="">انتخاب سخنران</option>
                    @foreach ($userOptions as $opt)
                        <option value="{{ $opt['id'] }}">{{ $opt['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div data-field-wrap>
                <label for="spk-__INDEX__-title" class="{{ $label }}">عنوان سخنرانی</label>
                <input type="text" id="spk-__INDEX__-title" name="speakers[__INDEX__][title]" data-field="title" maxlength="255"
                       class="{{ $input }} border-slate-300">
            </div>
            <div data-field-wrap class="md:col-span-2">
                <label for="spk-__INDEX__-bio" class="{{ $label }}">معرفی</label>
                <textarea id="spk-__INDEX__-bio" name="speakers[__INDEX__][bio]" data-field="bio" rows="3"
                          class="{{ $input }} leading-7 resize-y border-slate-300"></textarea>
            </div>
        </div>
    </div>
</template>

<template id="session-template">
    <div class="rounded-lg border border-slate-200 bg-slate-50/60 p-4" data-row>
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-bold text-slate-700">برنامه <span data-row-number></span></span>
            <button type="button" data-remove class="px-3 py-1.5 text-xs font-bold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">حذف</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div data-field-wrap class="md:col-span-2">
                <label for="ses-__INDEX__-title" class="{{ $label }}">عنوان</label>
                <input type="text" id="ses-__INDEX__-title" name="sessions[__INDEX__][title]" data-field="title" maxlength="255"
                       class="{{ $input }} border-slate-300">
            </div>
            <div data-field-wrap>
                <label for="ses-__INDEX__-time" class="{{ $label }}">زمان</label>
                <input type="text" id="ses-__INDEX__-time" name="sessions[__INDEX__][time_string]" data-field="time_string" maxlength="255"
                       placeholder="روز اول، ساعت ۱۱" class="{{ $input }} border-slate-300">
            </div>
            <div data-field-wrap>
                <label for="ses-__INDEX__-location" class="{{ $label }}">مکان</label>
                <input type="text" id="ses-__INDEX__-location" name="sessions[__INDEX__][location]" data-field="location" maxlength="255"
                       class="{{ $input }} border-slate-300">
            </div>
            <div data-field-wrap>
                <label for="ses-__INDEX__-duration" class="{{ $label }}">مدت</label>
                <input type="text" id="ses-__INDEX__-duration" name="sessions[__INDEX__][duration]" data-field="duration" maxlength="100"
                       placeholder="مثلاً ۹۰ دقیقه" class="{{ $input }} border-slate-300">
            </div>
            <div data-field-wrap class="md:col-span-2">
                <label for="ses-__INDEX__-desc" class="{{ $label }}">توضیحات</label>
                <textarea id="ses-__INDEX__-desc" name="sessions[__INDEX__][description]" data-field="description" rows="3"
                          class="{{ $input }} leading-7 resize-y border-slate-300"></textarea>
            </div>
        </div>
    </div>
</template>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css">
<style>
    .ts-wrapper { direction: rtl; }
    .ts-control { border-radius: .5rem; border-color: #cbd5e1; background: #f8fafc; padding: .55rem .75rem; font-size: .875rem; font-family: inherit; }
    .ts-wrapper.focus .ts-control { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 2px rgba(99,102,241,.3); }
    .ts-dropdown { text-align: right; font-size: .875rem; font-family: inherit; border-radius: .5rem; }
    persian-datepicker-element { display: block; width: 100%; font-family: 'Vazirmatn', Tahoma, sans-serif; }
    .datepicker-wrap { padding: 0.25rem 0.5rem; }
</style>

@endpush

@push('scripts')
<script type="module" src="https://cdn.jsdelivr.net/npm/persian-datepicker-element@1.1.17/dist/persian-datepicker-element.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
(function () {
    const formErrors = @json($errors->messages(), JSON_HEX_TAG | JSON_HEX_AMP);
    const initial = {
        speakers: @json($speakerRows, JSON_HEX_TAG | JSON_HEX_AMP),
        sessions: @json($sessionRows, JSON_HEX_TAG | JSON_HEX_AMP),
    };

    // ---------- Categories (Tom Select) ----------
    const cats = document.getElementById('categories');
    if (cats) new TomSelect(cats, { plugins: ['remove_button'], placeholder: 'دسته‌ها را انتخاب کنید', maxOptions: null });

    // ---------- Location type ----------
    const typeSel = document.getElementById('location_type');
    const addressWrap = document.getElementById('address-wrap');
    const linkWrap = document.getElementById('link-wrap');
    const platformWrap = document.getElementById('platform-wrap');
    function syncLocation() {
        const t = typeSel.value;
        const physical = t === 'physical' || t === 'hybrid';
        const online = t === 'online' || t === 'hybrid';
        addressWrap.classList.toggle('hidden', !physical);
        linkWrap.classList.toggle('hidden', !online);
        platformWrap.classList.toggle('hidden', !online);
    }
    typeSel.addEventListener('change', syncLocation);
    syncLocation();

    // ---------- Payment ----------
    const pay = document.getElementById('requires_payment');
    const price = document.getElementById('price_amount');
    const priceWrap = document.getElementById('price-wrap');
    function syncPay() {
        price.disabled = !pay.checked;
        priceWrap.classList.toggle('opacity-50', !pay.checked);
    }
    pay.addEventListener('change', syncPay);
    syncPay();

    // ---------- Jalali date + time -> hidden field ----------
    (function () {
        const pad = (n) => String(n).padStart(2, '0');
        const toLatin = (s) => String(s)
            .replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
            .replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));

        function setupDateTime(name) {
            const hidden = document.getElementById(name);
            const picker = document.getElementById(name + '_picker');
            const time   = document.getElementById(name + '_time');
            if (!hidden || !picker || !time) return;

            let date = null; // "1403/08/15"

            // مقدار اولیه (old یا مقدار ذخیره‌شده)
            const m = toLatin(hidden.value || '').trim().match(/^(\d{4})\/(\d{1,2})\/(\d{1,2})/);
            if (m) {
                date = `${m[1]}/${pad(m[2])}/${pad(m[3])}`;
                customElements.whenDefined('persian-datepicker-element').then(() => {
                    picker.setValue(+m[1], +m[2], +m[3]);
                });
            }

            function sync() {
                if (!date) { hidden.value = ''; return; }
                hidden.value = time.value ? `${date} ${time.value}` : date;
            }

            picker.addEventListener('change', (e) => {
                const j = e.detail && e.detail.jalali;
                date = Array.isArray(j) && j.length === 3 ? `${j[0]}/${pad(j[1])}/${pad(j[2])}` : null;
                sync();
            });
            time.addEventListener('input', sync);
            time.addEventListener('change', sync);
            hidden.form && hidden.form.addEventListener('submit', sync);

            sync();
        }

        ['starts_at', 'ends_at', 'registration_deadline'].forEach(setupDateTime);
    })();

    // ---------- Dynamic rows ----------
    function createRepeater(cfg) {
        const container = document.getElementById(cfg.container);
        const template = document.getElementById(cfg.template);
        const empty = document.getElementById(cfg.empty);
        let next = cfg.next;

        function renumber() {
            container.querySelectorAll('[data-row]').forEach((row, i) => {
                const n = row.querySelector('[data-row-number]');
                if (n) n.textContent = (i + 1).toLocaleString('fa-IR');
            });
            empty.classList.toggle('hidden', container.children.length > 0);
        }

        function add(index, values) {
            const holder = document.createElement('div');
            holder.innerHTML = template.innerHTML.replaceAll('__INDEX__', index).trim();
            const row = holder.firstElementChild;

            row.querySelectorAll('[data-field]').forEach((el) => {
                const field = el.dataset.field;
                if (values && values[field] !== undefined && values[field] !== null) el.value = values[field];

                const msgs = formErrors[cfg.prefix + '.' + index + '.' + field];
                if (msgs && msgs.length) {
                    if (el.tagName !== 'SELECT') {
                        el.classList.remove('border-slate-300');
                        el.classList.add('border-red-400');
                    }
                    const p = document.createElement('p');
                    p.className = 'mt-1.5 text-xs text-red-600';
                    p.textContent = msgs[0];
                    el.closest('[data-field-wrap]').appendChild(p);
                }
            });

            row.querySelector('[data-remove]').addEventListener('click', () => {
                row.querySelectorAll('select').forEach((s) => s.tomselect && s.tomselect.destroy());
                row.remove();
                renumber();
            });

            container.appendChild(row);
            row.querySelectorAll('select[data-tom]').forEach((s) => {
                new TomSelect(s, { allowEmptyOption: true, placeholder: 'انتخاب سخنران', maxOptions: null });
            });
            renumber();
        }

        document.getElementById(cfg.addBtn).addEventListener('click', () => add(next++));
        Object.entries(cfg.initial || {}).forEach(([i, v]) => add(i, v));
        renumber();
    }

    createRepeater({
        container: 'speakers-container', template: 'speaker-template', empty: 'speakers-empty',
        addBtn: 'add-speaker', prefix: 'speakers', initial: initial.speakers, next: {{ $nextSpeakerIndex }},
    });
    createRepeater({
        container: 'sessions-container', template: 'session-template', empty: 'sessions-empty',
        addBtn: 'add-session', prefix: 'sessions', initial: initial.sessions, next: {{ $nextSessionIndex }},
    });
})();
</script>
@endpush