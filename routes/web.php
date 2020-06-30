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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::match(['GET','POST'],'/admin/authentication','Admin\AuthenticationController@index');

Route::middleware(['logincheck'])->group(function ()
{

	Route::get('/admin/dashboard','Admin\DashboardController@index')->name('dashboard');

	Route::get('/admin/logout','Admin\AuthenticationController@logout')->name('logout');

	Route::match(['GET','POST'],'/admin/forgot_password','Admin\AuthenticationController@forgot_password')->name('forgot_password');

	Route::resource('/admin/categories','Admin\CategoryController');

	Route::get('/admin/categories/delete/{id}','Admin\CategoryController@destroy');

	Route::post('/admin/categories/update_status','Admin\CategoryController@update_status')->name('categories_update_status');

	Route::post('/admin/categories/delete_selected','Admin\CategoryController@delete_selected')->name('categories_delete_selected');

	Route::resource('/admin/projects','Admin\ProjectController');

	Route::post('/admin/projects/delete_selected','Admin\ProjectController@delete_selected')->name('projects_delete_selected');

	Route::get('/admin/projects/delete/{id}','Admin\ProjectController@destroy');

	Route::resource('/admin/users','Admin\UserController');

	Route::get('/admin/users/delete/{id}','Admin\UserController@destroy');

	Route::post('/admin/users/update_status','Admin\UserController@update_status')->name('users_update_status');

	Route::post('/admin/users/delete_selected','Admin\UserController@delete_selected')->name('users_delete_selected');

	Route::resource('/admin/emails','Admin\EmailController');

	Route::resource('/admin/settings','Admin\SettingController');

	Route::post('/admin/settings/add','Admin\SettingController@create')->name('settings_add');
});