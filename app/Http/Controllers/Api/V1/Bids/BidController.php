<?php

namespace App\Http\Controllers\Api\V1\Bids;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Bids\CancelBidRequest;
use App\Http\Requests\Api\V1\Bids\ListBidsRequest;
use App\Http\Requests\Api\V1\Bids\StoreBidRequest;
use App\Http\Resources\Api\V1\Bids\BidResource;
use App\Models\Bid;
use App\Models\Job;
use Illuminate\Http\JsonResponse;

class BidController extends Controller
{
    public function index(ListBidsRequest $request, string $id): JsonResponse
    {
        return $this->error('Not implemented.', 501);
    }

    public function store(StoreBidRequest $request, string $id): JsonResponse
    {
        $user = $request->attributes->get('auth_user');
        $job = Job::find($id);

        if (is_null($job) || !is_null($job->deleted_at)) {
            return $this->error(__('bids.store.job_not_found'), 404);
        }

        if ($job->status !== 'open') {
            return $this->error(__('bids.store.job_not_open'), 403);
        }

        if ($job->client_id === $user->id) {
            return $this->error(__('bids.store.own_job'), 403);
        }

        $exists = Bid::query()
            ->where('job_id', $job->id)
            ->where('worker_id', $user->id)
            ->exists();

        if ($exists) {
            return $this->error(__('bids.store.already_bid'), 409);
        }

        $payload = $request->validated();

        $bid = Bid::query()->create([
            'job_id' => $job->id,
            'worker_id' => $user->id,
            'proposed_price' => $payload['proposed_price'],
            'status' => 'pending',
        ]);

        $bid->load('worker');

        return $this->success(
            __('bids.store.success'),
            new BidResource($bid),
            201
        );
    }

    public function cancel(CancelBidRequest $request, string $id): JsonResponse
    {
        return $this->error('Not implemented.', 501);
    }
}
