<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // دریافت تمام تنظیمات به صورت یک آرایه (key => value)
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_version' => 'required|string|max:50',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        // آپدیت کردن هر تنظیم در دیتابیس
        foreach ($validated as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        return back()->with('success', 'تنظیمات با موفقیت ذخیره شد.');
    }
}