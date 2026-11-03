@extends('layouts.public')

@section('title', 'مدیریت تیم')

@section('content')
@php
    // وضعیت تیم (پشتیبانی از enum و رشته)
    $orgStatusValue = $organization->status instanceof \BackedEnum ? $organization->status->value : $organization->status;
    $orgStatusLabel = $organization->status instanceof \BackedEnum && method_exists($organization->status, 'label')
        ? $organization->status->label()
        : match ($orgStatusValue) {
            'team'            => 'تیم',
            'pending_company' => 'در انتظار تبدیل به شرکت',
            'company'         => 'شرکت رسمی',
            'pending'         => 'در انتظار تأیید',
            'approved'        => 'تأیید شده',
            'rejected'        => 'رد شده',
            default           => $orgStatusValue ?: 'نامشخص',
        };
    $orgStatusClass = match ($orgStatusValue) {
        'company', 'approved'        => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        'pending_company', 'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
        'rejected'                   => 'bg-red-100 text-red-800 border-red-200',
        'team'                       => 'bg-sky-100 text-sky-800 border-sky-200',
        default                      => 'bg-slate-100 text-slate-700 border-slate-200',
    };

    // اعضای فعال و درخواست‌های عضویت (از کنترلر یا از رابطه‌ها)
    $members      = $members      ?? $organization->members      ?? collect();
    $joinRequests = $joinRequests ?? $organization->joinRequests ?? collect();

    $createdAt = $organization->created_at
        ? \Morilog\Jalali\Jalalian::fromCarbon($organization->created_at)->format('Y/m/d')
        : null;
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white min-h-[calc(100vh-5rem)]">

    {{-- Decorative blobs --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Back --}}
        <a href="{{ route('user.dashboard') }}"
           class="inline-flex items-center gap-1.5 mb-6 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            بازگشت به پنل کاربری
        </a>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mb-6 flex items-center gap-2 px-4 py-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-6 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- ============ 1. Header ============ --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5 p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                <div class="flex items-center gap-4 min-w-0">
                    @if (!empty($organization->logo_path))
                        <img src="{{ route('file.logo', basename($organization->logo_path)) }}" alt="{{ $organization->name }}"
                             class="w-20 h-20 shrink-0 rounded-2xl object-cover border border-slate-200">
                    @else
                        <div class="w-20 h-20 shrink-0 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 text-white flex items-center justify-center text-3xl font-extrabold shadow-lg shadow-cyan-500/30">
                            {{ mb_substr($organization->name, 0, 1) }}
                        </div>
                    @endif

                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 truncate">{{ $organization->name }}</h1>
                        <span class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full border {{ $orgStatusClass }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $orgStatusLabel }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col items-stretch sm:items-end gap-2 shrink-0">

                    <a href="{{ route('user.organizations.edit', $organization->id) }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-indigo-700 bg-indigo-50 rounded-xl
                            hover:bg-indigo-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 3l5 5L8 21H3v-5L16 3z"/></svg>
                        ویرایش اطلاعات تیم
                    </a>

                    @if ($orgStatusValue === 'team')
                        <a href="{{ route('user.organizations.convert-to-company', $organization->id) }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-white rounded-xl
                                bg-gradient-to-l from-indigo-600 to-fuchsia-500 shadow-md shadow-indigo-500/30
                                hover:shadow-lg hover:-translate-y-0.5 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h2M13 7h2M9 11h2M13 11h2M10 21v-4h4v4"/></svg>
                            درخواست تبدیل به شرکت
                        </a>
                    @elseif ($orgStatusValue === 'pending_company')
                        <span class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-amber-800 bg-amber-100 border border-amber-200 rounded-xl">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            درخواست تبدیل به شرکت در حال بررسی است.
                        </span>
                    @elseif ($orgStatusValue === 'company')
                        <span class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-emerald-800 bg-emerald-100 border border-emerald-200 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            شرکت رسمی
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6 space-y-6">

            {{-- ============ 2. Team info ============ --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 21a9 9 0 100-18 9 9 0 000 18zM12 8h.01M11 12h1v4h1"/></svg>
                    </span>
                    <h2 class="text-base font-semibold text-slate-800">اطلاعات تیم</h2>
                </div>
                <dl class="p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                        <div>
                            <dt class="text-xs text-slate-400">دسته فعالیت</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-800">{{ $organization->activityCategory->name ?? 'ثبت نشده' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400">تاریخ ایجاد</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-800">
                                @if ($createdAt)
                                    <span dir="ltr" class="inline-block">{{ $createdAt }}</span>
                                @else
                                    <span class="text-slate-400">ثبت نشده</span>
                                @endif
                            </dd>
                        </div>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400">توضیحات</dt>
                        <dd class="mt-2 p-4 text-sm leading-8 text-slate-700 bg-slate-50 rounded-lg border border-slate-100 whitespace-pre-line">{{ $organization->description ?: 'توضیحاتی ثبت نشده است.' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- ============ 4. Join requests ============ --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0113-6.2M19 14v6M16 17h6"/></svg>
                        </span>
                        <h2 class="text-base font-semibold text-slate-800">درخواست‌های عضویت</h2>
                    </div>
                    @if ($joinRequests->count())
                        <span class="px-2.5 py-0.5 text-xs font-bold text-amber-800 bg-amber-100 rounded-full">{{ $joinRequests->count() }} درخواست</span>
                    @endif
                </div>

                @if ($joinRequests->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm font-medium text-slate-500">درخواست عضویت جدیدی وجود ندارد.</p>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($joinRequests as $joinRequest)
                            @php
                                $reqUser = $joinRequest->user;
                                $reqName = trim(($reqUser?->name ?? '') . ' ' . ($reqUser?->family ?? ''));
                            @endphp
                            <li class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 shrink-0 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">
                                        {{ mb_substr($reqName !== '' ? $reqName : '؟', 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $reqName !== '' ? $reqName : 'کاربر نامشخص' }}</p>
                                        <p class="text-xs text-slate-400">
                                            ارسال‌شده در
                                            @if ($joinRequest->created_at)
                                                <span dir="ltr" class="inline-block">{{ \Morilog\Jalali\Jalalian::fromCarbon($joinRequest->created_at)->format('Y/m/d') }}</span>
                                            @else
                                                —
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-400"> متن درخواست: {{ $joinRequest->message }} </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <form method="POST" action="{{ route('user.organizations.join-requests.approve', [$organization->id, $joinRequest->id]) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">
                                            تأیید
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('user.organizations.join-requests.reject', [$organization->id, $joinRequest->id]) }}"
                                          onsubmit="return confirm('آیا از رد این درخواست مطمئن هستید؟');">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2 text-xs font-bold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                            رد
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- ============ 3. Members ============ --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-cyan-50 text-cyan-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <h2 class="text-base font-semibold text-slate-800">اعضای تیم</h2>
                    </div>
                    @if ($members->count())
                        <span class="px-2.5 py-0.5 text-xs font-bold text-cyan-800 bg-cyan-100 rounded-full">{{ $members->count() }} عضو</span>
                    @endif
                </div>

                @if ($members->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm font-medium text-slate-500">هنوز عضوی به تیم اضافه نشده است.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-right">
                            <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                                <tr>
                                    <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">نام</th>
                                    <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">سمت</th>
                                    <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">عملیات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($members as $member)
                                    @php
                                        $memUser = $member->user;
                                        $memName = trim(($memUser?->name ?? '') . ' ' . ($memUser?->family ?? ''));
                                        $isRep   = ($memUser?->id ?? $member->user_id) === $organization->representative_id;
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        {{-- Name --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 shrink-0 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">
                                                    {{ mb_substr($memName !== '' ? $memName : '؟', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-slate-800">{{ $memName !== '' ? $memName : 'کاربر نامشخص' }}</p>
                                                    @if ($isRep)
                                                        <span class="inline-block mt-0.5 px-2 py-0.5 text-[11px] font-bold text-indigo-700 bg-indigo-50 rounded-full">نماینده‌ی تیم</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Position (inline update form) --}}
                                        <td class="px-5 py-4">
                                            <form method="POST"
                                                  action="{{ route('user.organizations.members.update', [$organization->id, $member->id]) }}"
                                                  class="flex items-center gap-2 min-w-[16rem]">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="position" value="{{ $member->position }}"
                                                       maxlength="100" placeholder="سمت (مثلاً توسعه‌دهنده)"
                                                       aria-label="سمت {{ $memName }}"
                                                       class="flex-1 min-w-0 px-3 py-2 text-xs rounded-lg border border-slate-300 bg-slate-50 placeholder:text-slate-400
                                                              focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                                <button type="submit"
                                                        class="px-3.5 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                                    ذخیره
                                                </button>
                                            </form>
                                        </td>

                                        {{-- Remove (not for representative) --}}
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            @unless ($isRep)
                                                <form method="POST"
                                                      action="{{ route('user.organizations.members.destroy', [$organization->id, $member->id]) }}"
                                                      onsubmit="return confirm('آیا از حذف این عضو از تیم مطمئن هستید؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3.5 py-2 text-xs font-bold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                                        حذف
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-400">—</span>
                                            @endunless
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection