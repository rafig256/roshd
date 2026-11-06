<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with(['representative', 'activityCategory'])
            ->latest()
            ->paginate(10);

        return view('admin.organizations.index', compact('organizations'));
    }

    public function show(Organization $organization)
    {
        $organization->load(['representative', 'activityCategory', 'members.user', 'joinRequests.user']);
        return view('admin.organizations.show', compact('organization'));
    }

    public function extendExpiry(Request $request, Organization $organization)
    {
        $request->validate([
            'months' => 'required|integer|min:1|max:24',
        ]);

        $months = (int) $request->months;

        if ($organization->team_expires_at && $organization->team_expires_at->isFuture()) {
            $newDate = $organization->team_expires_at->addMonths($months);
        } else {
            $newDate = now()->addMonths($months);
        }

        $organization->update([
            'team_expires_at' => $newDate,
        ]);

        return back()->with('success', 'اعتبار تیم با موفقیت تمدید شد.');
    }
}