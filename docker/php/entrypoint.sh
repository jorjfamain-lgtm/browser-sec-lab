#!/bin/bash

# توقف در صورت بروز خطای مهلک
set -e

echo "[+] Starting Initialization Process..."

# ۱. نصب وابستگی‌های PHP با نادیده گرفتن پکیج‌های Dev
echo "[+] Installing PHP dependencies via Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# ۲. پیکربندی محیط (Environment) لاراول
if [ ! -f .env ]; then
    echo "[+] .env file not found. Creating from .env.example..."
    cp .env.example .env
else
    echo "[+] .env file already exists."
fi

# ۳. تولید کلید امنیتی لاراول (App Key)
echo "[+] Generating App Key..."
php artisan key:generate --no-interaction

# ۴. پاکسازی node_modules قدیمی
echo "[+] Cleaning old node_modules..."
rm -rf node_modules package-lock.json

# ۵. نصب پکیج‌های NPM و بیلد فرانت‌اند
echo "[+] Installing NPM dependencies..."
npm config set registry ${NPM_REGISTRY}
npm install

echo "[+] Building frontend assets..."
npm run build

# ۶. آماده‌سازی پایگاه داده
echo "[+] Running database migrations..."
# از سوییچ force استفاده می‌کنیم تا در محیط غیرتعاملی ارور ندهد
php artisan migrate --force

# ۷. اجرای سرویس اصلی
echo "[+] Starting PHP-FPM for Browser-Sec-Lab..."
exec php-fpm