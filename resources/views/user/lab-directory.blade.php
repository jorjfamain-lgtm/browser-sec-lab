<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browser Security Lab Directory</title>
    <script src="https://tailwindcss.eitebar.ir/tailwind-play.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen p-8">
    <div class="max-w-7xl mx-auto">
        <header class="text-center mb-12">
            <h1 class="text-5xl font-extrabold text-blue-400 tracking-wider">Browser Security Lab</h1>
            <p class="text-gray-400 mt-4 text-lg">An interactive training ground to explore modern web vulnerabilities.</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- XSS Lab -->
            <div class="bg-gray-800 rounded-2xl border border-blue-500/30 shadow-lg hover:shadow-blue-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-blue-400">XSS Lab</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Test Reflected XSS and Content Security Policy (CSP) bypass techniques.</p>
                    <a href="{{ route('vulnerability.xss') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- CSR Attack -->
            <div class="bg-gray-800 rounded-2xl border border-blue-500/30 shadow-lg hover:shadow-blue-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-blue-400">CSRF Attack</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Demonstrate a classic Cross-Site Request Forgery attack using a hidden form.</p>
                    <a href="http://hackerapp.eitebar.ir/csrf-attack" target="_blank" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- Session/Cookie Stealer -->
            <div class="bg-gray-800 rounded-2xl border border-blue-500/30 shadow-lg hover:shadow-blue-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-blue-400">Session Stealer Logs</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">View the C2 server logs to see if any cookies or data have been exfiltrated.</p>
                    <a href="http://hackerapp.eitebar.ir/hacker-panel" target="_blank" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- MitB (Extension Simulation) -->
            <div class="bg-gray-800 rounded-2xl border border-yellow-500/30 shadow-lg hover:shadow-yellow-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-yellow-400">MitB Simulation</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Simulate a Man-in-the-Browser attack by injecting JS into the login page via DevTools.</p>
                    <a href="http://webapp.kr-rezvan.ir/login" target="_blank" class="block w-full text-center bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- Clickjacking -->
            <div class="bg-gray-800 rounded-2xl border border-yellow-500/30 shadow-lg hover:shadow-yellow-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-yellow-400">Clickjacking</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Bypass SameSite=Lax cookie protection using a compromised but same-site subdomain.</p>
                    <a href="http://blog.webapp.kr-rezvan.ir/clickjacking" target="_blank" class="block w-full text-center bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- Drive-by Download -->
            <div class="bg-gray-800 rounded-2xl border border-red-500/30 shadow-lg hover:shadow-red-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-red-400">Drive-by Download</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Test how browsers handle automatic downloads initiated from a cross-origin iframe.</p>
                    <a href="http://hackerapp.eitebar.ir/drive-by" target="_blank" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- Clipboard Data Leak -->
            <div class="bg-gray-800 rounded-2xl border border-red-500/30 shadow-lg hover:shadow-red-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-red-400">Clipboard Data Leak</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Explore browser permissions and user gestures required for clipboard access.</p>
                    <a href="http://hackerapp.eitebar.ir/clipboard" target="_blank" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- Reverse Tabnabbing -->
            <div class="bg-gray-800 rounded-2xl border border-red-500/30 shadow-lg hover:shadow-red-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-red-400">Reverse Tabnabbing</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Exploit `window.opener` to perform a phishing attack on the previous tab.</p>
                    <a href="{{ route('user.partners') }}" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- CORS Opaque Responses -->
            <div class="bg-gray-800 rounded-2xl border border-red-500/30 shadow-lg hover:shadow-red-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-red-400">CORS & Opaque Responses</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Test how browsers block cross-origin data reads and handle 'no-cors' requests.</p>
                    <a href="http://hackerapp.eitebar.ir/cors-test" target="_blank" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- WebRTC IP Leak -->
            <div class="bg-gray-800 rounded-2xl border border-red-500/30 shadow-lg hover:shadow-red-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-red-400">WebRTC IP Leak</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Discover real IP addresses bypassing standard proxies via WebRTC STUN negotiation.</p>
                    <a href="http://hackerapp.eitebar.ir/webrtc-leak" target="_blank" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- Canvas Fingerprinting -->
            <div class="bg-gray-800 rounded-2xl border border-red-500/30 shadow-lg hover:shadow-red-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-red-400">Canvas Fingerprinting</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Demonstrate how browsers leak unique hardware and rendering signatures for tracking.</p>
                    <a href="http://hackerapp.eitebar.ir/fingerprint" target="_blank" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- Local Network Scan -->
            <div class="bg-gray-800 rounded-2xl border border-red-500/30 shadow-lg hover:shadow-red-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-red-400">Local Network Scan</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Abuse the browser to scan private network boundaries via timing attacks.</p>
                    <a href="http://hackerapp.eitebar.ir/local-scan" target="_blank" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- Autofill Trap -->
            <div class="bg-gray-800 rounded-2xl border border-red-500/30 shadow-lg hover:shadow-red-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-red-400">Autofill Trap</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Demonstrate how a malicious site steals sensitive user data by hiding input fields populated by Autofill.</p>
                    <a href="http://hackerapp.eitebar.ir/autofill-trap" target="_blank" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>

            <!-- XS-Leaks -->
            <div class="bg-gray-800 rounded-2xl border border-red-500/30 shadow-lg hover:shadow-red-500/20 transition-shadow duration-300">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-red-400">XS-Leaks</h2>
                    <p class="text-gray-400 mt-2 mb-4 h-16">Infer private cross-origin data by measuring network response times.</p>
                    <a href="http://hackerapp.eitebar.ir/xs-leak" target="_blank" class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Launch Lab</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>