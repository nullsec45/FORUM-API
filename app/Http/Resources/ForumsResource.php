<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ForumsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "judul" => ucfirst($this->title),
            "body" => $this->body,
            "slug" => $this->slug,
            "category" => $this->category,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "user" => $this->user,
            "comment_count" => $this->comments_count
        ];
    }
}
