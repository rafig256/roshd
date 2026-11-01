<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InnovatorRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class InnovatorRequestController extends Controller
{
    public function index()
    {
        $requests = InnovatorRequest::with(['user', 'activityCategory'])
            ->latest()
            ->paginate(10);

        return view('admin.innovator-requests.index', compact('requests'));
    }

    public function approve(InnovatorRequest $innovatorRequest)
    {
        $innovatorRequest->update(['status' => 'approved']);

        $user = $innovatorRequest->user;
        $user->update(['is_innovator' => true]);

        $innovatorRole = Role::where('name', 'innovator')->first();
        if ($innovatorRole) {
            $user->assignRole($innovatorRole);
        }

        return back()->with('success', 'درخواست فناوری تأیید شد.');
    }

    public function reject(Request $request, InnovatorRequest $innovatorRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $innovatorRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'درخواست فناوری رد شد.');
    }
}