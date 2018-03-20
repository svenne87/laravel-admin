<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Resources\UserResource;
use Spatie\Permission\Models\Permission;
use App\Vocabulary;
use Auth;
use Lang;
use Response;

class VocabularyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $requeste
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasPermissionTo('show taxonomies', 'api')) {
            // We are allowed to show Vocabularies
            
            // Handle Sort 
            if (request()->has('sort')) {
                list($sortCol, $sortDir) = explode('|', request()->sort);
                $query = Vocabulary::orderBy($sortCol, $sortDir);
            } else {
                $query = Vocabulary::orderBy('id', 'ASC');
            }
    
            // Handle Filter
            if ($request->exists('filter')) {
                $query->where(function($q) use($request) {
                    $value = "%{$request->filter}%";
                    $q->where('name', 'like', $value)
                        ->orWhere('description', 'like', $value);
                });
            }
            
            $perPage = request()->has('per_page') ? (int) request()->per_page : 20;
            $vocabularies = $query->paginate($perPage);
            //return PostResource::collection($posts);
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
