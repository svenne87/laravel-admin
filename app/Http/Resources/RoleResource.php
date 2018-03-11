<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;
use Auth;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'display' => ucfirst(preg_replace('/[^A-Za-z0-9\-]/', ' ', $this->name)),
            'name' => $this->name,
            'guard' => $this->guard_name,
            'created' => $this->created_at->diffForHumans(),
        ];

        if (Auth::user()->hasPermissionTo('create roles', 'api') || Auth::user()->hasPermissionTo('edit users', 'roles')) {     
            $this->permissions->each(function($permission) use (&$resource) {
                !isset($resource['permissions']) ?? $resource['permissions'] = array();
                $resource['permissions'][] = new RoleResource($permission);
            });
        }

        return $resource;
    }
}
