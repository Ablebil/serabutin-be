<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Cache;
use Throwable;

class EmailVerificationTokenService
{
    public function issue(User $user): string
    {
        $token = $this->generateToken();

        $this->cache()->put($this->cacheKey($token), (string) $user->id, $this->ttlSeconds());

        return $token;
    }

    public function consume(string $token): ?string
    {
        $key = $this->cacheKey($token);
        $cache = $this->cache();
        $userId = $cache->get($key);

        if (is_null($userId)) {
            return null;
        }

        $cache->forget($key);

        return (string) $userId;
    }

    public function buildVerificationUrl(string $token): string
    {
        $base = rtrim((string) config('app.frontend_url', config('app.url')), '/');
        $path = '/' . ltrim((string) config('auth.verification.frontend_path', '/verify-email'), '/');

        return $base . $path . '?token=' . urlencode($token);
    }

    private function ttlSeconds(): int
    {
        $ttl = (int) config('auth.verification.ttl_seconds', 3600);

        return $ttl > 0 ? $ttl : 3600;
    }

    private function cacheKey(string $token): string
    {
        return 'auth:verify-email:' . hash('sha256', $token);
    }

    private function cache(): CacheRepository
    {
        try {
            Cache::store()->get('auth:cache:healthcheck');

            return Cache::store();
        } catch (Throwable) {
            return Cache::store('file');
        }
    }

    private function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(48)), '+/', '-_'), '=');
    }
}
