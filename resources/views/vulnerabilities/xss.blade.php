<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Lab - Browser Security</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans p-10">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-red-500 mb-6">Reflected XSS Lab</h1>

        <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 shadow-xl">
            <p class="mb-4">ورودی شما در زیر به صورت <strong>Raw</strong> رندر می‌شود:</p>

            <div class="p-4 bg-gray-700 rounded border border-yellow-500 text-yellow-200">
                {{-- استفاده از سینتکس خام برای اجرای اسکریپت --}}
                {!! $payload !!}
            </div>
        </div>

        <div class="mt-10 p-6 bg-blue-900/30 rounded-lg border border-blue-500">
            <h2 class="text-xl font-semibold mb-3">تست در مرورگر:</h2>
            <code class="block bg-black p-3 rounded text-green-400 break-all">
                /xss?payload=&lt;script&gt;alert('XSS by Browser-Sec-Lab')&lt;/script&gt;
            </code>
        </div>
    </div>
</body>
</html>