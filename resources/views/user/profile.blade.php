<div style="font-family: tahoma,serif; padding: 20px; border: 1px solid #ccc;">
    <h2>پنل کاربری (سایت قربانی)</h2>
    <p>سلام <b>{{ $user->name }}</b> خوش آمدید.</p>
    <p>ایمیل فعلی شما: <span style="color: blue;">{{ $user->email }}</span></p>
    <hr>
    <a href="{{ route('logout') }}">خروج از سیستم</a>
</div>