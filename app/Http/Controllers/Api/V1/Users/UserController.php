<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Users\UpdateProfileRequest;
use App\Http\Resources\Api\V1\Users\PublicUserProfileResource;
use App\Http\Resources\Api\V1\Users\UserProfileResource;
use App\Http\Resources\Api\V1\Users\UserResource;
use App\Models\User;
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

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->attributes->get('auth_user');

        $payload = $request->validated();

        if (array_key_exists('full_name', $payload)) {
            $user->full_name = $payload['full_name'];
            $user->save();
        }

        $profileFields = array_intersect_key($payload, array_flip([
            'bio',
            'location_district',
            'location_city',
            'phone',
        ]));

        if (!empty($profileFields)) {
            $user->profile()->update($profileFields);
        }

        $user->loadMissing('profile');
        $user->profile->refresh();

        return $this->success(
            __('users.update.success'),
            [
                'user' => new UserResource($user),
                'profile' => new UserProfileResource($user->profile),
            ]
        );
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $target = User::query()
            ->where('id', $id)
            ->where('is_active', true)
            ->with('profile')
            ->first();

        if (is_null($target)) {
            return $this->error(__('users.show.not_found'), 404);
        }

        return $this->success(
            __('users.show.success'),
            [
                'user' => new UserResource($target),
                'profile' => new PublicUserProfileResource($target->profile),
            ]
        );
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