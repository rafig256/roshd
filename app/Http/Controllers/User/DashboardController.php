<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InnovatorRequest;
use App\Models\Plan;

class DashboardController extends Controller
{
    public function index()
    {
        $latestRequest = InnovatorRequest::where('user_id', auth()->id())->latest()->first();
        $latestPlan = Plan::where('innovator_id', auth()->id())->latest()->first();
        return view('user.dashboard', compact('latestRequest', 'latestPlan'));
    }
}