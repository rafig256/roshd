<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationJoinRequest;
use App\Models\OrganizationMember;
use Illuminate\Http\Request;

class OrganizationJoinRequestController extends Controller
{
    public function approve(Organization $organization, OrganizationJoinRequest $joinRequest)
    {
        if ($organization->representative_id !== auth()->id()) {
            abort(403);
        }

        if ($joinRequest->organization_id !== $organization->id) {
            abort(403);
        }

        if ($joinRequest->status !== 'pending') {
            return back()->with('error', 'این درخواست قبلاً بررسی شده است.');
        }

        $joinRequest->update([
            'status'       => 'approved',
            'reviewed_by'  => auth()->id(),
            'reviewed_at'  => now(),
        ]);

        OrganizationMember::create([
            'organization_id' => $organization->id,
            'user_id'         => $joinRequest->user_id,
            'position'        => 'عضو',
            'joined_at'       => now(),
            'is_active'       => true,
        ]);

        return back()->with('success', 'درخواست عضویت تأیید شد و کاربر به تیم اضافه گردید.');
    }

    public function reject(Request $request, Organization $organization, OrganizationJoinRequest $joinRequest)
    {
        if ($organization->representative_id !== auth()->id()) {
            abort(403);
        }

        if ($joinRequest->organization_id !== $organization->id) {
            abort(403);
        }

        if ($joinRequest->status !== 'pending') {
            return back()->with('error', 'این درخواست قبلاً بررسی شده است.');
        }

        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $joinRequest->update([
            'status'           => 'rejected',
            'reviewed_by'      => auth()->id(),
            'reviewed_at'      => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'درخواست عضویت رد شد.');
    }
}