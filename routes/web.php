<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VulnerabilityController;

/*
|--------------------------------------------------------------------------
| ☠️ دامنه مهاجم (Attacker App) : hackerapp.eitebar.ir
|--------------------------------------------------------------------------
*/
Route::domain('hackerapp.eitebar.ir')->group(function () {

    // داشبورد اصلی هکر (لاگ‌های سرقت شده)
    Route::get('/', [VulnerabilityController::class, 'viewLogs'])->name('hacker.home');
    Route::get('/hacker-panel', [VulnerabilityController::class, 'viewLogs'])->name('vulnerability.logs');
    Route::get('/stealer', [VulnerabilityController::class, 'logStolenData'])->name('vulnerability.stealer');

    // [جدید]: صفحه‌ای که هکر برای فریب کاربر می‌سازد (حاوی فرم مخفی CSRF)
    Route::get('/csrf-attack', function () {
        return view('hacker.csrf-exploit');
    })->name('hacker.csrf.exploit');

});

/*
|--------------------------------------------------------------------------
| 🛡️ دامنه قربانی (Victim App) : webapp.kr-rezvan.ir
|--------------------------------------------------------------------------
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

        // هدف حمله CSRF (اکنون کاملاً توسط میدل‌ویر CSRF محافظت می‌شود)
        Route::post('/update-email', [UserController::class, 'updateEmail'])->name('vulnerable.update.email');

        // آزمایشگاه XSS (کاربر لاگین شده این صفحه را می‌بیند)
        Route::get('/xss', [VulnerabilityController::class, 'xss'])->name('vulnerability.xss');

        // (فضای خالی برای حملات بعدی مثل Phishing، MITB و ...)
    });

});