<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VulnerableHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ۱. کنترل Content-Security-Policy (CSP)
        if ($request->has('disable_csp')) {
            $response->headers->remove('Content-Security-Policy');
        } else {
            // آپدیت جدید: اجازه دادن به Tailwind CDN، استایل‌های Inline و عکس‌ها
            $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:;");
        }

        // ۲. کنترل X-Frame-Options
        if ($request->has('allow_framing')) {
            $response->headers->remove('X-Frame-Options');
        } else {
            $response->headers->set('X-Frame-Options', 'DENY');
        }

        // ۳. خاموش کردن محافظت پیش‌فرض XSS
        if ($request->has('disable_xss_protection')) {
            $response->headers->set('X-XSS-Protection', '0');
        }

        return $response;
    }
}