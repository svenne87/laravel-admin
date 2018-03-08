<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Auth;
use Lang;
use Image;
use Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasPermissionTo('show users', 'api')) {
            // We are allowed to show Users
            
            // Handle Sort 
            if (request()->has('sort')) {
                list($sortCol, $sortDir) = explode('|', request()->sort);
                $query = User::orderBy($sortCol, $sortDir);
            } else {
                $query = User::orderBy('id', 'ASC');
            }
    
            // Handle Filter
            if ($request->exists('filter')) {
                $query->where(function($q) use($request) {
                    $value = "%{$request->filter}%";
                    $q->where('name', 'like', $value)
                        ->orWhere('email', 'like', $value);
                });
            }
    
            $perPage = request()->has('per_page') ? (int) request()->per_page : 20;
            $users = $query->paginate($perPage);
            return UserResource::collection($users);
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
        
        if ($user->hasPermissionTo('create users', 'api')) {
            $this->validate($request, [
                'name' => 'required|min:3|max:200', 
                'email' => 'unique:users|email|required', 
                'password' => 'required|string|min:6|confirmed', 
            ]);
            
            $data = $request->except('password');
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);

            if ($request->has('roles')) {
                $roles = $request->get('roles');
                $rolesToAssign = array();
                
                foreach ($roles as $role) {
                    $rolesToAssign[] = Role::findByName($role['name'], $role['guard']); 
                }
                
                $user->syncRoles($rolesToAssign);
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
        if (Auth::user()->hasPermissionTo('show users', 'api')) {
            // We are allowed to show any User
            $loadedUser = User::findOrFail($id);
            $resource = new UserResource($loadedUser);

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
    public function update(Request $request, $id = false)
    {

        // If we are trying to modify other user without permission
        if (Auth::id() != $id && !Auth::user()->hasPermissionTo('edit users', 'api')) {
            abort(403);
        }

        $user = User::findOrFail($id);
        
        $this->validate($request, [
            'name' => 'required|min:3|max:200',
            'email' => 'unique:users,email,'.$user->id.'|email|required',
        ]);

        if ($request->has('email') && $request->get('email') != $user->email && !Auth::user()->hasPermissionTo('edit users', 'api')) {
            $this->validate($request, ['current_password' => 'required|current_password']);
        }

        if ($request->has('new_password')) { 
            if (!Auth::user()->hasPermissionTo('edit users', 'api')) $this->validate($request, ['current_password' => 'required|current_password']);
            $this->validate($request, ['new_password' => 'required|string|min:6|confirmed']);

            $request->user()->fill([
                'password' => Hash::make($request->get('new_password'))
            ])->save();
        }

        $user->update($request->all());

        if (Auth::user()->hasPermissionTo('edit users', 'api') && $request->has('roles')) {
            $roles = $request->get('roles');
            $rolesToAssign = array();
            
            foreach ($roles as $role) {
                $rolesToAssign[] = Role::findByName($role['name'], $role['guard']); 
            }
            
            $user->syncRoles($rolesToAssign);
        }
        
        $resource = new UserResource($user);
        return $resource->response()->setStatusCode(201);
    }

    /**
     * Set User avatar image
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function updateAvatar(Request $request)
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);

        if ($request->get('image')) {
            $this->validate($request, ['image' => 'imageable']);

            $image = $request->get('image');
            $fileName = $name = sha1(date('YmdHis') . str_random(30)) . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $size = 300;
            $path = storage_path('app/public/uploads/avatars/');

             // Create the folder if it's not already there
            if (!is_dir($path)) mkdir($path, 0755, true);
             
            $imageCreated = Image::make($request->get('image'));
    
            $imageCreated->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            
            // Save
            $imageCreated->save($path . $fileName);

            // Update User
            $user->avatar = $fileName;
            $user->save();

            return Response::json([
                'saved' => true,
                'image' => $fileName,
            ], 201);   
        }
        return Response::json([
            'saved' => false,
            'error' => Lang::get('errors.no_image')
        ], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        if ($user->hasPermissionTo('delete users', 'api')) {
            if ($user->id != $id) {
                User::destroy($id);

                return Response::json([], 204);
            }    
        }

        return abort('403');
    }
}


/*
    https://github.com/stevebauman/revision   - install?
    https://github.com/Intervention/image
    https://github.com/spatie/laravel-permission#installation
    https://github.com/atayahmet/laravel-nestable
    https://laracasts.com/series/laravel-5-fundamentals/episodes/25
    https://www.froala.com/wysiwyg-editor  - but in vue
    https://github.com/ratiw/vuetable-2-tutorial-bootstrap/blob/master/src/components/MyVuetable.vue
    ( https://github.com/lavary/laravel-menu )
    https://bootstrap-vue.js.org/docs/components/
    https://github.com/hilongjw/vue-progressbar
    
    Tester update i UserController + allt i Roles, Permissions controller

    Change vou-router to use child routes and simple bread crumbs
    'welcome' -> 'master'
    Should create .env och set certain values + copy default image and cretae avatars dir etc.
    ln -s ../storage/app/public storage   (run in public (not in nanobox virtual))
    500 duplicates view
    php artisan vue-i18n:generate
    php artisan passport:install
    Larval blade extend
    https://cms.botble.com/admin
    https://github.com/axios/axios/issues/690
    https://tutsforweb.com/laravel-5-5-and-dropzone-js-uploading-images-with-removal-links/
    // UserResource conditional if admin or not...
    // TODO include permissions into Vue
    // TODO navbar image = make it a vue template?
    // TODO Style Login
    // TODO should split admin and client app... But keep same landing page for roles and start app there, just different app depending on role?
*/