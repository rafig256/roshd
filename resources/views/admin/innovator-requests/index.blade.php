@extends('admin.layouts.app')

@section('title', 'مدیریت درخواست‌های فناوری')
@section('page-title', 'مدیریت درخواست‌های فناوری')

@section('content')
    <div class="max-w-7xl">

        {{-- Page header --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-800">درخواست‌های فناوری</h2>
            <p class="mt-1 text-sm text-slate-500">لیست درخواست‌های ارسال شده توسط کاربران برای تبدیل شدن به فناور را اینجا مدیریت کنید.</p>
        </div>

        {{-- Success message --}}
        @if (session('success'))
            <div class="mb-6 flex items-center gap-2 px-4 py-3 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Requests table --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-right text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-sm font-semibold text-slate-700">کاربر</th>
                        <th class="px-6 py-3 text-sm font-semibold text-slate-700">دسته فعالیت</th>
                        <th class="px-6 py-3 text-sm font-semibold text-slate-700">تاریخ ورود</th>
                        <th class="px-6 py-3 text-sm font-semibold text-slate-700">وضعیت</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700 w-48">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($requests as $request)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-800">
                                <a href="{{ route('admin.innovator-requests.show', $request->id) }}" class="font-medium hover:text-indigo-600 hover:underline transition-colors">
                                    {{ $request->user->name }} {{ $request->user->family }}
                                </a>
                                <div class="text-xs text-slate-500" dir="ltr">{{ $request->user->national_code }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-800">
                                {{ $request->activityCategory->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-slate-800" dir="ltr">
                                {{ $request->entry_date ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($request->entry_date))->format('Y/m/d') : '—' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($request->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">در حال بررسی</span>
                                @elseif ($request->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">تأیید شده</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">رد شده</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-left">
                                @if ($request->status === 'pending')
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.innovator-requests.approve', $request->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" onclick="return confirm('آیا از تأیید این درخواست مطمئن هستید؟')" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-emerald-600 rounded-md hover:bg-emerald-700 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                تأیید
                                            </button>
                                        </form>
                                        {{-- دکمه باز کردن مودال رد --}}
                                        <button type="button" onclick="openRejectModal({{ $request->id }})" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            رد
                                        </button>
                                    </div>
                                @elseif ($request->status === 'rejected')
                                    <button type="button" onclick="showReasonModal('{{ addslashes($request->rejection_reason) }}')" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        دلیل رد
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">درخواستی یافت نشد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    </div>

    {{-- Reject Modal (Native HTML5 Dialog) --}}
    <dialog id="rejectModal" class="backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm w-full max-w-lg p-0 rounded-2xl shadow-2xl open:flex open:flex-col" dir="rtl">
        <form id="rejectForm" method="POST" action="" class="flex flex-col h-full bg-white">
            @csrf
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">رد درخواست</h3>
                <button type="button" onclick="document.getElementById('rejectModal').close()" class="p-1.5 text-slate-400 rounded-lg hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <div class="p-6 flex-1">
                <label for="rejection_reason" class="block mb-2 text-sm font-medium text-slate-700">
                    دلیل رد درخواست <span class="text-red-500">*</span>
                </label>
                <textarea id="rejection_reason" name="rejection_reason" rows="5" required placeholder="لطفاً دلیل رد درخواست را وارد کنید..."
                          class="w-full px-4 py-3 text-sm leading-7 rounded-lg border bg-slate-50 resize-y focus:bg-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition border-slate-300"></textarea>
            </div>
            <div class="px-6 py-4 bg-slate-50 rounded-b-2xl flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button" onclick="document.getElementById('rejectModal').close()" class="px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg hover:bg-white transition-colors">
                    انصراف
                </button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    ثبت و رد درخواست
                </button>
            </div>
        </form>
    </dialog>

    {{-- Reason Modal (Native HTML5 Dialog) --}}
    <dialog id="reasonModal" class="backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm w-full max-w-md p-0 rounded-2xl shadow-2xl open:flex open:flex-col" dir="rtl">
        <div class="bg-white rounded-2xl overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">دلیل رد درخواست</h3>
                <button type="button" onclick="document.getElementById('reasonModal').close()" class="p-1.5 text-slate-400 rounded-lg hover:bg-slate-100 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <div class="p-6">
                <p id="reasonText" class="text-sm leading-7 text-slate-700 whitespace-pre-line"></p>
            </div>
            <div class="px-6 py-4 bg-slate-50 flex justify-end">
                <button type="button" onclick="document.getElementById('reasonModal').close()" class="px-5 py-2.5 text-sm font-semibold text-slate-600 border border-slate-300 rounded-lg hover:bg-white transition-colors">
                    بستن
                </button>
            </div>
        </div>
    </dialog>

    <script>
        function openRejectModal(requestId) {
            // تنظیم اکشن فرم برای درخواست انتخاب شده
            const form = document.getElementById('rejectForm');
            form.action = `{{ route('admin.innovator-requests.reject', ':id') }}`.replace(':id', requestId);
            
            // پاک کردن مقدار قبلی
            document.getElementById('rejection_reason').value = '';
            
            // باز کردن مودال
            document.getElementById('rejectModal').showModal();
        }

        function showReasonModal(reason) {
            // قرار دادن دلیل رد در مودال
            document.getElementById('reasonText').textContent = reason;
            
            // باز کردن مودال
            document.getElementById('reasonModal').showModal();
        }
    </script>
@endsection