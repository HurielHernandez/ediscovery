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


//Home
Route::get('/','HomeController@index');

Route::get('/home', 'HomeController@index');

//Cases
Route::group(['prefix' => '/cases'], function () {

	Route::get('/', 'CaseController@index');

	Route::get('/{id}', 'CaseController@show');

});

//Files
Route::group(['prefix' => '/cases/{id}/files'], function () {

	Route::get('/', 'FileController@index');

	Route::get('/{file}/download', 'FileController@download');

});


//User
Auth::routes();

Route::get('/register/confirm/{confirm_id}', 'Auth\RegisterController@confirm');

Route::get('/profile', '\\TCG\\Voyager\\Http\\Controllers\\VoyagerController@profile');

Route::get('/logout', '\\TCG\\Voyager\\Http\\Controllers\\VoyagerController@logout');


//Routes only in office
Route::group(['middleware' => 'fw-allow-wl'], function (){
	
  	Route::get('/admin/login', '\\TCG\\Voyager\\Http\\Controllers\\VoyagerAuthController@login')->name('voyager.login');
    Route::post('/admin/login', '\\TCG\\Voyager\\Http\\Controllers\\VoyagerAuthController@postLogin');
	
	//Routes where admin is logged in
	Route::group(['middleware' => 'admin.user'], function () {

		Route::delete('/admin/users/{id}', 'AdminUserController@delete');

		//Settings routes
		Route::group(['prefix' => 'admin'], function () {
	    	Voyager::routes();
		});




		//Case Routes
		Route::group(['prefix' => 'admin/cases'], function () {

			Route::get('/', ['uses' => 'AdminCaseController@index']);

			Route::get('/create', 'AdminCaseController@create');

			Route::post('/create', 'AdminCaseController@post');

			Route::get('/{caseId}/edit', 'AdminCaseController@edit');

			Route::get('/{id}', 'AdminCaseController@show');

			Route::delete('/{id}', 'AdminCaseController@destroy');

			Route::put('/{id}/update', 'AdminCaseController@update');
		});

		//File Routes
		Route::group(['prefix' => '/admin/cases/{id}/files'], function () {

			Route::get('/', 'AdminFileController@index');

			Route::get('/upload', 'AdminFileController@upload');

			Route::post('/upload', 'AdminFileController@store');

			Route::get('/{file}/download', 'AdminFileController@download');

			Route::delete('/{file}/', 'AdminFileController@destroy');

			Route::get('/{file}/edit', 'AdminFileController@edit');

			Route::post('/{file}/edit', 'AdminFileController@update');
		});

		//User-Case Access
		Route::group(['prefix' => '/admin/cases/{id}/users'], function(){

			Route::get('/', 'AdminUserCaseController@index');

			Route::get('/add', 'AdminUserCaseController@add');

			Route::get('/{user}/edit', 'AdminUserCaseController@edit');

			Route::patch('/{user}/edit', 'AdminUserCaseController@update');

			Route::delete('/{user}/', 'AdminUserCaseController@revoke');

			Route::get('/{user}/grant', 'AdminUserCaseController@grant');

			Route::get('/{userId}/files/{fileId}/increase', 'AdminUserCaseController@increase');

			Route::get('/{userId}/files/{fileId}/decrease', 'AdminUserCaseController@decrease');

		});
	});

});



