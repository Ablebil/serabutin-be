<?php

namespace App\Services\Auth;

use App\Models\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Support\Str;
use RuntimeException;
use UnexpectedValueException;

class JwtService
{
    public function issueAccessToken(User $user): array
    {
        $ttl = $this->accessTtlSeconds();
        $issuedAt = time();

        $payload = [
            'sub' => (string) $user->id,
            'role' => (string) $user->role,
            'iat' => $issuedAt,
            'exp' => $issuedAt + $ttl,
            'jti' => (string) Str::uuid(),
            'iss' => $this->issuer(),
        ];

        $audience = $this->audience();
        if ($audience !== '') {
            $payload['aud'] = $audience;
        }

        $token = JWT::encode($payload, $this->secret(), 'HS256');

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $ttl,
        ];
    }

    public function decodeAccessToken(string $token): array
    {
        $decoded = (array) JWT::decode($token, new Key($this->secret(), 'HS256'));

        $issuer = $this->issuer();
        if ($issuer !== '' && (($decoded['iss'] ?? null) !== $issuer)) {
            throw new RuntimeException(__('auth.jwt.issuer_invalid'));
        }

        $audience = $this->audience();
        if ($audience !== '' && (($decoded['aud'] ?? null) !== $audience)) {
            throw new RuntimeException(__('auth.jwt.audience_invalid'));
        }

        return $decoded;
    }

    public function verifyAccessToken(string $token): bool
    {
        try {
            $this->decodeAccessToken($token);
            return true;
        } catch (ExpiredException | SignatureInvalidException | UnexpectedValueException | RuntimeException $exception) {
            return false;
        }
    }

    public function decodeAccessTokenOrFail(string $token): array
    {
        try {
            return $this->decodeAccessToken($token);
        } catch (ExpiredException $exception) {
            throw new RuntimeException(__('auth.jwt.expired'));
        } catch (SignatureInvalidException $exception) {
            throw new RuntimeException(__('auth.jwt.signature_invalid'));
        } catch (UnexpectedValueException $exception) {
            throw new RuntimeException(__('auth.jwt.invalid'));
        }
    }

    private function secret(): string
    {
        $secret = (string) config('auth.jwt.secret');

        if ($secret === '') {
            throw new RuntimeException(__('auth.jwt.secret_missing'));
        }

        if (strlen($secret) < 32) {
            throw new RuntimeException(__('auth.jwt.secret_too_short'));
        }

        return $secret;
    }

    private function issuer(): string
    {
        return (string) config('auth.jwt.issuer', '');
    }

    private function audience(): string
    {
        return (string) config('auth.jwt.audience', '');
    }

    private function accessTtlSeconds(): int
    {
        return (int) config('auth.jwt.access_ttl_seconds', 900);
    }
}
