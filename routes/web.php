<?php

use App\Http\Controllers\VulnerabilityController;
use Illuminate\Support\Facades\Route;

// صفحه اصلی برای دسترسی سریع به بخش‌های مختلف
Route::get('/', function () {
    return view('welcome');
});

// ۱. روت تست XSS
// پارامتر payload را می‌گیرد و در صفحه نمایش می‌دهد
Route::get('/xss', [VulnerabilityController::class, 'xss'])->name('vulnerability.xss');

// ۲. روت هدف برای تست حمله CSRF
// این روت را در فایل bootstrap/app.php از بررسی توکن استثنا کردیم
Route::post('/csrf-target', [VulnerabilityController::class, 'csrfTarget'])->name('vulnerability.csrf');

// ۳. روت تست کوکی‌ها (برای مرحله بعدی)
Route::get('/set-cookie', function () {
    // ایجاد یک کوکی بدون HttpOnly برای تست سرقت با XSS
    return response('Cookie set!')
        ->cookie('secret_token', 'Bypass-12345', 60, null, null, false, false);
})->name('vulnerability.cookie');


// روت دریافت اطلاعات سرقت شده (معمولاً در دنیای واقعی روی سرور مهاجم است)
Route::get('/stealer', [VulnerabilityController::class, 'logStolenData'])->name('vulnerability.stealer');

// پنل مشاهده لاگ‌های سرقت شده
Route::get('/hacker-panel', [VulnerabilityController::class, 'viewLogs'])->name('vulnerability.logs');