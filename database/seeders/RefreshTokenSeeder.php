<?php

namespace Database\Seeders;

use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Database\Seeder;

class RefreshTokenSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->inRandomOrder()->limit(10)->get();

        foreach ($users as $user) {
            RefreshToken::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
