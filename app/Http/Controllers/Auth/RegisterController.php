<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'family'         => 'required|string|max:100',
            'national_code'  => 'required|string|max:10|unique:users,national_code',
            'mobile'         => 'required|string|max:11|unique:users,mobile',
            'password'       => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        auth()->login($user);

        return redirect()->route('home')
            ->with('success', 'ثبت‌نام با موفقیت انجام شد.');
    }
}