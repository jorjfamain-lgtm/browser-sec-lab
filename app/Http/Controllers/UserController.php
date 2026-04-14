<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ==========================================
    // صفحه اصلی
    // ==========================================
    public function home()
    {
        return redirect()->route('login');
    }

    // ==========================================
    // راه‌اندازی آزمایشگاه
    // ==========================================
    public function setupLab()
    {
        User::firstOrCreate(
            ['email' => 'victim@webapp.kr-rezvan.ir'],
            [
                'name' => 'کاربر قربانی',
                'password' => Hash::make('12345678')
            ]
        );

        return "<div style='font-family:tahoma,serif; padding:20px; text-align:center;'>
                    <h2>آزمایشگاه آماده شد! 🧪</h2>
                    <p>کاربر هدف (victim@webapp.kr-rezvan.ir) با رمز عبور (12345678) در دیتابیس ساخته شد.</p>
                    <a href='" . route('login') . "' style='padding:10px 20px; background:blue; color:white; text-decoration:none; border-radius:5px;'>رفتن به صفحه لاگین</a>
                </div>";
    }

    // ==========================================
    // نمایش فرم ورود
    // ==========================================
    public function showLogin()
    {
        return view('user.login');
    }

    // ==========================================
    // پردازش ورود کاربر
    // ==========================================
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('user.profile');
        }

        return back()->withErrors(['message' => 'اطلاعات ورود صحیح نیست.']);
    }

    // ==========================================
    // خروج کاربر
    // ==========================================
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // ==========================================
    // نمایش پروفایل کاربری
    // ==========================================
    public function profile()
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    // ==========================================
    // متد آسیب‌پذیر تغییر ایمیل (CSRF Target)
    // ==========================================
    public function updateEmail(Request $request)
    {
        // بررسی اعتبار کوکی نشست (آیا SameSite اجازه ارسال کوکی را داده است؟)
        if (!Auth::check()) {
            return response()->json([
                'error' => 'شما لاگین نیستید. مرورگر کوکی نشست را بلاک کرده است! (محافظت SameSite عمل کرد)'
            ], 401);
        }

        $user = Auth::user();
        $oldEmail = $user->email;

        // اعمال تغییرات مخرب در دیتابیس
        $user->email = $request->input('email');
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'حمله موفق! ایمیل کاربر در دیتابیس تغییر یافت.',
            'old_email' => $oldEmail,
            'new_email' => $user->email
        ]);
    }
}