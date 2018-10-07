<?php

/*
    Route for file
*/
Route::group(['prefix' => 'file'], function () {
	$routeCode = "file";
	Route::get('{name}', 'WebAppId\Content\Controllers\FileController@index')->name($routeCode);
	Route::get('{name}/{size}', 'WebAppId\Content\Controllers\FileController@show')->name($routeCode.'.resize');
	Route::post('upload/{path}', 'WebAppId\Content\Controllers\FileController@create')->name($routeCode.'.upload');
});