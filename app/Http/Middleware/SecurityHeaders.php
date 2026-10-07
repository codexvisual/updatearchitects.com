<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Content-Security-Policy directives. Vite bundles are same-origin and the
     * app only uses first-party inline scripts/styles plus Google Fonts, so no
     * third-party script hosts need to be allowed.
     *
     * @var list<string>
     */
    private array $cspDirectives = [
        "default-src 'self'",
        "base-uri 'self'",
        "form-action 'self'",
        "frame-ancestors 'self'",
        "object-src 'none'",
        "script-src 'self' 'unsafe-inline'",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
        "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net",
        "img-src 'self' data: blob: https:",
        "connect-src 'self'",
        "media-src 'self'",
        "manifest-src 'self'",
        "frame-src 'self' https://www.google.com https://maps.google.com",
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=(), browsing-topics=()'
        );

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        if ($this->cspEnabled()) {
            $response->headers->set('Content-Security-Policy', implode('; ', $this->cspDirectives));
        }

        return $response;
    }

    /**
     * Vite's dev server loads modules from another origin, which the CSP would
     * block, so the policy is only sent once the app is out of debug mode.
     */
    private function cspEnabled(): bool
    {
        return ! config('app.debug') && config('app.env') !== 'local';
    }
}
