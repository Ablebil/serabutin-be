<?php

namespace Tests\Unit\Services\Auth;

use App\Models\User;
use App\Services\Auth\JwtService;
use Illuminate\Support\Str;
use RuntimeException;
use Tests\TestCase;

class JwtServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'auth.jwt.secret' => 'abcdefghijklmnopqrstuvwxyz123456',
            'auth.jwt.issuer' => 'https://serabutin.test',
            'auth.jwt.audience' => 'https://serabutin.test',
            'auth.jwt.access_ttl_seconds' => 900,
        ]);
    }

    public function test_it_issues_access_token_with_expected_payload_structure(): void
    {
        $service = app(JwtService::class);

        $user = new User();
        $user->id = (string) Str::uuid();
        $user->role = 'worker';

        $issued = $service->issueAccessToken($user);

        $this->assertArrayHasKey('access_token', $issued);
        $this->assertArrayHasKey('token_type', $issued);
        $this->assertArrayHasKey('expires_in', $issued);
        $this->assertSame('Bearer', $issued['token_type']);
        $this->assertSame(900, $issued['expires_in']);

        $claims = $service->decodeAccessTokenOrFail($issued['access_token']);

        $this->assertSame($user->id, $claims['sub']);
        $this->assertSame('worker', $claims['role']);
        $this->assertSame('https://serabutin.test', $claims['iss']);
        $this->assertSame('https://serabutin.test', $claims['aud']);
        $this->assertIsString($claims['jti']);
        $this->assertIsInt($claims['iat']);
        $this->assertIsInt($claims['exp']);
        $this->assertSame(900, $claims['exp'] - $claims['iat']);
    }

    public function test_it_throws_when_signature_is_invalid(): void
    {
        $service = app(JwtService::class);

        $user = new User();
        $user->id = (string) Str::uuid();
        $user->role = 'client';

        $token = $service->issueAccessToken($user)['access_token'];

        config(['auth.jwt.secret' => '1234567890abcdefghijklmnopqrstuvwxyz']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(__('auth.jwt.signature_invalid'));

        $service->decodeAccessTokenOrFail($token);
    }

    public function test_it_returns_false_for_malformed_access_token(): void
    {
        $service = app(JwtService::class);

        $this->assertFalse($service->verifyAccessToken('not-a-jwt-token'));
    }
}
