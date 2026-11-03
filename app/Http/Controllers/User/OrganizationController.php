<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityCategory;
use App\Models\Organization;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\OrganizationMember;
use Morilog\Jalali\CalendarUtils;
use App\Enums\OrganizationStatus;

class OrganizationController extends Controller
{
    public function create()
    {
        if (!auth()->user()->is_innovator) {
            return back()->with('error', 'برای ایجاد تیم، ابتدا باید درخواست فناوری شما تأیید شود.');
        }

        if (Organization::where('representative_id', auth()->id())->exists()) {
            return back()->with('error', 'شما قبلاً نمایده یک تیم هستید. هر کاربر فقط می‌تواند نمایده یک تیم باشد.');
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
            return back()->with('error', 'شما قبلاً نمایده یک تیم هستید. هر کاربر فقط می‌تواند نمایده یک تیم باشد.');
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

    public function show(Organization $organization)
    {
        if ($organization->representative_id !== auth()->id()) {
            abort(403);
        }

        $organization->load([
            'activityCategory',
            'members' => function ($query) {
                $query->where('is_active', true)->with('user');
            },
            'joinRequests' => function ($query) {
                $query->where('status', 'pending')->with('user');
            }
        ]);

        $members = $organization->members;
        $joinRequests = $organization->joinRequests;

        return view('user.organizations.show', compact('organization', 'members', 'joinRequests'));
    }

    public function edit(Organization $organization)
    {
        if ($organization->representative_id !== auth()->id()) {
            abort(403);
        }

        return view('user.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        if ($organization->representative_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($organization->logo_path) {
                $oldFilename = basename($organization->logo_path);
                if (Storage::disk('public')->exists('logos/' . $oldFilename)) {
                    Storage::disk('public')->delete('logos/' . $oldFilename);
                }
            }
            $organization->logo_path = $request->file('logo')->store('logos', 'public');
        }

        $organization->update([
            'name'        => $validated['name'],
            'description' => $validated['description'],
        ]);

        return back()->with('success', 'اطلاعات تیم با موفقیت به‌روزرسانی شد.');
    }

    public function updateMemberPosition(Request $request, Organization $organization, OrganizationMember $member)
    {
        if ($organization->representative_id !== auth()->id()) {
            abort(403);
        }

        if ($member->organization_id !== $organization->id) {
            abort(403);
        }

        $validated = $request->validate([
            'position' => 'required|string|max:255',
        ]);

        $member->update([
            'position' => $validated['position'],
        ]);

        return back()->with('success', 'سمت عضو با موفقیت به‌روزرسانی شد.');
    }

    public function removeMember(Organization $organization, OrganizationMember $member)
    {
        if ($organization->representative_id !== auth()->id()) {
            abort(403);
        }

        if ($member->organization_id !== $organization->id) {
            abort(403);
        }

        if ($member->user_id === auth()->id()) {
            return back()->with('error', 'شما نمی‌توانید نمایده تیم (خودتان) را حذف کنید.');
        }

        $member->update([
            'is_active' => false,
            'left_at'   => now(),
        ]);

        return back()->with('success', 'عضو از تیم حذف شد.');
    }

    public function showConvertForm(Organization $organization)
    {
        if ($organization->representative_id !== auth()->id()) {
            abort(403);
        }

        if ($organization->status !== OrganizationStatus::Team) {
            return redirect()->route('user.organizations.show', $organization->id)
                ->with('error', 'فقط تیم‌ها می‌توانند درخواست تبدیل به شرکت دهند.');
        }

        return view('user.organizations.convert-to-company', compact('organization'));
    }

    public function convertToCompany(Request $request, Organization $organization)
    {
        if ($organization->representative_id !== auth()->id()) {
            abort(403);
        }

        if ($organization->status !== OrganizationStatus::Team) {
            return redirect()->route('user.organizations.show', $organization->id)
                ->with('error', 'فقط تیم‌ها می‌توانند درخواست تبدیل به شرکت دهند.');
        }

        $validated = $request->validate([
            'legal_name'           => 'required|string|max:255',
            'national_id'          => 'required|digits:11',
            'registration_number'  => 'required|string|max:50',
            'economic_code'        => 'nullable|string|max:20',
            'established_date'     => 'required|regex:/^\d{4}\/\d{2}\/\d{2}$/',
            'company_type'         => 'required|in:private_joint_stock,limited_liability,general_partnership,other',
            'legal_address'        => 'required|string',
            'phone'                => 'nullable|string|max:20',
            'website'              => 'nullable|url|max:255',
            'email'                => 'nullable|email|max:255',
        ]);

        // Convert Jalali date to Gregorian
        $parts = explode('/', $validated['established_date']);
        $gregorianArray = CalendarUtils::toGregorian(
            (int)$parts[0],
            (int)$parts[1],
            (int)$parts[2]
        );
        $establishedDate = implode('-', $gregorianArray);

        $organization->update([
            'legal_name'           => $validated['legal_name'],
            'national_id'          => $validated['national_id'],
            'registration_number'  => $validated['registration_number'],
            'economic_code'        => $validated['economic_code'],
            'established_date'     => $establishedDate,
            'company_type'         => $validated['company_type'],
            'legal_address'        => $validated['legal_address'],
            'phone'                => $validated['phone'],
            'website'              => $validated['website'],
            'email'                => $validated['email'],
            'status'               => OrganizationStatus::PendingCompany,
            'company_requested_at' => now(),
        ]);

        return redirect()->route('user.organizations.show', $organization->id)
            ->with('success', 'درخواست تبدیل به شرکت ثبت شد و در انتظار بررسی است.');
    }
}