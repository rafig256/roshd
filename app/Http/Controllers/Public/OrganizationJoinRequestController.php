<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\OrganizationJoinRequest;
use Illuminate\Http\Request;

class OrganizationJoinRequestController extends Controller
{
    public function store(Request $request, Organization $organization)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'برای ارسال درخواست عضویت باید وارد حساب کاربری خود شوید.');
        }

        $userId = auth()->id();

        // Check if user is already a member
        $isMember = OrganizationMember::where('organization_id', $organization->id)
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->exists();

        if ($isMember) {
            return back()->with('error', 'شما از قبل عضو این تیم هستید.');
        }

        // Check if user is a representative of ANY team
        $isRepresentative = Organization::where('representative_id', $userId)->exists();
        if ($isRepresentative) {
            return back()->with('error', 'شما نمایده یک تیم هستید و نمی‌توانید درخواست عضویت بدهید.');
        }

        // Check if user has a pending request for this organization
        $hasPendingRequest = OrganizationJoinRequest::where('organization_id', $organization->id)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingRequest) {
            return back()->with('error', 'شما قبلاً درخواست عضویت در این تیم را ارسال کرده‌ید و در انتظار بررسی است.');
        }

        $validated = $request->validate([
            'message' => 'nullable|string',
        ]);

        OrganizationJoinRequest::create([
            'organization_id' => $organization->id,
            'user_id'         => $userId,
            'message'         => $validated['message'] ?? null,
            'status'          => 'pending',
        ]);

        return back()->with('success', 'درخواست عضویت شما با موفقیت ارسال شد و در انتظار تأیید نمایده تیم قرار گرفت.');
    }
}