<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>آزمایشگاه XSS | Browser Security</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://tailwindcss.eitebar.ir/tailwind-play.js"></script>
    <style>body { font-family: Tahoma, sans-serif; }</style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen p-8">
    <div class="max-w-4xl mx-auto">

        <div class="flex items-center gap-4 mb-8 border-b border-red-500/30 pb-4">
            <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center text-red-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-red-500">آزمایشگاه Reflected XSS</h1>
                <p class="text-slate-400 mt-1">تزریق کد مخرب و دور زدن سیاست‌های امنیتی مرورگر (CSP)</p>
            </div>
        </div>

        <!-- فرم تزریق پی‌لود -->
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-2xl mb-8">
            <form action="{{ route('vulnerability.xss') }}" method="GET">
                <label for="payload" class="block text-sm font-semibold text-slate-300 mb-3">پی‌لود مخرب (Payload) خود را اینجا وارد کنید:</label>

                <div class="flex flex-col gap-5">
                    <textarea
                        name="payload"
                        id="payload"
                        rows="4"
                        class="w-full bg-slate-950 text-green-400 border border-slate-600 rounded-xl p-4 focus:ring-2 focus:ring-red-500 focus:border-red-500 font-mono text-left outline-none transition-all shadow-inner"
                        dir="ltr"
                        placeholder="<script>alert('Hacked!');</script>"
                    >{{ request('payload') ?? '' }}</textarea>

                    <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-slate-900/50 p-4 rounded-xl border border-slate-700/50">
                        <label class="flex items-center space-x-3 space-x-reverse cursor-pointer group">
                            <input type="checkbox" name="disable_csp" value="1" class="form-checkbox h-5 w-5 text-red-500 rounded border-slate-500 bg-slate-800 focus:ring-red-500 focus:ring-offset-slate-900" {{ request('disable_csp') ? 'checked' : '' }}>
                            <span class="text-sm text-slate-400 group-hover:text-slate-200 transition">غیرفعال کردن CSP (برای اجرای راحت‌تر اسکریپت روی مرورگرهای مدرن)</span>
                        </label>

                        <button type="submit" class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-8 rounded-lg transition duration-200 shadow-lg shadow-red-900/20">
                            اجرای حمله (Execute)
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- خروجی DOM -->
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-2 h-full bg-yellow-500"></div>
            <h2 class="text-xl font-bold mb-2 text-yellow-500 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                خروجی سرور (DOM Sink):
            </h2>
            <p class="mb-5 text-sm text-slate-400">مرورگر ورودی شما را در کادر زیر به عنوان بخشی از ساختار HTML رندر می‌کند:</p>

            <div class="p-5 bg-slate-900 rounded-xl border border-dashed border-yellow-500/50 text-yellow-100 min-h-[80px] text-left font-mono break-words" dir="ltr">
                {!! request('payload') ?? '<span class="text-slate-600">خروجی در اینجا نمایش داده می‌شود...</span>' !!}
            </div>
        </div>

    </div>
    <a href="https://webapp.kr-rezvan.ir/labs" style="position: fixed; bottom: 20px; right: 20px; background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 50px; font-family: sans-serif; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 9999; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
        🏠 Back to Lab Directory
    </a>
</body>
</html>