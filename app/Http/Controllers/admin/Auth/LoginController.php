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
            $user = Auth::user();
            if ($user->hasAnyRole(['super_admin', 'site_admin', 'expert', 'pr_officer'])) {
                // نقش دارد، پس اجازه ورود می‌دهیم
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            // ⚠️ اگر نقش نداشت، باید لاگین را لغو کنیم
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'national_code' => 'شما دسترسی به پنل مدیریت را ندارید.',
            ])->onlyInput('national_code');
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