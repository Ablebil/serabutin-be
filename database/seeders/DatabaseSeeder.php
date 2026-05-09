<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            UserProfileSeeder::class,
            JobSeeder::class,
            BidSeeder::class,
            JobAssignmentSeeder::class,
            ReviewSeeder::class,
            RefreshTokenSeeder::class,
        ]);
    }
}
