<?php

namespace App\Http\Resources\Api\V1\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function __construct(
        mixed $resource,
        private readonly ?array $categoryRatings = null,
    ) {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'bio' => $this->bio,
            'location_district' => $this->location_district,
            'location_city' => $this->location_city,
            'avatar_url' => $this->avatar_url,
            'phone' => $this->phone,
            'avg_rating' => $this->avg_rating,
            'total_jobs_posted' => $this->total_jobs_posted,
            'total_jobs_completed' => $this->total_jobs_completed,
            'category_ratings' => $this->when(
                !is_null($this->categoryRatings),
                $this->categoryRatings
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}