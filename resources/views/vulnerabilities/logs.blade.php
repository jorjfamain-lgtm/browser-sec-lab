<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hacker Panel - Stolen Data</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-black text-green-500 font-mono p-10">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-red-600 mb-6">[!] Compromised Data Logs</h1>

        <div class="bg-gray-900 p-6 rounded border border-green-700 shadow-2xl">
            <pre class="whitespace-pre-wrap">{{ $logs }}</pre>
        </div>

        <form action="{{ route('vulnerability.logs') }}" method="GET" class="mt-4">
            <button class="bg-green-700 text-black px-4 py-2 rounded hover:bg-green-600">Refresh Logs</button>
        </form>
    </div>
</body>
</html>