<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and attach essential security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Remove PHP version exposure if headers haven't been sent yet
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }

        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $next($request);

        // Remove X-Powered-By from Symfony response headers
        $response->headers->remove('X-Powered-By');

        // Prevent Clickjacking: deny framing from unauthorized domains
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME-sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // XSS Filter defense for legacy browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy: send full URL for same-origin, domain-only for cross-origin HTTPS
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restrict unnecessary browser features / APIs
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
