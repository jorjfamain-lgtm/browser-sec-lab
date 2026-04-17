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

    // Drive-by Download Payload
    Route::get('/payload.exe', function () {
        $content = "This is a harmless dummy payload simulating malware for the Browser Security Lab.";
        return response($content, 200, [
            'Content-Type' => 'application/x-msdownload',
            'Content-Disposition' => 'attachment; filename="update_installer.exe"',
        ]);
    })->name('hacker.payload');

    // Drive-by Download Exploit Page
    Route::get('/drive-by', function () {
        return view('hacker.drive-by');
    })->name('hacker.driveby');

    // Clipboard Abuse Exploit Page
    Route::get('/clipboard', function () {
        return view('hacker.clipboard');
    })->name('hacker.clipboard');

    // Tabnabbing Exploit Pages
    Route::get('/tabnab', function () { 
        return view('hacker.tabnab'); 
    })->name('hacker.tabnab');
    Route::get('/fake-login', function () { 
        return view('hacker.fake-login'); 
    })->name('hacker.fake-login');
    
    // CORS Test Exploit Page
    Route::get('/cors-test', function () {
        return view('hacker.cors');
    })->name('hacker.cors');

    // WebRTC IP Leak Exploit Page
    Route::get('/webrtc-leak', function () {
        return view('hacker.webrtc');
    })->name('hacker.webrtc');

    // Canvas Fingerprinting Exploit Page
    Route::get('/fingerprint', function () {
        return view('hacker.fingerprint');
    })->name('hacker.fingerprint');
    
    // Local Network Scan Exploit Page
    Route::get('/local-scan', function () {
        return view('hacker.local-scan');
    })->name('hacker.local-scan');

    // Autofill Trap Exploit Page
    Route::get('/autofill-trap', function () {
        return view('hacker.autofill');
    })->name('hacker.autofill');

});

/*
|--------------------------------------------------------------------------
|  compromised subdomain (blog.webapp.kr-rezvan.ir)
|--------------------------------------------------------------------------
*/
Route::domain('blog.webapp.kr-rezvan.ir')
    ->withoutMiddleware([VulnerableHeadersMiddleware::class])
    ->group(function () {
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
Route::domain('webapp.kr-rezvan.ir')
    ->group(function () {

    Route::get('/', [UserController::class, 'home'])->name('home');
    Route::get('/setup-lab', [UserController::class, 'setupLab'])->name('setup.lab');

    // احراز هویت
    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.submit');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    // External Partners (Vulnerable Tabnabbing page)
    Route::get('/partners', function () { 
        return view('user.partners'); 
    })->name('user.partners');
    
    // Dummy API endpoint simulating sensitive user data (No CORS headers)
    Route::get('/internal/secret-data', function () {
        return response()->json([
            'user' => 'admin_rezvan',
            'balance' => '50,000 USD',
            'credit_card' => '****-****-****-1234'
        ]);
    })->name('user.api.secret');

    // Central Lab Directory Route
    Route::get('/labs', function () {
        return view('user.lab-directory');
    })->withoutMiddleware([VulnerableHeadersMiddleware::class])->name('user.labs');

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
