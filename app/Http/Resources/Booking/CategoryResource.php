<?php

namespace App\Http\Resources\Booking;

use App\Http\Resources\General\FileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            "name" => $this->name,
            "icon" => !empty($key = $this->icon) ? new FileResource($key) : null,
            "category_id" => $this->category_id ?? null,
        ];

    }
}
