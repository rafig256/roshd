<?php

use Illuminate\Support\Facades\Route;

// --- کنترلرهای پنل مدیریت (Admin) ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InnovatorRequestController as AdminInnovatorRequestController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\CompanyRequestController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\NewsCategoryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;

// --- کنترلرهای عمومی (Public) ---
use App\Http\Controllers\Public\OrganizationController as PublicOrganizationController;
use App\Http\Controllers\Public\OrganizationJoinRequestController as PublicOrganizationJoinRequestController;

// --- کنترلرهای کاربران عادی (User) ---
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController as UserLoginController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\InnovatorRequestController as UserInnovatorRequestController;
use App\Http\Controllers\User\PlanController as UserPlanController;
use App\Http\Controllers\User\OrganizationController as UserOrganizationController;
use App\Http\Controllers\User\OrganizationJoinRequestController as UserOrganizationJoinRequestController;

// ==========================================
// ۱. مسیرهای عمومی سایت (بدون نیاز به لاگین)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// مسیرهای عمومی واحدهای فناور (تیم‌ها)
Route::get('/organizations', [PublicOrganizationController::class, 'index'])->name('public.organizations.index');
Route::get('/organizations/{organization}', [PublicOrganizationController::class, 'show'])->name('public.organizations.show');

// مسیرهای عمومی برای فایل‌ها
Route::get('/storage/logos/{filename}', [FileController::class, 'showLogo'])->name('file.logo');
Route::get('/storage/news-covers/{filename}', [FileController::class, 'showNewsCover'])->name('file.news-cover');

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
    
    // درخواست فناوری
    Route::get('/innovator-request', [UserInnovatorRequestController::class, 'create'])->name('innovator-request.create');
    Route::post('/innovator-request', [UserInnovatorRequestController::class, 'store'])->name('innovator-request.store');
    Route::get('/innovator-request/{innovatorRequest}/edit', [UserInnovatorRequestController::class, 'edit'])->name('innovator-request.edit');
    Route::put('/innovator-request/{innovatorRequest}', [UserInnovatorRequestController::class, 'update'])->name('innovator-request.update');

    // طرح‌ها (ایده محوری)
    Route::get('/plans/create', [UserPlanController::class, 'create'])->name('plans.create');
    Route::post('/plans', [UserPlanController::class, 'store'])->name('plans.store');
    Route::get('/plans/{plan}/edit', [UserPlanController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/{plan}', [UserPlanController::class, 'update'])->name('plans.update');

    // مدیریت تیم (پنل نماینده) - آدرس‌ها با /my-team شروع می‌شوند
    Route::get('/my-team/create', [UserOrganizationController::class, 'create'])->name('organizations.create');
    Route::post('/my-team', [UserOrganizationController::class, 'store'])->name('organizations.store');
    Route::get('/my-team/{organization}', [UserOrganizationController::class, 'show'])->name('organizations.show');
    Route::get('/my-team/{organization}/edit', [UserOrganizationController::class, 'edit'])->name('organizations.edit');
    Route::put('/my-team/{organization}', [UserOrganizationController::class, 'update'])->name('organizations.update');
    
    Route::put('/my-team/{organization}/members/{member}', [UserOrganizationController::class, 'updateMemberPosition'])->name('organizations.members.update');
    Route::delete('/my-team/{organization}/members/{member}', [UserOrganizationController::class, 'removeMember'])->name('organizations.members.destroy');
    Route::post('/my-team/{organization}/join-requests/{joinRequest}/approve', [UserOrganizationJoinRequestController::class, 'approve'])->name('organizations.join-requests.approve');
    Route::post('/my-team/{organization}/join-requests/{joinRequest}/reject', [UserOrganizationJoinRequestController::class, 'reject'])->name('organizations.join-requests.reject');

    // تبدیل تیم به شرکت
    Route::get('/my-team/{organization}/convert-to-company', [UserOrganizationController::class, 'showConvertForm'])->name('organizations.convert-to-company');
    Route::post('/my-team/{organization}/convert-to-company', [UserOrganizationController::class, 'convertToCompany'])->name('organizations.convert-to-company.store');

    // درخواست عضویت در تیم‌ها (توسط کاربر)
    Route::post('/organizations/{organization}/join', [PublicOrganizationJoinRequestController::class, 'store'])->name('public.organizations.join');

    // دانلود رزومه
    Route::get('/download/resume/{filename}', [FileController::class, 'downloadResume'])->name('download.resume');
});

// ==========================================
// ۳. مسیرهای پنل مدیریت (فقط ادمین‌ها)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', 'role:super_admin|site_admin|expert|pr_officer'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
        
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/innovator-requests', [AdminInnovatorRequestController::class, 'index'])->name('innovator-requests.index');
        Route::get('/innovator-requests/{innovatorRequest}', [AdminInnovatorRequestController::class, 'show'])->name('innovator-requests.show');
        Route::post('/innovator-requests/{innovatorRequest}/approve', [AdminInnovatorRequestController::class, 'approve'])->name('innovator-requests.approve');
        Route::post('/innovator-requests/{innovatorRequest}/reject', [AdminInnovatorRequestController::class, 'reject'])->name('innovator-requests.reject');
        
        Route::get('/plans', [AdminPlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/{plan}', [AdminPlanController::class, 'show'])->name('plans.show');
        Route::post('/plans/{plan}/approve', [AdminPlanController::class, 'approve'])->name('plans.approve');
        Route::post('/plans/{plan}/reject', [AdminPlanController::class, 'reject'])->name('plans.reject');
        
        // درخواست‌های تبدیل به شرکت
        Route::get('/company-requests', [CompanyRequestController::class, 'index'])->name('company-requests.index');
        Route::get('/company-requests/{organization}', [CompanyRequestController::class, 'show'])->name('company-requests.show');
        Route::post('/company-requests/{organization}/approve', [CompanyRequestController::class, 'approve'])->name('company-requests.approve');
        Route::post('/company-requests/{organization}/reject', [CompanyRequestController::class, 'reject'])->name('company-requests.reject');
        
        Route::get('/download/resume/{filename}', [FileController::class, 'downloadResume'])->name('download.resume');

        // مدیریت سازمان‌ها (تیم‌ها و شرکت‌ها)
        Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
        Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');

        // مدیریت دسته‌بندی اخبار
        Route::resource('news-categories', NewsCategoryController::class);

        // مدیریت اخبار
        Route::resource('news', NewsController::class);
    });
});