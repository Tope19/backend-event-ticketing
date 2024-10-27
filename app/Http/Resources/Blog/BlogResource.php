<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "category" => $this->category()->first(),
            "title" => $this->title,
            "caption" => $this->caption,
            "cover_image" => $this->cover_image,
            "content" => $this->content,
            "status" => $this->status,
            "views" => $this->views,
            "likes" => $this->likes,
        ];
    }
}
