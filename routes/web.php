<?php

use App\Http\Controllers\VulnerabilityController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\UserController;

// ==========================================
// صفحه اصلی
// ==========================================
Route::get('/', [UserController::class, 'home'])->name('home');


// ==========================================
// ۱. راه‌اندازی سریع آزمایشگاه (مرحله اول)
// ==========================================
Route::get('/setup-lab', [UserController::class, 'setupLab'])->name('setup.lab');


// ==========================================
// ۲. سیستم احراز هویت (Authentication)
// ==========================================
Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.submit');
// نکته امنیتی: خروج در حالت استاندارد باید POST باشد اما برای راحتی تست در لابراتوار GET قرار دادیم
Route::get('/logout', [UserController::class, 'logout'])->name('logout');


// ==========================================
// ۳. پنل کاربری (محافظت شده با نشست)
// ==========================================
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
});


// ==========================================
// ۴. هدف حمله CSRF (آسیب‌پذیر)
// ==========================================
Route::post('/update-email', [UserController::class, 'updateEmail'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('vulnerable.update.email');


// ==========================================
// ۵. آزمایشگاه XSS و سرقت کوکی (Hacker Panel)
// ==========================================
// صفحه اصلی آزمایشگاه XSS
Route::get('/xss', [VulnerabilityController::class, 'xss'])->name('vulnerability.xss');

// روت مخفی هکر برای دریافت کوکی‌های سرقت شده (Stealer)
Route::get('/stealer', [VulnerabilityController::class, 'logStolenData'])->name('vulnerability.stealer');

// داشبورد هکر برای مشاهده دیتای سرقت شده
Route::get('/hacker-panel', [VulnerabilityController::class, 'viewLogs'])->name('vulnerability.logs');