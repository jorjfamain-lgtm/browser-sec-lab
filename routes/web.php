<?php

use App\Http\Middleware\VulnerableHeadersMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VulnerabilityController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

/*
|--------------------------------------------------------------------------
| ☠️ دامنه مهاجم (Attacker App) : hackerapp.eitebar.ir
|--------------------------------------------------------------------------
| این بخش به عنوان سرور C2 (Command and Control) و میزبان پی‌لودها عمل می‌کند.
*/
Route::domain('hackerapp.eitebar.ir')->withoutMiddleware([VulnerableHeadersMiddleware::class])
    ->group(function () {

    // داشبورد اصلی هکر (لاگ‌های سرقت شده)
    Route::get('/', [VulnerabilityController::class, 'viewLogs'])->name('hacker.home');
    Route::get('/hacker-panel', [VulnerabilityController::class, 'viewLogs'])->name('vulnerability.logs');

    // روت مخفی هکر برای دریافت کوکی‌های سرقت شده (Stealer)
    Route::get('/stealer', [VulnerabilityController::class, 'logStolenData'])->name('vulnerability.stealer');

    // صفحه‌ای که فرم مخفی CSRF را به سمت قربانی شلیک می‌کند
    Route::get('/csrf-attack', function () {
        return view('hacker.csrf-exploit');
    })->name('hacker.csrf.exploit');

    // New Route: Clickjacking Exploit Page
    Route::get('/clickjacking', function () {
        return view('hacker.clickjacking');
    })->name('hacker.clickjacking');

});

/*
|--------------------------------------------------------------------------
|  compromised subdomain (blog.webapp.kr-rezvan.ir)
|--------------------------------------------------------------------------
*/
Route::domain('blog.webapp.kr-rezvan.ir')->group(function () {
    Route::get('/clickjacking', function () {
        return view('hacker.samesite-clickjacking');
    })->name('samesite.clickjacking');
});

/*
|--------------------------------------------------------------------------
| 🛡️ دامنه قربانی (Victim App) : webapp.kr-rezvan.ir
|--------------------------------------------------------------------------
| میزبان اطلاعات حساس کاربری که هدف حملات Cross-Origin قرار می‌گیرد.
*/
Route::domain('webapp.kr-rezvan.ir')->group(function () {

    Route::get('/', [UserController::class, 'home'])->name('home');
    Route::get('/setup-lab', [UserController::class, 'setupLab'])->name('setup.lab');

    // احراز هویت
    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.submit');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    // ----- بخش‌های محافظت شده با سشن -----
    Route::middleware(['web', 'auth'])->group(function () {

        // پروفایل کاربر
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        
        // حذف حساب کاربری (Clickjacking Target)
        Route::post('/profile/delete', [UserController::class, 'destroy'])->name('profile.destroy');

        // 🔓 هدف حمله CSRF (پروتکشن لایه کد کاملاً حذف شده است)
        Route::post('/update-email', [UserController::class, 'updateEmail'])
            ->withoutMiddleware([VerifyCsrfToken::class])
            ->name('vulnerable.update.email');

        // آزمایشگاه XSS (کاربر لاگین شده این صفحه را می‌بیند)
        Route::get('/xss', [VulnerabilityController::class, 'xss'])->name('vulnerability.xss');

        // (فضای خالی برای حملات بعدی مثل Phishing، MITB و ...)
    });

});
