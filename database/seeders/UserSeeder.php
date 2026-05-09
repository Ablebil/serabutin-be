<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(10)->client()->withProfile()->create();
        User::factory()->count(20)->worker()->withProfile()->create();
    }
}
