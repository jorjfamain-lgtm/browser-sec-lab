<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Lab - Browser Security</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans p-10">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-red-500 mb-6 border-b border-red-500 pb-2">آزمایشگاه Reflected XSS</h1>

        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-xl mb-6">
            <form action="{{ route('vulnerability.xss') }}" method="GET">
                <label for="payload" class="block text-sm font-medium text-gray-300 mb-2">پی‌لود مخرب (Payload) خود را اینجا وارد کنید:</label>

                <div class="flex flex-col gap-4">
                    <textarea
                        name="payload"
                        id="payload"
                        rows="3"
                        class="w-full bg-gray-900 text-green-400 border border-gray-600 rounded-md p-3 focus:ring-red-500 focus:border-red-500 font-mono text-left"
                        dir="ltr"
                        placeholder="<script>alert(1)</script>"
                    >{{ $payload ?? '' }}</textarea>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2 space-x-reverse cursor-pointer">
                            <input type="checkbox" name="disable_csp" value="1" class="form-checkbox h-5 w-5 text-red-600 rounded bg-gray-700 border-gray-500" {{ request('disable_csp') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-400">غیرفعال کردن Content-Security-Policy (برای اجرای راحت‌تر اسکریپت)</span>
                        </label>

                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                            اجرای حمله (Execute)
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-xl">
            <h2 class="text-xl font-semibold mb-3 text-yellow-400">خروجی سرور (DOM Sink):</h2>
            <p class="mb-4 text-sm text-gray-400">ورودی شما بدون فیلتر در کادر زیر رندر شده است:</p>

            <div class="p-4 bg-gray-700 rounded border border-dashed border-yellow-500 text-yellow-200 min-h-[50px] text-left" dir="ltr">
                {{-- آسیب‌پذیری ساختاریافته Blade --}}
                {!! $payload ?? 'خروجی در اینجا نمایش داده می‌شود...' !!}
            </div>
        </div>
    </div>
</body>
</html>