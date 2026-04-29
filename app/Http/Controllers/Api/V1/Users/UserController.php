<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Users\UserProfileResource;
use App\Http\Resources\Api\V1\Users\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $user = $request->attributes->get('auth_user');

        $user->loadMissing('profile');

        return $this->success(
            __('users.me.success'),
            [
                'user' => new UserResource($user),
                'profile' => new UserProfileResource($user->profile),
            ]
        );
    }

    public function update()
    {
    }

    public function show()
    {
    }

    public function postedJobs()
    {
    }

    public function bidHistory()
    {
    }

    public function assignments()
    {
    }
}