<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// ۱. مرحله اول: مقداردهی اولیه پایگاه داده (ساخت کاربر قربانی)
Route::get('/setup-lab', function () {
    $user = User::firstOrCreate(
        ['email' => 'victim@webapp.kr-rezvan.ir'],
        [
            'name' => 'کاربر قربانی',
            'password' => Hash::make('12345678')
        ]
    );
    return "آزمایشگاه آماده شد! کاربر هدف (victim@webapp.kr-rezvan.ir) در دیتابیس ساخته شد.";
});

// ۲. شبیه‌سازی ورود کاربر به سیستم
Route::get('/login-victim', function () {
    if (Auth::attempt(['email' => 'victim@webapp.kr-rezvan.ir', 'password' => '12345678'])) {
        return "هویت شما تایید شد. کوکی نشست صادر و در دیتابیس ثبت گردید.";
    }
    return "خطا در ورود.";
});

// ۳. بررسی وضعیت پروفایل (برای بررسی موفقیت‌آمیز بودن حمله)
Route::get('/profile', function () {
    if (Auth::check()) {
        return "شما لاگین هستید. ایمیل فعلی شما: <b>" . Auth::user()->email . "</b>";
    }
    return "شما لاگین نیستید! (احتمالاً کوکی به درستی ارسال نشده است).";
});

// ۴. نقطه آسیب‌پذیر (هدف حمله CSRF)
Route::post('/update-email', function (Request $request) {
    // اگر کوکی معتبر ارسال نشود، لاراول این کاربر را ناشناس می‌داند
    if (!Auth::check()) {
        return response()->json(['error' => 'دسترسی غیرمجاز. مرورگر کوکی نشست را ارسال نکرد.'], 401);
    }

    $user = Auth::user();
    $oldEmail = $user->email;

    // تغییر در دیتابیس واقعی
    $user->email = $request->input('email');
    $user->save();

    return response()->json([
        'status' => 'success',
        'message' => 'ایمیل در دیتابیس تغییر یافت!',
        'old' => $oldEmail,
        'new' => $user->email,
        'session_id' => session()->getId()
    ]);
})->withoutMiddleware([VerifyCsrfToken::class]);