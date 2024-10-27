<?php

namespace App\Http\Resources\Booking;

use App\Http\Resources\Account\ProfileResource;
use App\Http\Resources\Service\ServiceCategoryResource;
use App\Http\Resources\Service\ServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientBookingResource extends JsonResource
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
            "service" => new ServiceResource($this->service),
            "category" => new ServiceCategoryResource($this->category),
            "artisan" => new ProfileResource($this->artisan),
            "schedule_date" => $this->schedule_date,
            "reference" => $this->reference,
            "start_from" => $this->start_from,
            "end_at" => $this->end_at,
            "address" => $this->address,
            "longitude" => $this->longitude,
            "latitude" => $this->latitude,
            "country" => $this->country,
            "state" => $this->state,
            "city" => $this->city,
            "status" => $this->status,
        ];
    }
}
