<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;

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
}