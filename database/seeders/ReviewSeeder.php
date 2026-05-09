<?php

namespace Database\Seeders;

use App\Models\JobAssignment;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = JobAssignment::query()
            ->whereHas('job', fn($query) => $query->where('status', 'completed'))
            ->with('job')
            ->get();

        foreach ($assignments as $assignment) {
            Review::factory()->create([
                'assignment_id' => $assignment->id,
                'reviewer_id' => $assignment->client_id,
                'reviewee_id' => $assignment->worker_id,
            ]);
        }
    }
}
