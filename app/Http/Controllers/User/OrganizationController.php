<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityCategory;
use App\Models\Organization;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{
    public function create()
    {
        if (!auth()->user()->is_innovator) {
            return back()->with('error', 'برای ایجاد تیم، ابتدا باید درخواست فناوری شما تأیید شود.');
        }

        if (Organization::where('representative_id', auth()->id())->exists()) {
            return back()->with('error', 'شما قبلاً نماینده یک تیم هستید. هر کاربر فقط می‌تواند نماینده یک تیم باشد.');
        }

        $plan = Plan::where('innovator_id', auth()->id())
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$plan) {
            return back()->with('error', 'شما طرح تأییدشده‌ای ندارید. ابتدا باید یک طرح ثبت کرده و آن تأیید شود.');
        }

        $categories = ActivityCategory::where('is_active', true)->get();
        return view('user.organizations.create', compact('categories', 'plan'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_innovator) {
            return back()->with('error', 'برای ایجاد تیم، ابتدا باید درخواست فناوری شما تأیید شود.');
        }

        if (Organization::where('representative_id', auth()->id())->exists()) {
            return back()->with('error', 'شما قبلاً نماینده یک تیم هستید. هر کاربر فقط می‌تواند نماینده یک تیم باشد.');
        }

        $plan = Plan::where('innovator_id', auth()->id())
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$plan) {
            return back()->with('error', 'شما طرح تأییدشده‌ای ندارید. ابتدا باید یک طرح ثبت کرده و آن تأیید شود.');
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $slug = auth()->id() . '-' . time();

        Organization::create([
            'slug'                   => $slug,
            'name'                   => $validated['name'],
            'representative_id'      => auth()->id(),
            'plan_id'                => $plan->id,
            'activity_category_id'   => $plan->activity_category_id,
            'status'                 => 'team',
            'description'            => $validated['description'],
            'logo_path'              => $logoPath,
            'team_approved_at'       => now(),
            'team_approved_by'       => auth()->id(),
            'team_expires_at'        => now()->addMonths(12),
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'تیم شما با موفقیت ایجاد شد.');
    }
}