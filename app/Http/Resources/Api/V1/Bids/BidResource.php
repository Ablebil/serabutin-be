<?php

namespace App\Http\Resources\Api\V1\Bids;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BidResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_id' => $this->job_id,
            'job_title' => $this->whenLoaded('job', fn() => $this->job->title),
            'worker_id' => $this->worker_id,
            'proposed_price' => $this->proposed_price,
            'estimated_duration_hours' => $this->estimated_duration_hours,
            'message' => $this->message,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
