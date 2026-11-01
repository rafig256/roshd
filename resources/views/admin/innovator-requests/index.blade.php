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
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
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
                                <a href="{{ route('admin.innovator-requests.show', $request->id) }}"
                                   class="font-medium hover:text-indigo-600 hover:underline transition-colors">
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
                                            <button type="submit"
                                                    onclick="return confirm('آیا از تأیید این درخواست مطمئن هستید؟')"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-emerald-600 rounded-md hover:bg-emerald-700 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                تأیید
                                            </button>
                                        </form>
                                        <button type="button"
                                                onclick="openRejectModal({{ $request->id }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            رد
                                        </button>
                                    </div>
                                @elseif ($request->status === 'rejected')
                                    <button type="button"
                                            onclick="showRejectionReason('{{ $request->rejection_reason }}')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
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

    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-20 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 text-red-600 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-right">
                            <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">رد درخواست</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500">دلیل رد درخواست را وارد کنید:</p>
                                <textarea id="rejectionReason" class="mt-2 w-full px-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none" rows="4" placeholder="دلیل رد..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            onclick="submitReject()"
                            class="w-full inline-flex justify-center rounded-lg px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors sm:ml-3 sm:w-auto sm:px-5">
                        تایید رد
                    </button>
                    <button type="button"
                            onclick="closeRejectModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition-colors sm:mt-0 sm:w-auto sm:px-5">
                        انصراف
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Rejection Reason Modal --}}
    <div id="reasonModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="reason-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-20 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-slate-100 text-slate-600 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-right">
                            <h3 class="text-lg leading-6 font-medium text-slate-900" id="reason-modal-title">دلیل رد درخواست</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 whitespace-pre-wrap" id="rejectionReasonText"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            onclick="closeReasonModal()"
                            class="w-full inline-flex justify-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 transition-colors sm:ml-3 sm:w-auto sm:px-5">
                        بستن
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentRequestId = null;

        function openRejectModal(requestId) {
            currentRequestId = requestId;
            document.getElementById('rejectionReason').value = '';
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            currentRequestId = null;
            document.getElementById('rejectModal').classList.add('hidden');
        }

        function submitReject() {
            const reason = document.getElementById('rejectionReason').value.trim();
            if (!reason) {
                alert('لطفاً دلیل رد را وارد کنید.');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('admin.innovator-requests.reject', ':id') }}`.replace(':id', currentRequestId);
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'rejection_reason';
            reasonInput.value = reason;
            form.appendChild(reasonInput);

            document.body.appendChild(form);
            form.submit();
        }

        function showRejectionReason(reason) {
            document.getElementById('rejectionReasonText').textContent = reason;
            document.getElementById('reasonModal').classList.remove('hidden');
        }

        function closeReasonModal() {
            document.getElementById('reasonModal').classList.add('hidden');
        }

        // Close modals on backdrop click
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
        document.getElementById('reasonModal').addEventListener('click', function(e) {
            if (e.target === this) closeReasonModal();
        });

        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRejectModal();
                closeReasonModal();
            }
        });
    </script>
@endsection