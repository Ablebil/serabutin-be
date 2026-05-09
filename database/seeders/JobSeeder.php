<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    private const OPEN_COUNT = 20;
    private const IN_PROGRESS_COUNT = 6;
    private const COMPLETED_COUNT = 4;
    private const CANCELLED_COUNT = 2;

    public function run(): void
    {
        $clients = User::query()->where('role', 'client')->get();
        $categories = Category::query()->get();

        if ($clients->isEmpty() || $categories->isEmpty()) {
            return;
        }

        $this->seedJobs($clients, $categories, self::OPEN_COUNT, 'open');
        $this->seedJobs($clients, $categories, self::IN_PROGRESS_COUNT, 'in_progress');
        $this->seedJobs($clients, $categories, self::COMPLETED_COUNT, 'completed');
        $this->seedJobs($clients, $categories, self::CANCELLED_COUNT, 'cancelled');
    }

    private function seedJobs($clients, $categories, int $count, string $status): void
    {
        Job::factory()
            ->count($count)
            ->state(fn() => [
                'client_id' => $clients->random()->id,
                'category_id' => $categories->random()->id,
                'status' => $status,
            ])
            ->create();
    }
}
