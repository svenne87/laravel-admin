<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Spatie\Permission\Models\Permission;
use Auth;
use Lang;
use Response;
use App\Post;
//use Cache;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
    * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasPermissionTo('show posts', 'api')) {
            // We are allowed to show Posts
            
            // Handle Sort 
            if (request()->has('sort')) {
                list($sortCol, $sortDir) = explode('|', request()->sort);
                $query = Post::orderBy($sortCol, $sortDir);
            } else {
                $query = Post::orderBy('id', 'ASC');
            }

             // Handle Post type
            if (request()->has('post_type')) {
                $query->where('post_type', "{$request->post_type}");
            }
    
            // Handle Filter
            if ($request->exists('filter')) {
                $query->where(function($q) use($request) {
                    $value = "%{$request->filter}%";
                    $q->where('title', 'like', $value)
                        ->orWhere('slug', 'like', $value);
                });
            }
            
            $perPage = request()->has('per_page') ? (int) request()->per_page : 20;
            $posts = $query->paginate($perPage);
            return PostResource::collection($posts);
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
        
        if ($user->hasPermissionTo('create posts', 'api')) {
            $this->validate($request, [
                'title' => 'required|min:3|max:200', 
                'slug' => 'unique:posts|required|regex:/(^[a-z0-9-]+$)+/', 
                'status' => 'required|', 
            ]);

            $request->merge(['author_id' => $user->id]);
            $post = Post::create($request->all());

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
        if (Auth::user()->hasPermissionTo('show posts', 'api')) {
            // We are allowed to show any Post
            $post = Post::findOrFail($id);
            $resource = new PostResource($post);
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
          
        if ($user->hasPermissionTo('edit posts', 'api')) {
            $this->validate($request, [
                'title' => 'required|min:3|max:200', 
                'slug' => 'unique:posts,slug,'.$id.'|required|regex:/(^[a-z0-9-]+$)+/', 
                'status' => 'required|', 
            ]);
            
            $post = Post::findOrFail($id);
            $post->update($request->all());

            $resource = new PostResource($post);

            // Clear this cache Cache::forget('pages_' . $post->slug);
            
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
        if (Auth::user()->hasPermissionTo('delete posts', 'api')) {
            Post::destroy($id);
            return Response::json([], 204);   
        }

        return abort('403');
    }
}
