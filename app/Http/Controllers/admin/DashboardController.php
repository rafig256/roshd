<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\InnovatorRequest;
use App\Models\Organization;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users_count'   => User::count(),
            'plans_count'   => Plan::count(),
            'orgs_count'    => Organization::count(),
            'events_count'  => Event::count(),
        ];

        $pendingReviews = [
            'innovators' => InnovatorRequest::where('status', 'pending')->count(),
            'plans'      => Plan::where('status', 'pending')->count(),
            'companies'  => Organization::where('status', 'pending_company')->count(),
        ];

        $recentEvents = Event::latest('starts_at')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'pendingReviews', 'recentEvents'));
    }
}