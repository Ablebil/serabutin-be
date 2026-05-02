<?php

namespace App\Http\Controllers\Api\V1\Jobs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Jobs\StoreJobRequest;
use App\Http\Resources\Api\V1\Jobs\JobResource;
use App\Models\Job;
use App\Services\Users\ProfileSummaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function __construct(
        private readonly ProfileSummaryService $profileSummary,
    ) {
    }

    public function index(): JsonResponse
    {
        return $this->success('Jobs feed');
    }

    public function store(StoreJobRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $job = DB::transaction(function () use ($payload, $request) {
            $job = Job::create([
                'client_id' => $request->attributes->get('auth_user')->id,
                'category_id' => $payload['category_id'],
                'title' => $payload['title'],
                'description' => $payload['description'],
                'budget_min' => $payload['budget_min'],
                'budget_max' => $payload['budget_max'],
                'workers_needed' => $payload['workers_needed'],
                'location_district' => $payload['location_district'],
                'location_city' => $payload['location_city'],
                'status' => 'open',
                'start_at' => $payload['start_at'],
                'deadline_at' => $payload['deadline_at'],
            ]);

            $this->profileSummary->refreshJobCounts($request->attributes->get('auth_user'));

            return $job;
        });

        $job->load(['client', 'category']);

        return $this->success(
            __('jobs.store.success'),
            new JobResource($job),
            201
        );
    }

    public function show(): JsonResponse
    {
        return $this->success('Job detail');
    }

    public function update(): JsonResponse
    {
        return $this->success('Job updated');
    }

    public function destroy(): JsonResponse
    {
        return $this->success('Job deleted');
    }

    public function updateStatus(): JsonResponse
    {
        return $this->success('Job status updated');
    }
}
