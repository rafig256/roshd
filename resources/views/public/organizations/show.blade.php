@extends('layouts.public')

@section('title', $organization->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags((string) $organization->description), 150))

@section('content')
@php
    $rep     = $organization->representative;
    $repName = trim(($rep?->name ?? '') . ' ' . ($rep?->family ?? ''));

    // اعضای فعال (از کنترلر یا رابطه)
    $members = $members ?? $organization->members ?? collect();

    // پرچم‌ها از کنترلر می‌آیند؛ مقدار پیش‌فرض برای حالت ارسال‌نشده
    $isMember          = $isMember          ?? false;
    $isRepresentative  = $isRepresentative  ?? false;
    $hasPendingRequest = $hasPendingRequest ?? false;

    // آیا مودال باید نمایش داده شود؟
    $canRequest = auth()->check() && !$isMember && !$isRepresentative && !$hasPendingRequest;
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-indigo-50 via-white to-white min-h-[calc(100vh-5rem)]">
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full bg-indigo-300/30 blur-3xl"></div>
    <div class="pointer-events-none absolute top-48 -left-24 w-80 h-80 rounded-full bg-fuchsia-300/20 blur-3xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Back --}}
        <a href="{{ route('public.organizations.index') }}"
           class="inline-flex items-center gap-1.5 mb-6 text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            بازگشت به واحدهای فناور
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- ============ Main column ============ --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Team header --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5 p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                        @if (!empty($organization->logo_path))
                            <img src="{{ route('file.logo', basename($organization->logo_path)) }}"
                                 alt="لوگوی {{ $organization->name }}"
                                 class="w-24 h-24 shrink-0 rounded-2xl object-cover border border-slate-200 bg-white">
                        @else
                            <div class="w-24 h-24 shrink-0 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 text-white flex items-center justify-center shadow-lg shadow-cyan-500/30">
                                <svg class="w-11 h-11" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                                </svg>
                            </div>
                        @endif

                        <div class="min-w-0">
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $organization->name }}</h1>

                            <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm">
                                @if (!empty($organization->activityCategory?->name))
                                    <span class="px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-full">
                                        {{ $organization->activityCategory->name }}
                                    </span>
                                @endif
                                <span class="flex items-center gap-1.5 text-slate-600">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0116 0"/></svg>
                                    <span class="text-xs text-slate-400">نماینده:</span>
                                    <span class="font-medium">{{ $repName !== '' ? $repName : 'نامشخص' }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 21a9 9 0 100-18 9 9 0 000 18zM12 8h.01M11 12h1v4h1"/></svg>
                        </span>
                        <h2 class="text-base font-semibold text-slate-800">درباره‌ی تیم</h2>
                    </div>
                    <div class="p-6">
                        @if (!empty($organization->description))
                            <p class="text-sm leading-8 text-slate-700 whitespace-pre-line">{{ $organization->description }}</p>
                        @else
                            <p class="text-sm text-slate-400">توضیحاتی برای این تیم ثبت نشده است.</p>
                        @endif
                    </div>
                </div>

                {{-- Members --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
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
                            <p class="text-sm font-medium text-slate-500">هنوز عضوی برای این تیم نمایش داده نمی‌شود.</p>
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
                                        <div class="w-10 h-10 shrink-0 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">
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
                </div>
            </div>

            {{-- ============ Side column: join CTA ============ --}}
            <aside class="lg:col-span-1">
                <div class="lg:sticky lg:top-28 bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-900/5 p-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 11a4 4 0 10-8 0 4 4 0 008 0zM4 20a8 8 0 0113-6.2M19 14v6M16 17h6"/></svg>
                    </div>
                    <h2 class="mt-4 text-base font-bold text-slate-900">عضویت در تیم</h2>

                    @guest
                        <p class="mt-2 text-sm leading-7 text-slate-600">
                            برای ارسال درخواست عضویت، ابتدا وارد سایت شوید.
                        </p>
                        <a href="{{ route('login') }}"
                           class="mt-5 block w-full py-2.5 text-center text-sm font-bold text-white rounded-xl bg-gradient-to-l from-indigo-600 to-fuchsia-500
                                  shadow-md shadow-indigo-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                            ورود به سایت
                        </a>
                    @endguest

                    @auth
                        @if ($isMember)
                            <p class="mt-4 flex items-start gap-2 px-4 py-3 text-sm font-semibold leading-7 text-emerald-800 bg-emerald-100 rounded-xl">
                                <svg class="w-5 h-5 mt-1 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                شما عضو این تیم هستید.
                            </p>
                        @elseif ($isRepresentative)
                            <p class="mt-4 px-4 py-3 text-sm font-semibold leading-7 text-slate-700 bg-slate-100 rounded-xl">
                                شما نماینده یک تیم هستید و نمی‌توانید در تیم دیگری درخواست دهید.
                            </p>
                        @elseif ($hasPendingRequest)
                            <p class="mt-4 px-4 py-3 text-sm font-semibold leading-7 text-amber-800 bg-amber-100 rounded-xl">
                                درخواست شما در حال بررسی است.
                            </p>
                        @else
                            <p class="mt-2 text-sm leading-7 text-slate-600">
                                اگر می‌خواهید به این تیم بپیوندید، درخواست خود را برای نماینده‌ی تیم ارسال کنید.
                            </p>
                            <button type="button" id="open-join-modal"
                                    class="mt-5 block w-full py-2.5 text-center text-sm font-bold text-white rounded-xl bg-gradient-to-l from-indigo-600 to-fuchsia-500
                                           shadow-md shadow-indigo-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                                درخواست عضویت
                            </button>
                        @endif
                    @endauth
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- Join request modal (native <dialog>) --}}
@if ($canRequest)
    <dialog id="join-modal"
            class="w-full max-w-lg p-0 rounded-2xl shadow-2xl backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm"
            style="margin:auto">
        <form method="POST" action="{{ route('user.public.organizations.join', $organization->id) }}" dir="rtl" class="font-sans">
            @csrf

            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">درخواست عضویت در «{{ $organization->name }}»</h3>
                <button type="button" id="close-join-modal" aria-label="بستن"
                        class="p-1.5 text-slate-400 rounded-lg hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
            </div>

            <div class="p-6">
                <label for="message" class="block mb-2 text-sm font-medium text-slate-700">پیام به نماینده</label>
                <textarea id="message" name="message" rows="5" maxlength="1000"
                          placeholder="خودتان را معرفی کنید و بنویسید چرا می‌خواهید به این تیم بپیوندید..."
                          class="w-full px-4 py-3 text-sm leading-7 rounded-lg border bg-slate-50 resize-y placeholder:text-slate-400
                                 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                 @error('message') border-red-400 @else border-slate-300 @enderror">{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="px-6 py-4 bg-slate-50 rounded-b-2xl flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button" id="cancel-join-modal"
                        class="px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg hover:bg-white transition-colors">
                    انصراف
                </button>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-white rounded-lg bg-gradient-to-l from-indigo-600 to-fuchsia-500 hover:shadow-lg transition-all">
                    ارسال درخواست
                </button>
            </div>
        </form>
    </dialog>

    @push('scripts')
    <script>
        (function () {
            const modal = document.getElementById('join-modal');
            if (!modal) return;

            document.getElementById('open-join-modal')?.addEventListener('click', () => modal.showModal());
            document.getElementById('close-join-modal')?.addEventListener('click', () => modal.close());
            document.getElementById('cancel-join-modal')?.addEventListener('click', () => modal.close());

            // کلیک روی پس‌زمینه، مودال را ببندد
            modal.addEventListener('click', (e) => {
                const r = modal.getBoundingClientRect();
                const inside = e.clientX >= r.left && e.clientX <= r.right && e.clientY >= r.top && e.clientY <= r.bottom;
                if (!inside) modal.close();
            });

            // اگر اعتبارسنجی پیام شکست خورد، مودال دوباره باز شود
            @if ($errors->has('message'))
                modal.showModal();
            @endif
        })();
    </script>
    @endpush
@endif
@endsection