<?php

/*
    Route for file
*/
Route::group(['prefix' => 'file'], function () {
	Route::get('/', function () {
		return view('sample.upload');
	});
	$routeCode = "file";
	Route::get('{name}', 'Tools\FileController@index')->name($routeCode);
	Route::get('{name}/{size}', 'Tools\FileController@show')->name($routeCode.'.resize');
	Route::post('upload/{path}', 'Tools\FileController@create')->name($routeCode.'.upload');
});