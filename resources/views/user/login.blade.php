<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ورود به سیستم | آزمایشگاه امنیت</title>
    <style>
        body { font-family: Tahoma, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-w-width: 400px; text-align: center; border-top: 4px solid #10b981;}
        input { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px;
            font-family: Tahoma, serif;}
        button { width: 95%; padding: 10px; background: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;}
        button:hover { background: #059669; }
        .error { color: red; font-size: 14px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>ورود به حساب کاربری</h2>

    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('login.submit') }}" method="POST">
        <!-- توکن CSRF برای فرم لاگین فعال است تا خود لاگین امن باشد -->
        @csrf

        <input type="email" name="email" value="victim@webapp.kr-rezvan.ir" placeholder="ایمیل" required>
        <input type="password" name="password" value="12345678" placeholder="رمز عبور" required>

        <button type="submit">ورود به پنل</button>
    </form>

    <p style="font-size: 12px; color: #666; margin-top: 20px;">برای تنظیم اولیه پایگاه داده <a href="{{ route('setup.lab') }}">اینجا کلیک کنید</a>.</p>
</div>

</body>
</html>