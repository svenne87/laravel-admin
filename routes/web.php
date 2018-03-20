<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MasterController@index')->name('master');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/profile', 'Web\ProfileController@index')->name('profile');
});

Route::group(['middleware' => ['permission:access admin cp']], function () {
    Route::get('/admin-cp', 'Admin\AdminController@index')->name('admin-cp');
});

Route::get('/{slug}', 'Web\PageController@view')->name('page_view');

Route::fallback('MasterController@notFound');
