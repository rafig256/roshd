<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityCategory;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function create()
    {
        if (!auth()->user()->is_innovator) {
            return redirect()->route('user.dashboard')
                ->with('error', 'برای ثبت طرح، ابتدا باید درخواست فناوری شما تأیید شود.');
        }

        if (Plan::where('innovator_id', auth()->id())->exists()) {
            return back()->with('error', 'شما قبلاً یک طرح ثبت کرده‌اید. هر فناور فقط می‌تواند یک طرح داشته باشد.');
        }

        $categories = ActivityCategory::where('is_active', true)->get();
        return view('user.plans.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_innovator) {
            return redirect()->route('user.dashboard')
                ->with('error', 'برای ثبت طرح، ابتدا باید درخواست فناوری شما تأیید شود.');
        }

        if (Plan::where('innovator_id', auth()->id())->exists()) {
            return back()->with('error', 'شما قبلاً یک طرح ثبت کرده‌ید. هر فناور فقط می‌تواند یک طرح داشته باشد.');
        }

        $validated = $request->validate([
            'title'                     => 'required|string|max:255',
            'activity_category_id'      => 'required|exists:activity_categories,id',
            'estimated_duration_months' => 'nullable|integer|min:1',
            'estimated_budget'          => 'nullable|integer|min:0',
            'description'               => 'required|string',
        ]);

        $slug = auth()->id() . '-' . time();

        Plan::create([
            'slug'                      => $slug,
            'innovator_id'              => auth()->id(),
            'title'                     => $validated['title'],
            'activity_category_id'      => $validated['activity_category_id'],
            'estimated_duration_months' => $validated['estimated_duration_months'] ?? null,
            'estimated_budget'          => $validated['estimated_budget'] ?? null,
            'description'               => $validated['description'],
            'status'                    => 'pending',
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'طرح شما با موفقیت ثبت شد و در انتظار بررسی است.');
    }

    public function edit(Plan $plan)
    {
        if ($plan->innovator_id !== auth()->id()) {
            abort(403);
        }

        if ($plan->status !== 'rejected') {
            return back()->with('error', 'شما فقط می‌توانید طرح‌های رد شده را ویرایش کنید.');
        }

        $categories = ActivityCategory::where('is_active', true)->get();
        return view('user.plans.edit', compact('plan', 'categories'));
    }

    public function update(Request $request, Plan $plan)
    {
        if ($plan->innovator_id !== auth()->id()) {
            abort(403);
        }

        if ($plan->status !== 'rejected') {
            return back()->with('error', 'شما فقط می‌توانید طرح‌های رد شده را ویرایش کنید.');
        }

        $validated = $request->validate([
            'title'                     => 'required|string|max:255',
            'activity_category_id'      => 'required|exists:activity_categories,id',
            'estimated_duration_months' => 'nullable|integer|min:1',
            'estimated_budget'          => 'nullable|integer|min:0',
            'description'               => 'required|string',
        ]);

        $plan->update([
            'title'                     => $validated['title'],
            'activity_category_id'      => $validated['activity_category_id'],
            'estimated_duration_months' => $validated['estimated_duration_months'] ?? null,
            'estimated_budget'          => $validated['estimated_budget'] ?? null,
            'description'               => $validated['description'],
            'status'                    => 'pending',
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'طرح شما ویرایش شد و در انتظار بررسی مجدد است.');
    }
}