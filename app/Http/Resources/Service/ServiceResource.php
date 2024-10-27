<?php

namespace App\Http\Resources\Service;

use App\Http\Resources\General\FileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            "description" => $this->description,
            "status" => $this->status,
            "cover" => !empty($key = $this->cover) ? new FileResource($key) : null,
            "category" => !empty($key = $this->category) ? new ServiceCategoryResource($key) : null,
        ];
    }
}
