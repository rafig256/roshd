@extends('admin.layouts.app')

@section('title', 'جزئیات سازمان')
@section('page-title', 'جزئیات سازمان')

@section('content')
@php
    $statusLabels = [
        'team'            => 'تیم',
        'pending_company' => 'در انتظار تبدیل به شرکت',
        'company'         => 'شرکت',
        'pending'         => 'در انتظار تأیید',
        'approved'        => 'تأیید شده',
        'rejected'        => 'رد شده',
    ];
    $statusStyles = [
        'team'            => ['class' => 'bg-sky-100 text-sky-800 border-sky-200',             'dot' => 'bg-sky-500'],
        'pending_company' => ['class' => 'bg-amber-100 text-amber-800 border-amber-200',       'dot' => 'bg-amber-500'],
        'company'         => ['class' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500'],
        'pending'         => ['class' => 'bg-amber-100 text-amber-800 border-amber-200',       'dot' => 'bg-amber-500'],
        'approved'        => ['class' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'dot' => 'bg-emerald-500'],
        'rejected'        => ['class' => 'bg-red-100 text-red-800 border-red-200',             'dot' => 'bg-red-500'],
    ];
    $companyTypes = [
        'private_joint_stock' => 'سهامی خاص',
        'limited_liability'   => 'مسئولیت محدود',
        'general_partnership' => 'تضامنی',
        'other'               => 'دیگر',
    ];

    $statusValue = $organization->status instanceof \BackedEnum ? $organization->status->value : $organization->status;
    $statusLabel = $organization->status instanceof \BackedEnum && method_exists($organization->status, 'label')
        ? $organization->status->label()
        : ($statusLabels[$statusValue] ?? ($statusValue ?: 'نامشخص'));
    $st = $statusStyles[$statusValue] ?? ['class' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-400'];

    $rep     = $organization->representative;
    $repName = trim(($rep?->name ?? '') . ' ' . ($rep?->family ?? ''));

    $members = $members ?? $organization->members ?? collect();

    // اطلاعات حقوقی: از $legal (اگر کنترلر بفرستد) یا مستقیم از خود سازمان
    $legal = $legal ?? $organization;

    // تاریخ تأسیس (میلادی -> شمسی؛ اگر از قبل شمسی ذخیره شده همان نمایش داده می‌شود)
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
        <div class="flex items-center gap-4 min-w-0">
            @if (!empty($organization->logo_path))
                <img src="{{ route('file.logo', basename($organization->logo_path)) }}" alt="لوگوی {{ $organization->name }}"
                     class="w-16 h-16 shrink-0 rounded-xl object-cover border border-slate-200 bg-white">
            @else
                <div class="w-16 h-16 shrink-0 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-2xl font-bold">
                    {{ mb_substr($organization->name, 0, 1) }}
                </div>
            @endif
            <div class="min-w-0">
                <h2 class="text-xl font-bold text-slate-800 truncate">{{ $organization->name }}</h2>
                <p class="mt-1 text-sm text-slate-500">شناسه: <span class="font-semibold text-slate-700">#{{ $organization->id }}</span></p>
            </div>
        </div>

        <span class="inline-flex items-center gap-2 self-start px-4 py-1.5 text-sm font-bold rounded-full border {{ $st['class'] }}">
            <span class="w-2 h-2 rounded-full {{ $st['dot'] }}"></span>
            {{ $statusLabel }}
        </span>
    </div>

    <div class="space-y-6">

        {{-- Pending company alert --}}
        @if ($statusValue === 'pending_company')
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 rounded-xl border border-amber-300 bg-amber-50">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 mt-0.5 shrink-0 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 9v4M12 17h.01M10.3 3.9L2.4 18a2 2 0 001.7 3h15.8a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-amber-900">درخواست تبدیل به شرکت در انتظار بررسی است.</p>
                        <p class="mt-1 text-xs leading-6 text-amber-800">نماینده‌ی تیم مشخصات حقوقی شرکت را ثبت کرده است. لطفاً آن را بررسی و تعیین وضعیت کنید.</p>
                    </div>
                </div>
                <a href="{{ route('admin.company-requests.show', $organization->id) }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-amber-600 rounded-lg hover:bg-amber-700 transition-colors shrink-0">
                    بررسی درخواست شرکت
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                </a>
            </div>
        @endif

        {{-- Organization info --}}
        <section class="rounded-xl border border-slate-200 bg-white">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/></svg>
                </span>
                <h3 class="text-base font-semibold text-slate-800">اطلاعات سازمان</h3>
            </div>
            <dl class="p-6 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                    <div>
                        <dt class="text-xs text-slate-400">نام</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-800">{{ $organization->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">دسته فعالیت</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-800">{{ $organization->activityCategory->name ?? 'ثبت نشده' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">نماینده</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-800">{{ $repName !== '' ? $repName : 'ثبت نشده' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">کد ملی نماینده</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-800" dir="ltr" style="text-align:right">{{ $rep?->national_code ?? 'ثبت نشده' }}</dd>
                    </div>
                </div>
                <div>
                    <dt class="text-xs text-slate-400">توضیحات</dt>
                    <dd class="mt-2 p-4 text-sm leading-8 text-slate-700 bg-slate-50 rounded-lg border border-slate-100 whitespace-pre-line">{{ $organization->description ?: 'ثبت نشده' }}</dd>
                </div>
            </dl>
        </section>

        {{-- Legal info (company) --}}
        @if ($statusValue === 'company')
            <section class="rounded-xl border border-emerald-200 bg-emerald-50">
                <div class="px-6 py-4 border-b border-emerald-200 flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <h3 class="text-base font-semibold text-emerald-900">شرکت رسمی؛ اطلاعات حقوقی</h3>
                </div>
                <dl class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                    <div>
                        <dt class="text-xs text-emerald-700/70">نام رسمی شرکت</dt>
                        <dd class="mt-1 text-sm font-semibold text-emerald-950">{{ $legal->legal_name ?: 'ثبت نشده' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-emerald-700/70">نوع شرکت</dt>
                        <dd class="mt-1 text-sm font-semibold text-emerald-950">{{ $companyTypeLabel ?: 'ثبت نشده' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-emerald-700/70">شناسه ملی</dt>
                        <dd class="mt-1 text-sm font-semibold text-emerald-950" dir="ltr" style="text-align:right">{{ $legal->national_id ?: 'ثبت نشده' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-emerald-700/70">شماره ثبت</dt>
                        <dd class="mt-1 text-sm font-semibold text-emerald-950" dir="ltr" style="text-align:right">{{ $legal->registration_number ?: 'ثبت نشده' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-emerald-700/70">کد اقتصادی</dt>
                        <dd class="mt-1 text-sm font-semibold text-emerald-950" dir="ltr" style="text-align:right">{{ $legal->economic_code ?: 'ثبت نشده' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-emerald-700/70">تاریخ تأسیس</dt>
                        <dd class="mt-1 text-sm font-semibold text-emerald-950">
                            @if ($established)
                                <span dir="ltr" class="inline-block">{{ $established }}</span>
                            @else
                                ثبت نشده
                            @endif
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-emerald-700/70">آدرس حقوقی</dt>
                        <dd class="mt-1 text-sm font-semibold leading-7 text-emerald-950 whitespace-pre-line">{{ $legal->legal_address ?: 'ثبت نشده' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-emerald-700/70">تلفن</dt>
                        <dd class="mt-1 text-sm font-semibold text-emerald-950" dir="ltr" style="text-align:right">{{ $legal->phone ?: 'ثبت نشده' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-emerald-700/70">ایمیل</dt>
                        <dd class="mt-1 text-sm font-semibold text-emerald-950" dir="ltr" style="text-align:right">{{ $legal->email ?: 'ثبت نشده' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-emerald-700/70">وب‌سایت</dt>
                        <dd class="mt-1 text-sm font-semibold text-emerald-950" dir="ltr" style="text-align:right">{{ $legal->website ?: 'ثبت نشده' }}</dd>
                    </div>
                </dl>
            </section>
        @endif

        {{-- Members --}}
        <section class="rounded-xl border border-slate-200 bg-white overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </span>
                    <h3 class="text-base font-semibold text-slate-800">اعضای فعال</h3>
                </div>
                @if ($members->count())
                    <span class="px-2.5 py-0.5 text-xs font-bold text-cyan-800 bg-cyan-100 rounded-full">{{ $members->count() }} عضو</span>
                @endif
            </div>

            @if ($members->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-sm font-medium text-slate-500">عضوی برای این سازمان ثبت نشده است.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($members as $member)
                        @php
                            $memUser = $member->user;
                            $memName = trim(($memUser?->name ?? '') . ' ' . ($memUser?->family ?? ''));
                            $isRep   = ($memUser?->id ?? $member->user_id) === $organization->representative_id;
                        @endphp
                        <li class="px-6 py-4 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 shrink-0 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">
                                    {{ mb_substr($memName !== '' ? $memName : '؟', 0, 1) }}
                                </div>
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $memName !== '' ? $memName : 'کاربر نامشخص' }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if ($isRep)
                                    <span class="px-2.5 py-0.5 text-[11px] font-bold text-indigo-700 bg-indigo-50 rounded-full">نماینده</span>
                                @endif
                                <span class="text-xs text-slate-500">{{ $member->position ?: 'سمت ثبت نشده' }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        {{-- Back --}}
        <div>
            <a href="{{ route('admin.organizations.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                بازگشت به لیست
            </a>
        </div>
    </div>
</div>
@endsection