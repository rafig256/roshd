<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\OrganizationJoinRequest;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $query = Organization::with('activityCategory')
            ->whereIn('status', ['team', 'company'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('activityCategory', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $organizations = $query->paginate(12)->withQueryString();

        return view('public.organizations.index', compact('organizations'));
    }

    public function show(Organization $organization)
    {
        $organization->load([
            'activityCategory',
            'members' => function ($query) {
                $query->where('is_active', true)->with('user');
            }
        ]);

        $isMember = false;
        $isRepresentative = false;
        $hasPendingRequest = false;

        if (auth()->check()) {
            $userId = auth()->id();

            $isMember = $organization->members()->where('user_id', $userId)->where('is_active', true)->exists();

            $isRepresentative = Organization::where('representative_id', $userId)->exists();

            $hasPendingRequest = OrganizationJoinRequest::where('organization_id', $organization->id)
                ->where('user_id', $userId)
                ->where('status', 'pending')
                ->exists();
        }

        return view('public.organizations.show', compact('organization', 'isMember', 'isRepresentative', 'hasPendingRequest'));
    }
}