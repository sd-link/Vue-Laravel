<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'dummy-module'], function () {
	Route::get('/','ModuleController@home')->name('dummy-module.home');

	Route::get('/frontend','ModuleController@frontend')->name('dummy-module.frontend');

	Route::group(['middleware'=>'setTheme:Admin_Theme'], function () {
		Route::get('/backend','ModuleController@backend')->name('dummy-module.backend');
	});


});
