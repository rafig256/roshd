<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // شروع کوئری
        $query = User::query();

        // اعمال فیلتر جستجو
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('family', 'like', "%{$search}%")
                  ->orWhere('national_code', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        // دریافت نتایج با صفحه‌بندی (۱۰ تا در هر صفحه)
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // حفظ پارامتر جستجو در لینک‌های صفحه‌بندی
        $users->appends($request->all());

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'innovator')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'family'        => 'required|string|max:100',
            'national_code' => 'required|string|max:10|unique:users,national_code',
            'mobile'        => 'required|string|max:11|unique:users,mobile',
            'password'      => 'required|string|min:6',
            'status'        => 'required|in:active,blocked',
            'roles'         => 'nullable|array',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $user->syncRoles($validated['roles'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'کاربر با موفقیت ایجاد شد.');
    }

    public function edit(User $user)
    {
        $roles = Role::where('name', '!=', 'innovator')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'        => 'required',
            'family'      => 'required',
            'national_code' => 'required|unique:users,national_code,' . $user->id,
            'mobile'      => 'required|unique:users,mobile,' . $user->id,
            'status'      => 'required|in:active,blocked',
            'roles'       => 'array',
        ]);

        $user->update([
            'name'         => $request->name,
            'family'       => $request->family,
            'national_code' => $request->national_code,
            'mobile'       => $request->mobile,
            'status'       => $request->status,
        ]);

        $user->syncRoles($request->roles ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'اطلاعات کاربر با موفقیت ویرایش شد.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'شما نمی‌توانید حساب کاربری خودتان را حذف کنید.');
        }

        $user->delete();

        return back()->with('success', 'کاربر با موفقیت حذف شد.');
    }
}