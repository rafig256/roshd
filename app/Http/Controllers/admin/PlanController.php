<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\PlanStatus;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with(['innovator', 'activityCategory'])
            ->latest()
            ->paginate(10);

        return view('admin.plans.index', compact('plans'));
    }

    public function show(Plan $plan)
    {
        $plan->load(['innovator', 'activityCategory']);
        return view('admin.plans.show', compact('plan'));
    }

    public function approve(Plan $plan)
    {
        $plan->update(['status' => PlanStatus::Approved]);

        return back()->with('success', 'طرح با موفقیت تأیید شد.');
    }

    public function reject(Request $request, Plan $plan)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $plan->update([
            'status' => PlanStatus::Rejected,
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'طرح با موفقیت رد شد.');
    }
}