<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){
    Route::get('/me', function (Request $request) {
        $resource = new UserResource($request->user());
        return $resource->response()->setStatusCode(200);
    });

    Route::post('user_avatar', 'API\UserController@updateAvatar');
    
    Route::apiResources([
        'users' => 'API\UserController',
        'roles' => 'API\RoleController',
        'permissions' => 'API\PermissionController',
        'posts' => 'API\PostController',
        'vocabularies' => 'API\VocabularyController',
    ]);

});