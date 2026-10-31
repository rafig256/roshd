<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ورود به پنل مدیریت | مرکز نوآوری شهری</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Vazirmatn', 'Tahoma', 'sans-serif'],
                    },
                },
            },
        };
    </script>
</head>
<body class="font-sans antialiased text-slate-800">

<div class="min-h-screen flex items-center justify-center px-4 py-10
            bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900">

    <div class="w-full max-w-md">

        {{-- Logo / Title --}}
        <div class="text-center mb-8">
            <div class="mx-auto w-16 h-16 rounded-2xl bg-indigo-500 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-900/40">
                لوگو
            </div>
            <h1 class="mt-4 text-xl font-bold text-white">مرکز نوآوری شهری</h1>
            <p class="mt-1 text-sm text-slate-300">ورود به پنل مدیریت</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">

            <form method="POST" action="#" class="space-y-5">
                @csrf

                {{-- National code --}}
                <div>
                    <label for="national_code" class="block mb-2 text-sm font-medium text-slate-700">
                        کد ملی
                    </label>
                    <input type="text"
                           id="national_code"
                           name="national_code"
                           value="{{ old('national_code') }}"
                           inputmode="numeric"
                           maxlength="10"
                           autocomplete="username"
                           placeholder="کد ملی ۱۰ رقمی"
                           dir="ltr"
                           class="w-full px-4 py-2.5 text-sm text-left rounded-lg border border-slate-300 bg-slate-50
                                  placeholder:text-right placeholder:text-slate-400
                                  focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                  transition">
                    @error('national_code')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-slate-700">
                        رمز عبور
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           autocomplete="current-password"
                           placeholder="رمز عبور خود را وارد کنید"
                           dir="ltr"
                           class="w-full px-4 py-2.5 text-sm text-left rounded-lg border border-slate-300 bg-slate-50
                                  placeholder:text-right placeholder:text-slate-400
                                  focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                  transition">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember"
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        مرا به خاطر بسپار
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg
                               hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                               transition-colors">
                    ورود
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">
            © مرکز نوآوری شهری — تمامی حقوق محفوظ است.
        </p>
    </div>
</div>

</body>
</html>