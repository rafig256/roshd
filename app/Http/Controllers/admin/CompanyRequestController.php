<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Enums\OrganizationStatus;
use Illuminate\Http\Request;

class CompanyRequestController extends Controller
{
    public function index()
    {
        $requests = Organization::with(['representative', 'activityCategory'])
            ->where('status', OrganizationStatus::PendingCompany)
            ->latest()
            ->paginate(10);

        return view('admin.company-requests.index', compact('requests'));
    }

    public function show(Organization $organization)
    {
        $organization->load(['representative', 'activityCategory']);
        return view('admin.company-requests.show', compact('organization'));
    }

    public function approve(Organization $organization)
    {
        if ($organization->status !== OrganizationStatus::PendingCompany) {
            return back()->with('error', 'این درخواست قابل بررسی نیست.');
        }

        $organization->update([
            'status'               => OrganizationStatus::Company,
            'company_approved_by'  => auth()->id(),
            'company_approved_at'  => now(),
        ]);

        return back()->with('success', 'درخواست تبدیل به شرکت با موفقیت تأیید شد.');
    }

    public function reject(Request $request, Organization $organization)
    {
        if ($organization->status !== OrganizationStatus::PendingCompany) {
            return back()->with('error', 'این درخواست قابل بررسی نیست.');
        }

        $request->validate([
            'company_rejection_reason' => 'required|string',
        ]);

        $organization->update([
            'status'                   => OrganizationStatus::Team,
            'company_rejection_reason' => $request->company_rejection_reason,
            'company_approved_by'      => null,
            'company_approved_at'      => null,
        ]);

        return back()->with('success', 'درخواست تبدیل به شرکت رد شد و وضعیت تیم به حالت قبل بازگردانده شد.');
    }
}