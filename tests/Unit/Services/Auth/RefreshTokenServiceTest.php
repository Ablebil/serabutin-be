<?php

namespace Tests\Unit\Services\Auth;

use App\Models\User;
use App\Services\Auth\RefreshTokenService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class RefreshTokenServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_hash_token_uses_sha256_and_is_deterministic(): void
    {
        $service = new RefreshTokenService();

        $plainToken = 'refresh-token-plain-value';
        $hashA = $service->hashToken($plainToken);
        $hashB = $service->hashToken($plainToken);

        $this->assertSame($hashA, $hashB);
        $this->assertSame(hash('sha256', $plainToken), $hashA);
        $this->assertNotSame($plainToken, $hashA);
    }

    public function test_rotate_returns_null_when_token_is_not_found_or_invalid(): void
    {
        $serviceMock = Mockery::mock(RefreshTokenService::class)->makePartial();
        $serviceMock->shouldReceive('findValid')
            ->once()
            ->with('missing-token')
            ->andReturnNull();

        /** @var RefreshTokenService $service */
        $service = $serviceMock;

        $result = $service->rotate('missing-token');

        $this->assertNull($result);
    }

    public function test_rotate_returns_null_and_deletes_session_for_inactive_user(): void
    {
        $serviceMock = Mockery::mock(RefreshTokenService::class)->makePartial();

        $inactiveUser = new User();
        $inactiveUser->id = 'user-id-123';
        $inactiveUser->is_active = false;

        $fakeSession = new class extends \App\Models\RefreshToken {
            public bool $deleted = false;

            public function delete(): bool|null
            {
                $this->deleted = true;
                return true;
            }
        };

        $fakeSession->setRelation('user', $inactiveUser);

        $serviceMock->shouldReceive('findValid')
            ->once()
            ->with('inactive-user-token')
            ->andReturn($fakeSession);

        /** @var RefreshTokenService $service */
        $service = $serviceMock;

        $result = $service->rotate('inactive-user-token');

        $this->assertNull($result);
        $this->assertTrue($fakeSession->deleted);
    }
}
