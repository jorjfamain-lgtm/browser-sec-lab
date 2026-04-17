<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CORS Exploitation Lab</title>
    <script src="https://tailwindcss.eitebar.ir/tailwind-play.js"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-red-500 mb-6 border-b border-red-500/30 pb-4">
            Cross-Origin Resource Sharing (CORS) Lab
        </h1>

        <p class="text-gray-400 mb-8">
            Attempting to read data from <code class="bg-gray-800 px-2 py-1 rounded text-green-400">https://webapp.kr-rezvan.ir/api/secret-data</code>
            which does NOT have CORS headers allowing this domain.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Test 1: Standard Fetch -->
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 hover:border-gray-500 transition">
                <h2 class="text-xl font-bold text-blue-400 mb-2">Test 1: Standard Fetch</h2>
                <p class="text-sm text-gray-400 mb-4">Standard cross-origin request. Browser will enforce Same-Origin Policy.</p>
                <button id="btn-standard" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                    Run Standard Fetch
                </button>
            </div>

            <!-- Test 2: No-CORS Fetch -->
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-700 hover:border-gray-500 transition">
                <h2 class="text-xl font-bold text-yellow-400 mb-2">Test 2: No-CORS Mode</h2>
                <p class="text-sm text-gray-400 mb-4">Forces request using <code class="text-xs bg-gray-900 p-1">mode: 'no-cors'</code>. Network request happens, but JS cannot see data.</p>
                <button id="btn-nocors" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mb-4">
                    Run No-CORS Fetch
                </button>
            </div>
        </div>

        <!-- Output Console -->
        <div class="bg-black rounded-lg border border-gray-700 shadow-2xl overflow-hidden">
            <div class="bg-gray-800 px-4 py-2 border-b border-gray-700 flex justify-between items-center">
                <span class="text-sm font-mono text-gray-400">Browser Console Output</span>
                <button onclick="document.getElementById('console-output').innerHTML=''" class="text-xs text-gray-500 hover:text-white">Clear</button>
            </div>
            <pre id="console-output" class="p-4 font-mono text-sm h-64 overflow-y-auto text-green-400 whitespace-pre-wrap"></pre>
        </div>
    </div>

    <script>
        const log = (msg, type = 'info') => {
            const out = document.getElementById('console-output');
            let color = 'text-green-400';
            if (type === 'error') color = 'text-red-500';
            if (type === 'warn') color = 'text-yellow-500';

            const time = new Date().toLocaleTimeString();
            out.innerHTML += `<span class="text-gray-600">[${time}]</span> <span class="${color}">${msg}</span>\n`;
            out.scrollTop = out.scrollHeight;
        };

        const targetUrl = 'https://webapp.kr-rezvan.ir/internal/secret-data';

        document.getElementById('btn-standard').addEventListener('click', async () => {
            log('Starting Standard Fetch...', 'info');
            try {
                const response = await fetch(targetUrl);
                const data = await response.json();
                log(`SUCCESS: Data stolen: ${JSON.stringify(data)}`, 'warn');
            } catch (error) {
                log(`CORS ERROR: Browser blocked the read!`, 'error');
                log(`Details: ${error.message}`, 'error');
                log('Note: Open DevTools (F12) Console to see the actual CORS policy error from the browser.', 'info');
            }
        });

        document.getElementById('btn-nocors').addEventListener('click', async () => {
            log(`Starting Fetch with { mode: 'no-cors' }...`, 'info');
            try {
                const response = await fetch(targetUrl, { mode: 'no-cors' });
                log(`Network request succeeded, but response is OPAQUE.`, 'warn');
                log(`Response Type: ${response.type}`, 'warn');
                log(`Response Status: ${response.status}`, 'warn');
                log(`Response StatusText: "${response.statusText}"`, 'warn');

                // Attempt to read body
                try {
                    const text = await response.text();
                    if(text === "") {
                         log('Response body is completely EMPTY to JavaScript.', 'warn');
                    } else {
                         log(`Body: ${text}`, 'warn');
                    }
                } catch(e) {
                     log(`Could not read body: ${e.message}`, 'error');
                }

                log('Conclusion: Network request hit the server, but browser hid the response from JS.', 'info');
            } catch (error) {
                log(`Unexpected Error: ${error.message}`, 'error');
            }
        });
    </script>
    <a href="https://webapp.kr-rezvan.ir/labs" style="position: fixed; bottom: 20px; right: 20px; background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 50px; font-family: sans-serif; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 9999; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
        🏠 Back to Lab Directory
    </a>
</body>
</html>