<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->doesntHave('profile')->get();

        foreach ($users as $user) {
            UserProfile::factory()->create(['user_id' => $user->id]);
        }
    }
}
