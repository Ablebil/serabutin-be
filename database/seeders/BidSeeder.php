<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;

class BidSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = Job::query()->get();
        $workers = User::query()->where('role', 'worker')->get();

        if ($jobs->isEmpty() || $workers->isEmpty()) {
            return;
        }

        foreach ($jobs as $job) {
            $bidCount = random_int(1, min(3, $workers->count()));
            $bidders = $workers->shuffle()->take($bidCount);

            $bidders->values()->each(function (User $worker, int $index) use ($job) {
                $status = 'pending';

                if (in_array($job->status, ['in_progress', 'completed'], true) && $index === 0) {
                    $status = 'accepted';
                }

                Bid::factory()->state([
                    'job_id' => $job->id,
                    'worker_id' => $worker->id,
                    'status' => $status,
                ])->create();
            });
        }
    }
}
