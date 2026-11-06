@extends('admin.layouts.app')

@section('title', 'مدیریت سازمان‌ها')
@section('page-title', 'مدیریت سازمان‌ها')

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
@endphp

<div>
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-800">تیم‌ها و شرکت‌ها</h2>
        <p class="mt-1 text-sm text-slate-500">فهرست همه‌ی سازمان‌های ثبت‌شده و وضعیت آن‌ها را مدیریت کنید.</p>
    </div>

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

    {{-- Table card --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">نام</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">نوع / وضعیت</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">نماینده</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">تاریخ انقضا</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold whitespace-nowrap">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($organizations as $organization)
                        @php
                            $statusValue = $organization->status instanceof \BackedEnum ? $organization->status->value : $organization->status;
                            $statusLabel = $organization->status instanceof \BackedEnum && method_exists($organization->status, 'label')
                                ? $organization->status->label()
                                : ($statusLabels[$statusValue] ?? ($statusValue ?: 'نامشخص'));
                            $st = $statusStyles[$statusValue] ?? ['class' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-400'];

                            $rep     = $organization->representative;
                            $repName = trim(($rep?->name ?? '') . ' ' . ($rep?->family ?? ''));
                            $expiresAt     = $organization->team_expires_at ? \Carbon\Carbon::parse($organization->team_expires_at) : null;
                            $expiresJalali = $expiresAt ? \Morilog\Jalali\Jalalian::fromCarbon($expiresAt)->format('Y/m/d') : null;
                            $isExpired     = $expiresAt ? $expiresAt->copy()->endOfDay()->isPast() : false;
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if (!empty($organization->logo_path))
                                        <img src="{{ route('file.logo', basename($organization->logo_path)) }}" alt="" loading="lazy"
                                             class="w-10 h-10 shrink-0 rounded-lg object-cover border border-slate-200 bg-white">
                                    @else
                                        <div class="w-10 h-10 shrink-0 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-sm font-bold">
                                            {{ mb_substr($organization->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="font-semibold text-slate-800">{{ $organization->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full border {{ $st['class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }}"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                                {{ $repName !== '' ? $repName : 'نامشخص' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($expiresJalali)
                                    <span dir="ltr" class="inline-block text-sm font-medium {{ $isExpired ? 'text-red-600' : 'text-slate-600' }}">{{ $expiresJalali }}</span>
                                    @if ($isExpired)
                                        <span class="mr-1 px-2 py-0.5 text-[11px] font-bold text-red-700 bg-red-50 rounded-full">منقضی</span>
                                    @endif
                                @else
                                    <span class="text-slate-400">نامشخص</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.organizations.show', $organization->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    مشاهده جزئیات
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="mx-auto w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                                    </svg>
                                </div>
                                <p class="mt-4 text-sm font-medium text-slate-500">هنوز سازمانی ثبت نشده است.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($organizations->hasPages())
            <div class="px-5 py-4 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-xs text-slate-500">
                    نمایش {{ $organizations->firstItem() }} تا {{ $organizations->lastItem() }} از {{ $organizations->total() }} سازمان
                </p>
                <div dir="ltr">
                    {{ $organizations->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection