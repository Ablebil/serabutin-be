<?php

namespace App\Http\Resources\Api\V1\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicUserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'bio' => $this->bio,
            'location_district' => $this->location_district,
            'location_city' => $this->location_city,
            'avatar_url' => $this->avatar_url,
            'avg_rating' => $this->avg_rating,
            'total_jobs_posted' => $this->total_jobs_posted,
            'total_jobs_completed' => $this->total_jobs_completed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
