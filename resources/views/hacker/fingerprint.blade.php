<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canvas Fingerprinting Lab</title>
    <script src="https://tailwindcss.eitebar.ir/tailwind-play.js"></script>
    <style>
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: .7; }
        }
        .animate-pulse-slow {
            animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-gray-950 text-gray-200 font-sans min-h-screen p-8 selection:bg-purple-500 selection:text-white">

    <div class="max-w-4xl mx-auto">

        <header class="mb-10 text-center">
            <h1 class="text-4xl font-extrabold text-purple-500 mb-2">Canvas Fingerprinting</h1>
            <p class="text-gray-400">Tracking users without cookies or IP addresses via GPU rendering anomalies.</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Left Column: The Exploit -->
            <div class="bg-gray-900 rounded-xl border border-gray-800 shadow-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2 h-full bg-purple-600"></div>

                <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Generated Hardware ID
                </h2>

                <div class="bg-black/50 p-4 rounded-lg border border-purple-900/30 text-center mb-6">
                    <span class="block text-xs text-gray-500 uppercase tracking-widest mb-1">Your Unique Signature</span>
                    <span id="fingerprint-id" class="text-2xl font-mono font-bold text-purple-400 break-all animate-pulse-slow">Calculating...</span>
                </div>

                <h3 class="text-sm font-semibold text-gray-400 mb-2 uppercase tracking-wide">Hidden Rendering Canvas:</h3>
                <div class="bg-gray-800 p-2 rounded border border-gray-700 inline-block">
                    <!-- The actual canvas used for fingerprinting. Usually hidden (display:none), but shown here for educational purposes. -->
                    <canvas id="fp-canvas" width="250" height="60" class="bg-white rounded-sm"></canvas>
                </div>
                <p class="text-xs text-gray-500 mt-2 italic">
                    * The canvas forces your OS and GPU to render anti-aliased text. Slight micro-pixel variations create the unique hash above.
                </p>
            </div>

            <!-- Right Column: Developer Notes -->
            <div class="bg-blue-900/20 rounded-xl border border-blue-800/30 p-6">
                <h3 class="flex items-center text-blue-400 font-bold text-lg mb-4 border-b border-blue-800/50 pb-2">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Security Researcher Notes
                </h3>

                <div class="text-gray-300 space-y-4 text-sm leading-relaxed">
                    <p>
                        <strong class="text-white">Why this works:</strong> Every combination of Operating System, GPU, Graphics Driver, and Font installation renders complex text slightly differently at the sub-pixel level.
                    </p>
                    <p>
                        <strong class="text-red-400">The Threat:</strong> Even if a user clears their cookies, uses a VPN to hide their IP, or browses in "Incognito/Private" mode, their Hardware Fingerprint ID will likely remain <em>exactly the same</em>. This allows ad networks (and attackers) to track users persistently across the web.
                    </p>
                    <div class="bg-blue-950/50 p-4 rounded-lg border border-blue-800/50 mt-4">
                        <p class="font-bold text-blue-300 mb-1">🛡️ Mitigation via Zero Trust (RBI):</p>
                        <p>
                            Zero Trust and Remote Browser Isolation (RBI) solutions defeat canvas fingerprinting. Because the website is rendered inside a disposable cloud container (e.g., a Linux Docker container), the script fingerprints the <em>cloud server's</em> virtual GPU and OS, not the actual user's device. Consequently, thousands of users connecting through the RBI will share the exact same generic cloud fingerprint, providing "security through anonymity."
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Canvas Fingerprinting Script -->
    <script>
        // Simple SHA-256 hashing function using Web Crypto API
        async function sha256(message) {
            const msgBuffer = new TextEncoder().encode(message);
            const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer);
            const hashArray = Array.from(new Uint8Array(hashBuffer));
            const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
            return hashHex;
        }

        async function generateCanvasFingerprint() {
            const canvas = document.getElementById('fp-canvas');
            const ctx = canvas.getContext('2d');

            // 1. Draw a background
            ctx.fillStyle = "rgb(255,0,255)";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // 2. Draw text with weird baselines to force anti-aliasing anomalies
            ctx.textBaseline = "top";
            ctx.font = "14px 'Arial'";
            ctx.textBaseline = "alphabetic";
            ctx.fillStyle = "#f60";
            ctx.fillRect(125, 1, 62, 20);

            // 3. Draw overlapping, multi-colored text
            ctx.fillStyle = "#069";
            ctx.fillText("BrowserSec Lab, \ud83d\ude03", 2, 15);
            ctx.fillStyle = "rgba(102, 204, 0, 0.7)";
            ctx.fillText("BrowserSec Lab, \ud83d\ude03", 4, 17);

            // 4. Extract the base64 data URI of the resulting image
            const dataURI = canvas.toDataURL();

            // 5. Hash the massive base64 string to get a clean, short ID
            const hash = await sha256(dataURI);

            // Display the first 16 chars of the hash as the "Hardware ID"
            document.getElementById('fingerprint-id').innerText = hash.substring(0, 16).toUpperCase();
        }

        // Execute on load
        window.addEventListener('load', generateCanvasFingerprint);
    </script>

    <!-- Global Back Button -->
    <a href="http://webapp.kr-rezvan.ir/labs" style="position: fixed; bottom: 20px; right: 20px; background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 50px; font-family: sans-serif; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 9999; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
        🏠 Back to Lab Directory
    </a>

</body>
</html>