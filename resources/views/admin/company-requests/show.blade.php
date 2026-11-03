@extends('admin.layouts.app')

@section('title', 'بررسی درخواست تبدیل به شرکت')
@section('page-title', 'بررسی درخواست تبدیل به شرکت')

@section('content')
@php
    $companyTypes = [
        'private_joint_stock' => 'سهامی خاص',
        'limited_liability'   => 'مسئولیت محدود',
        'general_partnership' => 'تضامنی',
        'other'               => 'دیگر',
    ];
    $statusLabels = [
        'team'            => 'تیم',
        'pending_company' => 'در انتظار تبدیل به شرکت',
        'company'         => 'شرکت',
    ];
    $statusStyles = [
        'team'            => ['class' => 'bg-sky-100 text-sky-800 border-sky-200',             'dot' => 'bg-sky-500'],
        'pending_company' => ['class' => 'bg-amber-100 text-amber-800 border-amber-200',       'dot' => 'bg-amber-500'],
        'company'         => ['class' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500'],
    ];

    $statusValue = $organization->status instanceof \BackedEnum ? $organization->status->value : $organization->status;
    $statusLabel = $organization->status instanceof \BackedEnum && method_exists($organization->status, 'label')
        ? $organization->status->label()
        : ($statusLabels[$statusValue] ?? ($statusValue ?: 'نامشخص'));
    $st = $statusStyles[$statusValue] ?? ['class' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-400'];

    $rep     = $organization->representative;
    $repName = trim(($rep?->name ?? '') . ' ' . ($rep?->family ?? ''));

    // اطلاعات حقوقی: از $legal (اگر کنترلر بفرستد) یا مستقیم از خود سازمان
    $legal = $legal ?? $organization;

    $establishedRaw = $legal->established_date ?? null;
    $established = null;
    if ($establishedRaw) {
        $rawStr = (string) $establishedRaw;
        if (preg_match('/^(\d{4})[\/-](\d{1,2})[\/-](\d{1,2})/', $rawStr, $m) && (int) $m[1] < 1700) {
            $established = sprintf('%04d/%02d/%02d', $m[1], $m[2], $m[3]);
        } else {
            $established = \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($rawStr))->format('Y/m/d');
        }
    }
    $companyTypeRaw   = $legal->company_type instanceof \BackedEnum ? $legal->company_type->value : $legal->company_type;
    $companyTypeLabel = $companyTypes[$companyTypeRaw] ?? $companyTypeRaw;

    $previousReason = $organization->company_rejection_reason ?? null;
@endphp

<div class="max-w-4xl mx-auto">

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-2 px-4 py-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">درخواست تبدیل «{{ $organization->name }}» به شرکت</h2>
            <p class="mt-1 text-sm text-slate-500">
                نماینده: <span class="font-semibold text-slate-700">{{ $repName !== '' ? $repName : 'نامشخص' }}</span>
                <span class="mx-1 text-slate-300">|</span>
                <a href="{{ route('admin.organizations.show', $organization->id) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors">مشاهده‌ی سازمان</a>
            </p>
        </div>

        <span class="inline-flex items-center gap-2 self-start px-4 py-1.5 text-sm font-bold rounded-full border {{ $st['class'] }}">
            <span class="w-2 h-2 rounded-full {{ $st['dot'] }}"></span>
            {{ $statusLabel }}
        </span>
    </div>

    <div class="space-y-6">

        {{-- Previous rejection --}}
        @if (!empty($previousReason) && $statusValue !== 'company')
            <section class="rounded-xl border border-red-200 bg-red-50 p-6">
                <h3 class="flex items-center gap-2 text-sm font-bold text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 9v4M12 17h.01M10.3 3.9L2.4 18a2 2 0 001.7 3h15.8a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/></svg>
                    دلیل رد درخواست قبلی
                </h3>
                <p class="mt-3 text-sm leading-8 text-red-800 whitespace-pre-line">{{ $previousReason }}</p>
            </section>
        @endif

        {{-- Legal details --}}
        <section class="rounded-xl border border-slate-200 bg-white">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h2M13 7h2M9 11h2M13 11h2M10 21v-4h4v4"/></svg>
                </span>
                <h3 class="text-base font-semibold text-slate-800">مشخصات حقوقی ثبت‌شده</h3>
            </div>
            <dl class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <dt class="text-xs text-slate-400">نام رسمی شرکت</dt>
                    <dd class="mt-1 text-sm font-bold text-slate-900">{{ $legal->legal_name ?: 'ثبت نشده' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">نوع شرکت</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800">{{ $companyTypeLabel ?: 'ثبت نشده' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">شناسه ملی</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800" dir="ltr" style="text-align:right">{{ $legal->national_id ?: 'ثبت نشده' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">شماره ثبت</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800" dir="ltr" style="text-align:right">{{ $legal->registration_number ?: 'ثبت نشده' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">کد اقتصادی</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800" dir="ltr" style="text-align:right">
                        @if ($legal->economic_code) {{ $legal->economic_code }} @else <span class="text-slate-400" dir="rtl">ثبت نشده</span> @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">تاریخ تأسیس</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800">
                        @if ($established)
                            <span dir="ltr" class="inline-block">{{ $established }}</span>
                        @else
                            <span class="text-slate-400">ثبت نشده</span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs text-slate-400">آدرس حقوقی</dt>
                    <dd class="mt-2 p-4 text-sm leading-8 text-slate-700 bg-slate-50 rounded-lg border border-slate-100 whitespace-pre-line">{{ $legal->legal_address ?: 'ثبت نشده' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">تلفن</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800" dir="ltr" style="text-align:right">
                        @if ($legal->phone) {{ $legal->phone }} @else <span class="text-slate-400" dir="rtl">ثبت نشده</span> @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">ایمیل</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800" dir="ltr" style="text-align:right">
                        @if ($legal->email) {{ $legal->email }} @else <span class="text-slate-400" dir="rtl">ثبت نشده</span> @endif
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs text-slate-400">وب‌سایت</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800" dir="ltr" style="text-align:right">
                        @if ($legal->website) {{ $legal->website }} @else <span class="text-slate-400" dir="rtl">ثبت نشده</span> @endif
                    </dd>
                </div>
            </dl>
        </section>

        {{-- Team info (summary) --}}
        <section class="rounded-xl border border-slate-200 bg-white">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                <span class="w-9 h-9 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
                <h3 class="text-base font-semibold text-slate-800">اطلاعات تیم</h3>
            </div>
            <dl class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-x-8 gap-y-5">
                <div>
                    <dt class="text-xs text-slate-400">نام تیم</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800">{{ $organization->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">دسته فعالیت</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800">{{ $organization->activityCategory->name ?? 'ثبت نشده' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">کد ملی نماینده</dt>
                    <dd class="mt-1 text-sm font-medium text-slate-800" dir="ltr" style="text-align:right">{{ $rep?->national_code ?? 'ثبت نشده' }}</dd>
                </div>
            </dl>
        </section>

        {{-- Actions --}}
        @if ($statusValue === 'pending_company')
            <section class="rounded-xl border border-slate-200 bg-slate-50 p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">تعیین وضعیت درخواست</h3>
                <div class="flex flex-col sm:flex-row gap-3">

                    <form method="POST" action="{{ route('admin.company-requests.approve', $organization->id) }}"
                          onsubmit="return confirm('آیا از تبدیل این تیم به شرکت رسمی مطمئن هستید؟');">
                        @csrf
                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-lg
                                       hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            تأیید تبدیل به شرکت
                        </button>
                    </form>

                    <button type="button" id="open-reject-modal"
                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg
                                   hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
                        رد درخواست
                    </button>
                </div>
            </section>
        @endif

        {{-- Back --}}
        <div>
            <a href="{{ route('admin.organizations.show', $organization->id) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                بازگشت به واحدهای فناور
            </a>
        </div>
    </div>
</div>

{{-- Reject modal (native <dialog>) --}}
@if ($statusValue === 'pending_company')
    <dialog id="reject-modal"
            class="w-full max-w-lg p-0 rounded-2xl shadow-2xl backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm"
            style="margin:auto">
        <form method="POST" action="{{ route('admin.company-requests.reject', $organization->id) }}" dir="rtl" class="font-sans">
            @csrf

            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">رد درخواست تبدیل به شرکت</h3>
                <button type="button" id="close-reject-modal" aria-label="بستن"
                        class="p-1.5 text-slate-400 rounded-lg hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>

            <div class="p-6">
                <label for="company_rejection_reason" class="block mb-2 text-sm font-medium text-slate-700">
                    دلیل رد درخواست <span class="text-red-500">*</span>
                </label>
                <textarea id="company_rejection_reason" name="company_rejection_reason" rows="5" required
                          placeholder="دلیل رد درخواست را برای نماینده‌ی تیم بنویسید..."
                          class="w-full px-4 py-3 text-sm leading-7 rounded-lg border bg-slate-50 resize-y
                                 focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition
                                 @error('company_rejection_reason') border-red-400 @else border-slate-300 @enderror">{{ old('company_rejection_reason') }}</textarea>
                @error('company_rejection_reason')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="px-6 py-4 bg-slate-50 rounded-b-2xl flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button" id="cancel-reject-modal"
                        class="px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg hover:bg-white transition-colors">
                    انصراف
                </button>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    ثبت رد درخواست
                </button>
            </div>
        </form>
    </dialog>

    @push('scripts')
    <script>
        (function () {
            const modal = document.getElementById('reject-modal');
            if (!modal) return;

            document.getElementById('open-reject-modal')?.addEventListener('click', () => modal.showModal());
            document.getElementById('close-reject-modal')?.addEventListener('click', () => modal.close());
            document.getElementById('cancel-reject-modal')?.addEventListener('click', () => modal.close());

            // کلیک روی پس‌زمینه، مودال را ببندد
            modal.addEventListener('click', (e) => {
                const r = modal.getBoundingClientRect();
                const inside = e.clientX >= r.left && e.clientX <= r.right && e.clientY >= r.top && e.clientY <= r.bottom;
                if (!inside) modal.close();
            });

            // اگر اعتبارسنجی دلیل رد شکست خورد، مودال دوباره باز شود
            @if ($errors->has('company_rejection_reason'))
                modal.showModal();
            @endif
        })();
    </script>
    @endpush
@endif
@endsection