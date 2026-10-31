<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // نمایش صفحه لاگین
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // بررسی اطلاعات ورود
    public function login(Request $request)
    {
        // اعتبارسنجی اولیه
        $credentials = $request->validate([
            'national_code' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // تلاش برای ورود (لاراول به صورت پیش‌فرض از فیلد password استفاده می‌کند)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // هدایت به داشبورد ادمین
            return redirect()->intended(route('admin.dashboard'));
        }

        // اگر ورود ناموفق بود
        return back()->withErrors([
            'national_code' => 'کد ملی یا رمز عبور اشتباه است.',
        ])->onlyInput('national_code');
    }

    // خروج از حساب
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}