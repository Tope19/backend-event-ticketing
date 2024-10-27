<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "rating" => $this->getAverageReview($this->user_id),
            "review" => $this->review,
            "name" => $this->name,
            "user_id" => $this->user()->first(),
            "created_at" => $this->created_at,
        ];
    }
}
