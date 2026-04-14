<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به حساب | WebApp Victim</title>
    <!-- لود کردن Tailwind CSS از طریق Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: Tahoma, sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border-t-4 border-indigo-500">

        <!-- لوگو و هدر -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 text-indigo-500 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-800">ورود به سیستم</h2>
            <p class="text-sm text-slate-500 mt-2">جهت دسترسی به پنل کاربری وارد شوید</p>
        </div>

        <!-- نمایش خطاها -->
        @if($errors->any())
            <div class="bg-red-50 border-r-4 border-red-500 text-red-700 p-4 rounded mb-6" role="alert">
                <p class="text-sm font-medium">{{ $errors->first() }}</p>
            </div>
        @endif

        <!-- فرم -->
        <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">آدرس ایمیل</label>
                <input type="email" name="email" value="victim@webapp.kr-rezvan.ir" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-left" dir="ltr">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">رمز عبور</label>
                <input type="password" name="password" value="12345678" required
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-left" dir="ltr">
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                ورود به داشبورد
            </button>
        </form>

        <!-- لینک راه‌اندازی -->
        <div class="mt-8 text-center text-sm text-slate-500 border-t pt-4">
            پایگاه داده هنوز مقداردهی نشده؟
            <a href="{{ route('setup.lab') }}" class="text-indigo-600 hover:text-indigo-800 font-medium transition">کلیک کنید</a>
        </div>

    </div>

</body>
</html>