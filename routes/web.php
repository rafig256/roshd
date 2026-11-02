<?php

use Illuminate\Support\Facades\Route;

// --- کنترلرهای پنل مدیریت (Admin) ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InnovatorRequestController as AdminInnovatorRequestController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController; // 👈 Alias اضافه شد
use App\Http\Controllers\FileController;

// --- کنترلرهای کاربران عادی (User) ---
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController as UserLoginController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\InnovatorRequestController as UserInnovatorRequestController;
use App\Http\Controllers\User\PlanController as UserPlanController; // 👈 Alias اضافه شد
use App\Http\Controllers\User\OrganizationController;
use App\Http\Controllers\User\OrganizationJoinRequestController;

// ==========================================
// ۱. مسیرهای عمومی سایت (بدون نیاز به لاگین)
// ==========================================
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// ==========================================
// ۲. مسیرهای کاربران عادی (فناوران)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserLoginController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');
    
    // مسیرهای درخواست فناوری (کاربر عادی)
    Route::get('/innovator-request', [UserInnovatorRequestController::class, 'create'])->name('innovator-request.create');
    Route::post('/innovator-request', [UserInnovatorRequestController::class, 'store'])->name('innovator-request.store');
    Route::get('/innovator-request/{innovatorRequest}/edit', [UserInnovatorRequestController::class, 'edit'])->name('innovator-request.edit');
    Route::put('/innovator-request/{innovatorRequest}', [UserInnovatorRequestController::class, 'update'])->name('innovator-request.update');

    // مسیرهای طرح‌ها (ایده محوری - کاربر عادی)
    Route::get('/plans/create', [UserPlanController::class, 'create'])->name('plans.create');
    Route::post('/plans', [UserPlanController::class, 'store'])->name('plans.store');
    Route::get('/plans/{plan}/edit', [UserPlanController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/{plan}', [UserPlanController::class, 'update'])->name('plans.update');

    // مسیرهای سازمان/تیم (کاربر عادی)
    Route::get('/organizations/create', [OrganizationController::class, 'create'])->name('organizations.create');
    Route::post('/organizations', [OrganizationController::class, 'store'])->name('organizations.store');
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');
    Route::get('/organizations/{organization}/edit', [OrganizationController::class, 'edit'])->name('organizations.edit');
    Route::put('/organizations/{organization}', [OrganizationController::class, 'update'])->name('organizations.update');
    Route::put('/organizations/{organization}/members/{member}', [OrganizationController::class, 'updateMemberPosition'])->name('organizations.members.update');
    Route::delete('/organizations/{organization}/members/{member}', [OrganizationController::class, 'removeMember'])->name('organizations.members.destroy');
    Route::post('/organizations/{organization}/join-requests/{joinRequest}/approve', [OrganizationJoinRequestController::class, 'approve'])->name('organizations.join-requests.approve');
    Route::post('/organizations/{organization}/join-requests/{joinRequest}/reject', [OrganizationJoinRequestController::class, 'reject'])->name('organizations.join-requests.reject');

    Route::get('/download/resume/{filename}', [FileController::class, 'downloadResume'])->name('download.resume');
});

// ==========================================
// ۳. مسیرهای پنل مدیریت (فقط ادمین‌ها)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // مهمان (کسانی که لاگین نیستند)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    });

    // کاربران لاگین شده با نقش‌های مدیریتی
    Route::middleware(['auth', 'role:super_admin|site_admin|expert|pr_officer'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
        
        // تنظیمات
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        // کاربران
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // درخواست‌های فناوری (ادمین)
        Route::get('/innovator-requests', [AdminInnovatorRequestController::class, 'index'])->name('innovator-requests.index');
        Route::get('/innovator-requests/{innovatorRequest}', [AdminInnovatorRequestController::class, 'show'])->name('innovator-requests.show');
        Route::post('/innovator-requests/{innovatorRequest}/approve', [AdminInnovatorRequestController::class, 'approve'])->name('innovator-requests.approve');
        Route::post('/innovator-requests/{innovatorRequest}/reject', [AdminInnovatorRequestController::class, 'reject'])->name('innovator-requests.reject');
        
        // طرح‌ها (ادمین)
        Route::get('/plans', [AdminPlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/{plan}', [AdminPlanController::class, 'show'])->name('plans.show');
        Route::post('/plans/{plan}/approve', [AdminPlanController::class, 'approve'])->name('plans.approve');
        Route::post('/plans/{plan}/reject', [AdminPlanController::class, 'reject'])->name('plans.reject');
        
        // دانلود رزومه
        Route::get('/download/resume/{filename}', [FileController::class, 'downloadResume'])->name('download.resume');
    });
});