#!/bin/bash

# توقف اجرا در صورت بروز خطای بحرانی
set -e

echo "[+] Starting Initialization Process..."

# پاک کردن کش‌های قدیمی و تداخل‌های باینری ویندوز/لینوکس
echo "[+] Cleaning old node_modules to prevent native binding conflicts..."
rm -rf node_modules package-lock.json

# بررسی و نصب پکیج‌های NPM
echo "[+] Installing NPM dependencies (Node 20+)..."
npm install

# کامپایل کردن فایل‌های Tailwind و Alpine برای پروداکشن
echo "[+] Building frontend assets..."
npm run build

# راه‌اندازی سرویس اصلی (PHP-FPM)
echo "[+] Starting PHP-FPM for Browser-Sec-Lab..."
exec php-fpm