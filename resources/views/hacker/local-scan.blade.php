<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local Network Scanner Lab</title>
    <script src="https://tailwindcss.eitebar.ir/tailwind-play.js"></script>
    <style>
        .scan-line {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .status-scanning { color: #facc15; } /* yellow-400 */
        .status-open { color: #ef4444; font-weight: bold; } /* red-500 */
        .status-closed { color: #6b7280; } /* gray-500 */
    </style>
</head>
<body class="bg-black text-green-500 font-mono min-h-screen p-8 selection:bg-green-500 selection:text-black">

    <div class="max-w-4xl mx-auto">

        <header class="mb-8 border-b border-green-800 pb-4">
            <h1 class="text-3xl font-extrabold text-red-500">Local Network Port Scanner (Timing Attack)</h1>
            <p class="text-green-700 mt-2 text-sm">Abusing the browser to scan private network boundaries.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Terminal UI -->
            <div class="lg:col-span-2 bg-gray-900 rounded border border-green-900 shadow-2xl overflow-hidden relative">
                <div class="bg-gray-800 px-4 py-1 text-xs text-gray-400 flex justify-between items-center border-b border-green-900">
                    <span>root@c2-server:~# ./scan_local.sh</span>
                    <button id="start-btn" class="bg-green-700 hover:bg-green-600 text-black font-bold py-1 px-3 rounded text-xs">Start Scan</button>
                </div>

                <div class="p-4 h-96 overflow-y-auto" id="terminal-output">
                    <div class="text-gray-500 mb-4">
                        [*] Initializing timing attack engine...<br>
                        [*] Warning: Strict timeout set to 1500ms.<br>
                        [*] Click 'Start Scan' to begin.
                    </div>
                    <!-- Scan lines will be injected here -->
                </div>
            </div>

            <!-- Developer Notes -->
            <div class="bg-gray-900/50 rounded border border-gray-700 p-6 h-fit">
                <h3 class="text-white font-bold mb-3 border-b border-gray-700 pb-2">Developer Notes</h3>

                <div class="text-gray-400 text-xs space-y-3 leading-relaxed">
                    <p>
                        <strong class="text-green-500">How it works:</strong> Browsers restrict reading cross-origin data (CORS), but they typically still <em>allow the network request to be sent</em> (opaque responses). By measuring exactly how long a <code>fetch()</code> takes to fail, we can infer the port status.
                    </p>
                    <p>
                        <strong class="text-white">Active Rejection vs. Timeout:</strong> If a local port is open/active, it quickly rejects the cross-origin request (or returns an opaque response). If the IP doesn't exist or a firewall drops the packet silently, the request hangs until our manual 1500ms timeout kills it.
                    </p>
                    <div class="bg-red-900/20 border border-red-800/50 p-3 rounded mt-4">
                        <strong class="text-red-400 block mb-1">The Threat:</strong>
                        Attackers can map your home network (routers, IoT devices, local dev servers) simply by having you visit their public webpage. This is often step 1 before a local CSRF exploit against a vulnerable router.
                    </div>
                    <div class="bg-blue-900/20 border border-blue-800/50 p-3 rounded mt-2">
                        <strong class="text-blue-400 block mb-1">RBI / Zero Trust Protection:</strong>
                        Enterprise Browsers and RBI solutions enforce strict network isolation. They run in a cloud container and are explicitly blocked from routing traffic to private IP ranges (192.168.x.x, 10.x.x.x, localhost). The scan completely fails.
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Scanner Logic -->
    <script>
        const targets = [
            'http://localhost:80',       // Common Web Server
            'http://localhost:3306',     // MySQL
            'http://localhost:8000',     // Common Dev Server
            'http://localhost:8080',     // Common Dev Server
            'http://localhost:27017',    // MongoDB
            'http://192.168.1.1:80',     // Common Router
            'http://192.168.0.1:80',     // Common Router
            'http://10.0.0.1:80',        // Common Router
            'http://192.168.1.100:80'    // Common local IP
        ];

        const TIMEOUT_MS = 1500;
        const terminal = document.getElementById('terminal-output');
        const startBtn = document.getElementById('start-btn');

        function appendLine(targetId, url) {
            const line = document.createElement('div');
            line.className = 'scan-line';
            line.id = targetId;
            line.innerHTML = `<span>Scanning ${url}...</span> <span class="status-scanning">[ SCANNING ]</span>`;
            terminal.appendChild(line);
            terminal.scrollTop = terminal.scrollHeight;
        }

        function updateLine(targetId, url, isOpen, time) {
            const line = document.getElementById(targetId);
            if (isOpen) {
                line.innerHTML = `<span class="text-gray-300">${url}</span> <span class="status-open">🔴 OPEN/ACTIVE (${time}ms)</span>`;
            } else {
                line.innerHTML = `<span class="text-gray-600">${url}</span> <span class="status-closed">⚪ TIMEOUT/CLOSED (> ${TIMEOUT_MS}ms)</span>`;
            }
        }

        async function scanPort(url, targetId) {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), TIMEOUT_MS);
            const startTime = performance.now();

            try {
                // mode: 'no-cors' prevents CORS preflight OPTIONS, sending a simple GET
                await fetch(url, {
                    mode: 'no-cors',
                    signal: controller.signal,
                    cache: 'no-cache'
                });

                // If it resolves, the server responded (even if opaque)
                clearTimeout(timeoutId);
                const timeTaken = Math.round(performance.now() - startTime);
                updateLine(targetId, url, true, timeTaken);

            } catch (error) {
                clearTimeout(timeoutId);
                const timeTaken = Math.round(performance.now() - startTime);

                if (error.name === 'AbortError') {
                    // It hit our manual timeout -> port is silently dropping packets or IP is dead
                    updateLine(targetId, url, false, timeTaken);
                } else {
                    // It failed fast (e.g. ERR_CONNECTION_REFUSED) -> the OS actively rejected it, meaning host exists
                    // Or it was a CORS block after receiving headers. Either way, host/port is active.
                    updateLine(targetId, url, true, timeTaken);
                }
            }
        }

        startBtn.addEventListener('click', async () => {
            startBtn.disabled = true;
            startBtn.classList.add('opacity-50', 'cursor-not-allowed');
            terminal.innerHTML = '<div class="text-green-400 mb-2">[*] Starting scan sweep...</div>';

            for (let i = 0; i < targets.length; i++) {
                const targetId = `target-${i}`;
                appendLine(targetId, targets[i]);
                await scanPort(targets[i], targetId);
                // Slight delay between scans for UI effect
                await new Promise(r => setTimeout(r, 100));
            }

            const doneLine = document.createElement('div');
            doneLine.className = 'text-green-400 mt-4';
            doneLine.innerText = '[*] Scan sweep completed.';
            terminal.appendChild(doneLine);
            terminal.scrollTop = terminal.scrollHeight;

            startBtn.disabled = false;
            startBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    </script>

    <!-- Global Back Button -->
    <a href="http://webapp.kr-rezvan.ir/labs" style="position: fixed; bottom: 20px; right: 20px; background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 50px; font-family: sans-serif; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 9999; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
        🏠 Back to Lab Directory
    </a>

</body>
</html>