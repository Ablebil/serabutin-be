<?php

namespace App\Http\Controllers\Api\V1\Bids;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Bids\AcceptBidRequest;
use App\Http\Requests\Api\V1\Bids\RejectBidRequest;
use Illuminate\Http\JsonResponse;

class BidActionController extends Controller
{
    public function accept(AcceptBidRequest $request, string $id): JsonResponse
    {
        return $this->error('Not implemented.', 501);
    }

    public function reject(RejectBidRequest $request, string $id): JsonResponse
    {
        return $this->error('Not implemented.', 501);
    }
}
