<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileSummaryService
{
    public function refreshJobCounts(User $user): void
    {
        if ($user->role === 'client') {
            $count = DB::table('jobs')
                ->where('client_id', $user->id)
                ->whereNull('deleted_at')
                ->count();

            $user->profile()->update(['total_jobs_posted' => $count]);
        } elseif ($user->role === 'worker') {
            $count = DB::table('job_assignments')
                ->where('worker_id', $user->id)
                ->count();

            $user->profile()->update(['total_jobs_completed' => $count]);
        }
    }

    public function refreshGlobalRating(User $user): void
    {
        $avg = DB::table('reviews')
            ->where('reviewee_id', $user->id)
            ->avg('rating') ?? 0.0;

        $user->profile()->update(['avg_rating' => round((float) $avg, 2)]);
    }

    public function getCategoryRatings(User $user): array
    {
        return DB::table('reviews as r')
            ->join('job_assignments as ja', 'r.assignment_id', '=', 'ja.id')
            ->join('jobs as j', 'ja.job_id', '=', 'j.id')
            ->join('categories as c', 'j.category_id', '=', 'c.id')
            ->where('r.reviewee_id', $user->id)
            ->groupBy('j.category_id', 'c.name')
            ->select(
                'j.category_id',
                'c.name as category_name',
                DB::raw('ROUND(AVG(r.rating)::numeric, 2) as avg_rating'),
                DB::raw('COUNT(r.id) as review_count'),
            )
            ->orderByDesc('avg_rating')
            ->get()
            ->map(fn($row) => [
                'category_id' => $row->category_id,
                'category_name' => $row->category_name,
                'avg_rating' => (float) $row->avg_rating,
                'review_count' => (int) $row->review_count,
            ])
            ->all();
    }
}
