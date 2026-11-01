<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityCategory;
use App\Models\InnovatorRequest;
use Illuminate\Http\Request;
use Morilog\Jalali\CalendarUtils;

class InnovatorRequestController extends Controller
{
    public function create()
    {
        $categories = ActivityCategory::where('is_active', true)->get();
        return view('user.innovator-request.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_category_id' => 'required|exists:activity_categories,id',
            'entry_date'           => 'nullable|string',
            'bio'                  => 'required|string',
            'motivation'           => 'required|string',
            'resume_file_path'     => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if (InnovatorRequest::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists()) {
            return back()->with('error', 'شما قبلاً درخواست داده‌ید و در حال بررسی است.');
        }

        $path = null;
        if ($request->hasFile('resume_file_path')) {
            $path = $request->file('resume_file_path')->store('resumes', 'public');
        }

        // Convert Jalali date to Gregorian
               $entryDate = null;
        if (!empty($validated['entry_date'])) {
            $parts = explode('/', $validated['entry_date']);
            if (count($parts) === 3) {
                // این متد یک آرایه برمی‌گرداند، مثلاً [2024, 10, 31]
                $gregorianArray = CalendarUtils::toGregorian(
                    (int)$parts[0],
                    (int)$parts[1],
                    (int)$parts[2]
                );
                
                $entryDate = implode('-', $gregorianArray); 
            }
        }

        InnovatorRequest::create([
            'user_id'              => auth()->id(),
            'activity_category_id' => $validated['activity_category_id'],
            'entry_date'           => $entryDate,
            'bio'                  => $validated['bio'],
            'motivation'           => $validated['motivation'],
            'resume_file_path'     => $path,
            'status'               => 'pending',
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'درخواست فناوری شما با موفقیت ثبت شد و در انتظار بررسی قرار گرفت.');
    }
}