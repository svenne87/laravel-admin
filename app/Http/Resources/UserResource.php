<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\RoleResource;
use Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request = false)
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'joined' => $this->joined,
        ];

        if (Auth::user()->hasPermissionTo('create users', 'api') || Auth::user()->hasPermissionTo('edit users', 'api')) { 
            $this->roles->each(function($role) use (&$resource) {
                !isset($resource['roles']) ?? $resource['roles'] = array();
                $resource['roles'][] = new RoleResource($role);
            });
        }

        return $resource;
    }
}
