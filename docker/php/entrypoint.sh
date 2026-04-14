#!/bin/bash

# توقف در صورت بروز خطا
set -e

echo "[+] Starting Initialization Process..."

# ۱. نصب وابستگی‌های PHP با نادیده گرفتن پکیج‌های Dev و ناهماهنگی فایل لاک
echo "[+] Installing PHP dependencies via Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev


# ۲. پاکسازی node_modules قدیمی (برای جلوگیری از تداخل لینوکس/ویندوز)
echo "[+] Cleaning old node_modules..."
rm -rf node_modules package-lock.json

# ۳. نصب پکیج‌های NPM از نکسوس
echo "[+] Installing NPM dependencies..."
npm install

# ۴. بیلد کردن فرانت‌اِند (Tailwind/Vite)
echo "[+] Building frontend assets..."
npm run build

# ۵. اجرای سرویس اصلی
echo "[+] Starting PHP-FPM for Browser-Sec-Lab..."
exec php-fpm