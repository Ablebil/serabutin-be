<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Auth\JwtService;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthMiddleware
{
    public function __construct(private readonly JwtService $jwtService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = $request->bearerToken();

        if (is_null($accessToken) || $accessToken === '') {
            throw new AuthenticationException(__('general.unauthenticated'));
        }

        try {
            $claims = $this->jwtService->decodeAccessTokenOrFail($accessToken);
        } catch (RuntimeException $exception) {
            throw new AuthenticationException(__('general.unauthenticated'));
        }

        $userId = (string) ($claims['sub'] ?? '');

        if ($userId === '') {
            throw new AuthenticationException(__('general.unauthenticated'));
        }

        $user = User::query()->find($userId);

        if (is_null($user) || !$user->is_active) {
            throw new AuthenticationException(__('general.unauthenticated'));
        }

        $request->attributes->set('auth_user', $user);
        $request->attributes->set('auth_claims', $claims);
        $request->setUserResolver(static fn (): User => $user);

        return $next($request);
    }
}
