#!/bin/bash

# توقف اجرا در صورت بروز خطای بحرانی
set -e

echo "[+] Starting Initialization Process..."

# بررسی و نصب پکیج‌های NPM
echo "[+] Installing NPM dependencies..."
npm install

# کامپایل کردن فایل‌های Tailwind و Alpine برای پروداکشن
echo "[+] Building frontend assets..."
npm run build

# راه‌اندازی سرویس اصلی (PHP-FPM)
echo "[+] Starting PHP-FPM for Browser-Sec-Lab..."
exec php-fpm