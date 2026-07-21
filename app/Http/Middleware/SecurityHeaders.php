<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Clickjacking Protection
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // MIME Sniffing Protection
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Legacy XSS Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Content Security Policy (CSP)
        // Dynamically allows local Vite dev server ports (5173) when in local environment.
        $csp = "default-src 'self'; ";
        $scriptSrc = "script-src 'self' 'unsafe-inline' 'unsafe-eval'";
        $styleSrc = "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com";
        $connectSrc = "connect-src 'self' ws: wss:";
        $imgSrc = "img-src 'self' data:";
        $fontSrc = "font-src 'self' https://fonts.gstatic.com";

        if (app()->environment('local')) {
            $scriptSrc .= " http://localhost:5173 http://127.0.0.1:5173";
            $styleSrc .= " http://localhost:5173 http://127.0.0.1:5173";
            $connectSrc .= " http://localhost:5173 http://127.0.0.1:5173 ws://localhost:5173 ws://127.0.0.1:5173";
            $imgSrc .= " http://localhost:5173 http://127.0.0.1:5173";
        }

        $csp .= "{$scriptSrc}; {$styleSrc}; {$connectSrc}; {$imgSrc}; {$fontSrc}; object-src 'none'; frame-ancestors 'self';";
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
