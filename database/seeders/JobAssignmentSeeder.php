<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\JobAssignment;
use Illuminate\Database\Seeder;

class JobAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $acceptedBids = Bid::query()
            ->where('status', 'accepted')
            ->with('job')
            ->get();

        foreach ($acceptedBids as $bid) {
            if (is_null($bid->job)) {
                continue;
            }

            JobAssignment::factory()->create([
                'job_id' => $bid->job_id,
                'bid_id' => $bid->id,
                'worker_id' => $bid->worker_id,
                'client_id' => $bid->job->client_id,
            ]);
        }
    }
}
