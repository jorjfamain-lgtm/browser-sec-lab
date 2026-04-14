<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VulnerabilityController;

/*
|--------------------------------------------------------------------------
| ☠️ دامنه مهاجم (Attacker App) : hackerapp.eitebar.ir
|--------------------------------------------------------------------------
*/
Route::domain('hackerapp.eitebar.ir')->group(function () {

    // داشبورد اصلی هکر برای مشاهده دیتای سرقت شده
    Route::get('/', [VulnerabilityController::class, 'viewLogs'])->name('hacker.home');
    Route::get('/hacker-panel', [VulnerabilityController::class, 'viewLogs'])->name('vulnerability.logs');

    // روت مخفی هکر برای دریافت کوکی‌های سرقت شده (Stealer / C2 Server)
    Route::get('/stealer', [VulnerabilityController::class, 'logStolenData'])->name('vulnerability.stealer');

});


/*
|--------------------------------------------------------------------------
| 🛡️ دامنه قربانی (Victim App) : webapp.kr-rezvan.ir
|--------------------------------------------------------------------------
*/
Route::domain('webapp.kr-rezvan.ir')->group(function () {

    Route::get('/', [UserController::class, 'home'])->name('home');
    Route::get('/setup-lab', [UserController::class, 'setupLab'])->name('setup.lab');

    // سیستم احراز هویت
    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.submit');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    // پنل کاربری (محافظت شده با نشست)
    Route::middleware(['web', 'auth'])->group(function () {
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    });

    // هدف حمله CSRF
    Route::post('/update-email', [UserController::class, 'updateEmail'])
        ->withoutMiddleware([VerifyCsrfToken::class])
        ->name('vulnerable.update.email');

    // صفحه آزمایشگاه XSS
    Route::get('/xss', [VulnerabilityController::class, 'xss'])->name('vulnerability.xss');

});