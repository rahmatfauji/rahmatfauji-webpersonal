<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TrackVisitorActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $ipAddress = (string) $request->ip();
        $actionType = $this->resolveActionType($request);

        if ($actionType === null) {
            return $response;
        }

        ActivityLog::query()->create([
            'ip_address' => $ipAddress,
            'country' => $this->resolveCountry($ipAddress, $request),
            'method' => $request->method(),
            'action_type' => $actionType,
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'visited_at' => now(),
        ]);

        return $response;
    }

    private function resolveActionType(Request $request): ?string
    {
        $method = $request->method();

        if ($method === 'GET') {
            return $request->is('admin/*') ? null : 'page_visit';
        }

        if ($request->is('login') && $method === 'POST') {
            return 'user_login';
        }

        if ($request->is('logout') && $method === 'POST') {
            return 'user_logout';
        }

        if ($request->is('admin/blog-posts') && $method === 'POST') {
            return 'blog_create';
        }

        if ($request->is('admin/blog-posts/*') && $method === 'PUT') {
            return 'blog_update';
        }

        if ($request->is('admin/blog-posts/*') && $method === 'DELETE') {
            return 'blog_delete';
        }

        if ($request->is('admin/portfolio-items') && $method === 'POST') {
            return 'portfolio_create';
        }

        if ($request->is('admin/portfolio-items/*') && $method === 'PUT') {
            return 'portfolio_update';
        }

        if ($request->is('admin/portfolio-items/*') && $method === 'DELETE') {
            return 'portfolio_delete';
        }

        if ($request->is('admin/profiles') && $method === 'POST') {
            return 'profile_create';
        }

        if ($request->is('admin/profiles/*') && $method === 'PUT') {
            return 'profile_update';
        }

        if ($request->is('admin/slides') && $method === 'POST') {
            return 'slide_create';
        }

        if ($request->is('admin/slides/*') && $method === 'PUT') {
            return 'slide_update';
        }

        if ($request->is('admin/slides/*') && $method === 'DELETE') {
            return 'slide_delete';
        }

        return null;
    }

    private function resolveCountry(string $ipAddress, Request $request): string
    {
        $cloudflareCountry = $request->header('CF-IPCountry');
        if (is_string($cloudflareCountry) && $cloudflareCountry !== '' && strtoupper($cloudflareCountry) !== 'XX') {
            return strtoupper($cloudflareCountry);
        }

        if ($ipAddress === '' || $this->isPrivateOrReservedIp($ipAddress)) {
            return 'Local/Private Network';
        }

        return Cache::remember("ip-country:{$ipAddress}", now()->addHours(12), function () use ($ipAddress) {
            try {
                $response = Http::timeout(2)
                    ->acceptJson()
                    ->get("http://ip-api.com/json/{$ipAddress}?fields=status,country", []);

                if ($response->successful() && ($response->json('status') === 'success')) {
                    return (string) ($response->json('country') ?: 'Unknown');
                }
            } catch (\Throwable $exception) {
                // Keep logging resilient if geo lookup fails.
            }

            return 'Unknown';
        });
    }

    private function isPrivateOrReservedIp(string $ipAddress): bool
    {
        return filter_var(
            $ipAddress,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }
}
