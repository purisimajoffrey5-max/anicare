<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecurityMonitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------------------------------
        | Collect request information
        |--------------------------------------------------------------------------
        */

        $url = $request->fullUrl();
        $method = $request->method();
        $userAgent = $request->userAgent() ?? '';
        $ip = $request->ip();

        /*
        |--------------------------------------------------------------------------
        | Suspicious patterns
        |--------------------------------------------------------------------------
        */

        $patterns = [
            // Path traversal
            '/\.\.(\/|\\\\|%2f|%5c)/i',

            // SQL Injection
            '/union\s+select/i',
            '/select\s+.*\s+from/i',
            '/insert\s+into/i',
            '/update\s+.*set/i',
            '/delete\s+from/i',
            '/drop\s+table/i',
            '/sleep\s*\(/i',
            '/benchmark\s*\(/i',

            // XSS
            '/<script\b/i',
            '/javascript:/i',
            '/onerror\s*=/i',
            '/onload\s*=/i',
            '/<iframe\b/i',

            // PHP execution / web shell indicators
            '/base64_decode\s*\(/i',
            '/eval\s*\(/i',
            '/shell_exec\s*\(/i',
            '/passthru\s*\(/i',
            '/system\s*\(/i',
            '/exec\s*\(/i',

            // Sensitive files
            '/\/\.env/i',
            '/\.env\b/i',
            '/composer\.json/i',
            '/composer\.lock/i',
            '/phpinfo\.php/i',

            // Common scanner targets
            '/wp-admin/i',
            '/wp-login\.php/i',
            '/xmlrpc\.php/i',
            '/\.git\//i',
            '/server-status/i',

            // Linux sensitive files
            '/\/etc\/passwd/i',
            '/\/etc\/shadow/i',

            // Windows sensitive paths
            '/win\.ini/i',
            '/boot\.ini/i',
        ];

        /*
        |--------------------------------------------------------------------------
        | Build searchable request string
        |--------------------------------------------------------------------------
        */

        $body = '';

        try {
            $body = $request->getContent();
        } catch (\Throwable $e) {
            $body = '';
        }

        $searchable = implode(' ', [
            $url,
            $method,
            $userAgent,
            $body,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Detect suspicious request
        |--------------------------------------------------------------------------
        */

        $matchedPatterns = [];

        foreach ($patterns as $pattern) {
            if (@preg_match($pattern, $searchable)) {
                $matchedPatterns[] = $pattern;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Log suspicious request
        |--------------------------------------------------------------------------
        */

        if (!empty($matchedPatterns)) {

            Log::warning('SECURITY_MONITOR: Suspicious request detected.', [
                'ip' => $ip,
                'method' => $method,
                'url' => $url,
                'user_agent' => $userAgent,
                'matched_patterns' => count($matchedPatterns),
                'time' => now()->toDateTimeString(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Continue request
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}