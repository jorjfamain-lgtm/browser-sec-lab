<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebRTC IP Leak Lab</title>
    <script src="https://tailwindcss.eitebar.ir/tailwind-play.js"></script>
    <style>
        .loader {
            border: 4px solid #374151; /* gray-700 */
            border-top: 4px solid #3b82f6; /* blue-500 */
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
            display: inline-block;
            vertical-align: middle;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-200 font-sans min-h-screen p-8">

    <div class="max-w-3xl mx-auto">
        <div class="bg-gray-800 rounded-xl shadow-2xl border border-gray-700 overflow-hidden mb-8">
            <div class="bg-blue-900/50 p-6 border-b border-blue-800/50 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-blue-400">WebRTC IP Leak Test</h1>
                    <p class="text-sm text-blue-200 mt-1">Zero-Click Information Disclosure</p>
                </div>
                <div id="status-indicator" class="flex items-center space-x-2">
                    <span class="text-sm font-semibold text-blue-300">Scanning</span>
                    <div class="loader" id="spinner"></div>
                </div>
            </div>

            <div class="p-6">
                <p class="text-gray-400 mb-6 leading-relaxed">
                    This page automatically executes a WebRTC connection request upon loading. WebRTC relies on STUN/TURN servers to establish peer-to-peer connections. During this process, the browser gathers all available network interfaces (ICE candidates) and exposes them to Javascript, often leaking the user's real public and local IP addresses—even behind proxies or split-tunnel VPNs.
                </p>

                <h2 class="text-lg font-semibold text-white mb-3 border-b border-gray-700 pb-2">Discovered IP Addresses:</h2>

                <div class="bg-black rounded-lg p-4 font-mono text-sm min-h-[120px] shadow-inner relative">
                     <ul id="ip-list" class="space-y-2 text-green-400 list-disc list-inside">
                        <!-- IPs will be injected here -->
                     </ul>
                     <div id="no-ip-msg" class="text-gray-600 italic absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden">
                         Waiting for candidates...
                     </div>
                </div>
            </div>
        </div>

        <!-- Developer Note -->
        <div class="bg-yellow-900/30 border-l-4 border-yellow-500 p-6 rounded-r-lg shadow-md">
            <h3 class="flex items-center text-yellow-400 font-bold text-lg mb-2">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Developer & Security Note
            </h3>
            <div class="text-gray-300 space-y-3 text-sm leading-relaxed">
                <p>
                    <strong>Local IP Obfuscation:</strong> Modern browsers (Chrome, Safari, Firefox) have implemented security measures to obfuscate local IP addresses using mDNS (Multicast DNS) by default (e.g., <code>xxx.local</code>). This prevents trivial local network scanning.
                </p>
                <p>
                    <strong>Public IP Leaks:</strong> However, public IP addresses often still leak, especially when users rely on proxy extensions or split-tunnel VPNs, because WebRTC operates outside the standard HTTP flow and communicates directly via UDP.
                </p>
                <p class="font-semibold text-white mt-4 border-t border-yellow-700/50 pt-3">
                    🛡️ Zero Trust & Remote Browser Isolation (RBI):
                </p>
                <p>
                    In an enterprise environment using a Zero Trust (RBI) browser, the WebRTC execution happens completely within an isolated cloud container. The STUN server only sees the cloud container's IP address, ensuring the real user's device IP is never exposed to the malicious website.
                </p>
            </div>
        </div>
    </div>

    <!-- WebRTC Leak Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ipListElement = document.getElementById('ip-list');
            const noIpMsg = document.getElementById('no-ip-msg');
            const spinner = document.getElementById('spinner');
            const statusIndicator = document.getElementById('status-indicator');
            const foundIPs = new Set();

            noIpMsg.classList.remove('hidden');

            const addIPToDOM = (ip) => {
                if (!foundIPs.has(ip)) {
                    foundIPs.add(ip);
                    noIpMsg.classList.add('hidden');

                    const li = document.createElement('li');

                    // Simple styling: highlight IPv4 vs IPv6 vs mDNS
                    if (ip.includes('.local')) {
                        li.innerHTML = `<span class="text-gray-400">mDNS (Obfuscated):</span> ${ip}`;
                    } else if (ip.includes(':')) {
                        li.innerHTML = `<span class="text-purple-400">IPv6:</span> ${ip}`;
                    } else {
                        li.innerHTML = `<span class="text-red-400 font-bold">IPv4:</span> ${ip}`;
                    }

                    ipListElement.appendChild(li);
                }
            };

            // Regex to extract IP addresses from SDP/ICE candidate string
            // Matches IPv4, IPv6, and .local mDNS names
            const ipRegex = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7}|[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}\.local)/gi;

            try {
                // Initialize RTCPeerConnection with a public Google STUN server
                const pc = new RTCPeerConnection({
                    iceServers: [{ urls: "stun:stun.l.google.com:19302" }]
                });

                // Create a dummy data channel to trigger ICE candidate gathering
                pc.createDataChannel("");

                // Create an offer and set it as local description
                pc.createOffer().then(offer => pc.setLocalDescription(offer)).catch(console.error);

                // Listen for ICE candidates
                pc.onicecandidate = (event) => {
                    if (event.candidate && event.candidate.candidate) {
                        const candidateString = event.candidate.candidate;
                        const match = candidateString.match(ipRegex);
                        if (match) {
                            match.forEach(addIPToDOM);
                        }
                    }
                };

                // Stop the spinner after a timeout (gathering is usually quick)
                setTimeout(() => {
                    spinner.style.display = 'none';
                    statusIndicator.innerHTML = '<span class="text-sm font-bold text-green-400">Scan Complete</span>';

                    if (foundIPs.size === 0) {
                         noIpMsg.textContent = "No IPs leaked (Browser blocked WebRTC or no network).";
                         noIpMsg.classList.remove('hidden');
                    }
                }, 4000); // 4 seconds timeout

            } catch (err) {
                console.error("WebRTC Error:", err);
                spinner.style.display = 'none';
                statusIndicator.innerHTML = '<span class="text-sm font-bold text-red-500">WebRTC Blocked/Failed</span>';
                noIpMsg.textContent = "Error: " + err.message;
            }
        });
    </script>

    <!-- Global Back Button -->
    <a href="http://webapp.kr-rezvan.ir/labs" style="position: fixed; bottom: 20px; right: 20px; background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 50px; font-family: sans-serif; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 9999; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
        🏠 Back to Lab Directory
    </a>

</body>
</html>