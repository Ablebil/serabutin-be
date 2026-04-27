<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Mail\VerifyEmailMail;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\Auth\EmailVerificationTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, EmailVerificationTokenService $tokenService): JsonResponse
    {
        $payload = $request->validated();

        $exists = User::query()
            ->where('email', $payload['email'])
            ->exists();

        if ($exists) {
            return $this->error(__('auth.register.email_exists'), 409);
        }

        $user = DB::transaction(function () use ($payload, $tokenService): User {
            $user = User::query()->create([
                'email' => $payload['email'],
                'password_hash' => $payload['password'],
                'full_name' => $payload['full_name'],
                'role' => $payload['role'],
                'is_verified' => false,
                'is_active' => true,
            ]);

            UserProfile::query()->create([
                'user_id' => $user->id,
                'avatar_url' => null,
            ]);

            $token = $tokenService->issue($user);
            $verificationUrl = $tokenService->buildVerificationUrl($token);

            Mail::to($user->email)->send(new VerifyEmailMail($user->full_name, $verificationUrl));

            return $user;
        });

        return $this->success(__('auth.register.success'), $user->fresh(), 201);
    }
}
