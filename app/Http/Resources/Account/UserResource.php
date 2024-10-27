<?php

namespace App\Http\Resources\Account;

use App\Http\Resources\General\FileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "phone" => $this->phone,
            "gender" => $this->gender,
            "email" => $this->email,
            "role" => $this->role,
            "date_of_birth" => $this->date_of_birth,
            "email_verified_at" => $this->email_verified_at,
            "created_at" => $this->created_at,
        ];
    }
}
