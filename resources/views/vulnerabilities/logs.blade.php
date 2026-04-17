<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Hacker Panel - C2 Server</title>
    <script src="https://tailwindcss.eitebar.ir/tailwind-play.js"></script>
</head>
<body class="bg-black text-green-500 font-mono min-h-screen p-8 selection:bg-green-500 selection:text-black">
    <div class="max-w-5xl mx-auto">

        <div class="border-b border-green-800 pb-4 mb-6 flex justify-between items-end">
            <div>
                <h1 class="text-4xl font-black text-red-600 tracking-tighter">[!] C2 SERVER LOGS</h1>
                <p class="text-green-700 mt-2 text-sm">Listening for incoming stolen sessions...</p>
            </div>
            <div class="text-xs text-green-800">
                STATUS: <span class="text-green-400 animate-pulse">ONLINE</span>
            </div>
        </div>

        <div class="bg-gray-950 p-6 rounded-lg border border-green-900 shadow-2xl relative">
            <!-- Decorative Terminal Header -->
            <div class="absolute top-0 left-0 w-full bg-green-900/20 px-4 py-1 text-xs text-green-600 flex gap-2">
                <span>root@kali:~# cat /var/log/stolen_cookies.log</span>
            </div>

            <div class="mt-6 overflow-x-auto">
                <pre class="whitespace-pre-wrap text-sm leading-relaxed">{{ $logs }}</pre>
            </div>

            @if($logs === 'هیچ دیتایی سرقت نشده است.')
                <div class="text-center py-10 opacity-50">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    NO DATA ACQUIRED YET
                </div>
            @endif
        </div>

        <form action="{{ route('vulnerability.logs') }}" method="GET" class="mt-6 flex gap-4">
            <button class="bg-green-600/20 border border-green-500 text-green-400 px-6 py-2 rounded hover:bg-green-600 hover:text-black transition-all font-bold uppercase tracking-widest text-sm">
                Refresh Logs
            </button>
            <a href="{{ route('home') }}" class="px-6 py-2 text-green-800 hover:text-green-500 transition uppercase tracking-widest text-sm flex items-center">
                Return to Target
            </a>
        </form>
    </div>
</body>
</html>