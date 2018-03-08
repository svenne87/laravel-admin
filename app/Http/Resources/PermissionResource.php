<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'display' => ucfirst(preg_replace('/[^A-Za-z0-9\-]/', ' ', $this->name)),
            'name' => $this->name,
            'guard' => $this->guard_name,
            'created' => $this->created_at->diffForHumans(),
        ];
    }
}
