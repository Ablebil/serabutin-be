<?php

namespace App\Http\Controllers\Api\V1\Bids;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Bids\CancelBidRequest;
use App\Http\Requests\Api\V1\Bids\ListBidsRequest;
use App\Http\Requests\Api\V1\Bids\StoreBidRequest;
use Illuminate\Http\JsonResponse;

class BidController extends Controller
{
    public function index(ListBidsRequest $request, string $id): JsonResponse
    {
        return $this->error('Not implemented.', 501);
    }

    public function store(StoreBidRequest $request, string $id): JsonResponse
    {
        return $this->error('Not implemented.', 501);
    }

    public function cancel(CancelBidRequest $request, string $id): JsonResponse
    {
        return $this->error('Not implemented.', 501);
    }
}
