<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $start) * 1000, 2);

        Log::info(sprintf(
            '%s | %d | %s | %s | %sms | %s',
            now()->format('Y-m-d H:i:s'),
            $response->getStatusCode(),
            $request->method(),
            $request->path(),
            $duration,
            $request->header('X-Request-ID', '-'),
        ));

        return $response;
    }
}