<?php

namespace App\Http\Middleware;

use App\Services\VisitorTrackingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function __construct(
        protected VisitorTrackingService $tracker
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking on crawler/bot user-agents, non-GET public routes, or asset paths
        if ($this->shouldSkipTracking($request)) {
            return $next($request);
        }

        try {
            $visitor = $this->tracker->resolveVisitor($request);
            $session = $this->tracker->resolveSession($visitor, $request);

            // Make visitor and session accessible throughout the request
            $request->attributes->set('visitor', $visitor);
            $request->attributes->set('visitor_session', $session);
        } catch (\Throwable $e) {
            // Silently fail to ensure page rendering is never blocked by tracking errors
        }

        $response = $next($request);

        // Ensure the visitor cookie is attached to the outgoing response if queued
        return $response;
    }

    /**
     * Check if request should bypass visitor tracking.
     */
    protected function shouldSkipTracking(Request $request): bool
    {
        // Don't track API requests, admin backoffice assets, or debug routes
        if ($request->is('api/*', 'admin/*', 'up', 'csrf-token', 'livewire/*', '_debugbar/*')) {
            return true;
        }

        $ua = strtolower($request->header('User-Agent', ''));
        $crawlers = ['googlebot', 'bingbot', 'yandex', 'duckduckbot', 'baiduspider', 'twitterbot', 'facebookexternalhit', 'whatsapp', 'slurp'];

        foreach ($crawlers as $crawler) {
            if (str_contains($ua, $crawler)) {
                return true;
            }
        }

        return false;
    }
}
