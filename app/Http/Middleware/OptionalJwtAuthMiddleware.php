<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Auth\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OptionalJwtAuthMiddleware
{
    public function __construct(private readonly JwtService $jwtService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = $request->bearerToken();

        if (!is_null($accessToken) && $accessToken !== '') {
            try {
                $claims = $this->jwtService->decodeAccessTokenOrFail($accessToken);
                $userId = (string) ($claims['sub'] ?? '');

                if ($userId !== '') {
                    $user = User::query()->find($userId);

                    if (!is_null($user) && $user->is_active) {
                        $request->attributes->set('auth_user', $user);
                        $request->attributes->set('auth_claims', $claims);
                        $request->setUserResolver(static fn(): User => $user);
                    }
                }
            } catch (Throwable) {
            }
        }

        return $next($request);
    }
}
