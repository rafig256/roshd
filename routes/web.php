    <?php

    use Illuminate\Support\Facades\Route;

    // --- کنترلرهای پنل مدیریت (Admin) ---
    use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
    use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
    use App\Http\Controllers\Admin\SettingController;
    use App\Http\Controllers\Admin\UserController;

    // --- کنترلرهای کاربران عادی (User) ---
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\Auth\LoginController as UserLoginController;
    use App\Http\Controllers\User\DashboardController as UserDashboardController;
    use App\Http\Controllers\User\InnovatorRequestController;

    // ==========================================
    // ۱. مسیرهای عمومی سایت (بدون نیاز به لاگین)
    // ==========================================
    Route::get('/', function () {
        return view('home'); // صفحه اصلی
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
        
        // مسیرهای درخواست فناوری
        Route::get('/innovator-request', [InnovatorRequestController::class, 'create'])->name('innovator-request.create');
        Route::post('/innovator-request', [InnovatorRequestController::class, 'store'])->name('innovator-request.store');
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
        });
    });