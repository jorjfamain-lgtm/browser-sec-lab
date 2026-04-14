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
            // یک CSP سخت‌گیرانه به عنوان حالت پیش‌فرض
            $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline';");
        }

        // ۲. کنترل X-Frame-Options برای تست‌های Clickjacking / UI Redressing
        if ($request->has('allow_framing')) {
            $response->headers->remove('X-Frame-Options');
        } else {
            $response->headers->set('X-Frame-Options', 'DENY');
        }

        // ۳. خاموش کردن محافظت پیش‌فرض XSS (برای مرورگرهای قدیمی یا تست‌های خاص)
        if ($request->has('disable_xss_protection')) {
            $response->headers->set('X-XSS-Protection', '0');
        }

        return $response;
    }
}