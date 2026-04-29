<?php

namespace App\Http\Resources\Api\V1\Reviews;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assignment_id' => $this->assignment_id,
            'reviewer' => $this->whenLoaded('reviewer', fn() => [
                'id' => $this->reviewer->id,
                'full_name' => $this->reviewer->full_name,
                'role' => $this->reviewer->role,
                'created_at' => $this->reviewer->created_at,
                'updated_at' => $this->reviewer->updated_at,
            ]),
            'reviewee' => $this->whenLoaded('reviewee', fn() => [
                'id' => $this->reviewee->id,
                'full_name' => $this->reviewee->full_name,
                'role' => $this->reviewee->role,
                'created_at' => $this->reviewee->created_at,
                'updated_at' => $this->reviewee->updated_at,
            ]),
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
        ];
    }
}
