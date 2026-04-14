<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>داشبورد کاربری | WebApp Victim</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: Tahoma, sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen">

    <nav class="bg-white shadow-sm border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <span class="bg-indigo-600 text-white px-3 py-1.5 rounded-md font-bold tracking-widest text-xs">VICTIM APP</span>
                    <span class="text-lg font-bold text-slate-800">پورتال کاربری</span>
                </div>
                <a href="{{ route('logout') }}" class="text-sm font-medium text-slate-500 hover:text-red-600 transition flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-lg border border-slate-200">
                    خروج از سیستم
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">سلام، {{ $user->name }} 👋</h1>
            <p class="mt-2 text-slate-600">اطلاعات هویتی شما در این پورتال امن نگهداری می‌شود.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-slate-900">اطلاعات حساب</h3>
                <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-full">احراز هویت شده</span>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-slate-50 p-5 rounded-xl border border-slate-200">
                        <span class="block text-xs font-bold text-slate-500 mb-2">نام کامل</span>
                        <span class="block text-lg text-slate-900 font-bold">{{ $user->name }}</span>
                    </div>

                    <!-- بخش حساس که هکر هدف می‌گیرد -->
                    <div class="bg-indigo-50 p-5 rounded-xl border border-indigo-200 relative overflow-hidden shadow-inner">
                        <div class="absolute top-0 right-0 w-2 h-full bg-indigo-500"></div>
                        <span class="block text-xs font-bold text-indigo-600 mb-2">آدرس ایمیل (هدف CSRF)</span>
                        <span class="block text-xl text-indigo-900 font-bold" dir="ltr">{{ $user->email }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Account Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-red-200 bg-red-50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-red-900">حذف حساب کاربری</h3>
            </div>
            <div class="p-6">
                <p class="text-red-700 mb-4">با حذف حساب کاربری، تمام اطلاعات شما به صورت دائمی پاک خواهد شد. این عمل غیرقابل بازگشت است.</p>
                <form action="{{ route('profile.destroy') }}" method="POST">
                    @csrf
                    <button type="submit" id="target-delete-btn" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 ease-in-out">
                        حذف حساب کاربری
                    </button>
                </form>
            </div>
        </div>
    </main>

</body>
</html>