<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use Lang;
use Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasPermissionTo('show roles', 'api')) {
            // We are allowed to show Roles
                     
            // Handle Sort 
            if (request()->has('sort')) {
                list($sortCol, $sortDir) = explode('|', request()->sort);
                $query = Role::orderBy($sortCol, $sortDir);
            } else {
                $query = Role::orderBy('id', 'ASC');
            }
    
            // Handle Filter
            if ($request->exists('filter')) {
                $query->where(function($q) use($request) {
                    $value = "%{$request->filter}%";
                    $q->where('name', 'like', $value)
                        ->orWhere('guard_name', 'like', $value);
                });
            }
    
            $perPage = request()->has('per_page') ? (int) request()->per_page : 0;
            $roles = $perPage == 0 ? $query->get() : $query->paginate($perPage);
            return RoleResource::collection($roles);
        } 
        return abort('403');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->hasPermissionTo('create roles', 'api')) {
            $this->validate($request, [
                'name' => 'required|min:1|max:100|regex:/(^[a-z0-9_]+$)+/', 
                'guard' => 'required', 
            ]);
            
            $request->merge(['guard_name' => $request->get('guard')]);
            $role = Role::create($request->only(['name', 'guard_name']));

            if ($request->has('permissions')) {
                $permissions = $request->get('permissions');
                $permissionsToAssign = array();
                
                foreach ($permissions as $permission) {
                    $permissionsToAssign[] = Permission::findByName($permission['name'], $permission['guard']); 
                }
                
                $role->syncPermissions($permissionsToAssign);
            }

            return Response::json([
                'created' => true,
            ], 201);
        }
        return abort('403');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->hasPermissionTo('show roles', 'api')) {
            // We are allowed to show any Role
            $role = Role::findOrFail($id);
            $resource = new RoleResource($role);

            return $resource->response()->setStatusCode(200);
        } 
        return abort('403');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
          
        if ($user->hasPermissionTo('edit roles', 'api')) {
            $this->validate($request, [
                'name' => 'required|min:1|max:100|regex:/(^[a-z0-9_]+$)+/', 
                'guard' => 'required', 
            ]);
            
            $request->merge(['guard_name' => $request->get('guard')]);
            $role = Role::findOrFail($id);
            $role->update($request->only(['name', 'guard_name']));

            if ($request->has('permissions')) {
                $permissions = $request->get('permissions');
                $permissionsToAssign = array();

                foreach ($permissions as $permission) {
                    $permissionsToAssign[] = Permission::findByName($permission['name'], $permission['guard']); 
                }
                
                $role->syncPermissions($permissionsToAssign);
            }

            $resource = new RoleResource($role);
            return $resource->response()->setStatusCode(201);
        }
        return abort('403');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->hasPermissionTo('delete roles', 'api')) {
            Role::destroy($id);
            return Response::json([], 204);   
        }

        return abort('403');
    }
}
