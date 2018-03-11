<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'post_type' => $this->post_type,
            'status' => $this->status,
            'description' => $this->description,
            'content' => $this->content,
            'parent_id' => $this->parent_id,
            'author' => ['name' => $this->author->name, 'id' => $this->author->id],
            'created' => $this->created,
        ];
    }
}
