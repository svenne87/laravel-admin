<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Spatie\Permission\Models\Permission;
use Auth;
use Lang;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
    * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasPermissionTo('show permissions', 'api')) {
            // We are allowed to show Permissions
                     
            // Handle Sort 
            if (request()->has('sort')) {
                list($sortCol, $sortDir) = explode('|', request()->sort);
                $query = Permission::orderBy($sortCol, $sortDir);
            } else {
                $query = Permission::orderBy('id', 'ASC');
            }
    
            // Handle Filter
            if ($request->exists('filter')) {
                $query->where(function($q) use($request) {
                    $value = "%{$request->filter}%";
                    $q->where('name', 'like', $value)
                        ->orWhere('guard_name', 'like', $value);
                });
            }
    
            $perPage = request()->has('per_page') ? (int) request()->per_page : 20;
            $permissions = $query->paginate($perPage);
            return PermissionResource::collection($permissions);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
