<?php

/*
    Route for file
*/

if($_SERVER["PHP_SELF"]=="vendor/phpunit/phpunit/phpunit"){
    session(["content_test" => "true"]);
    Route::group(['prefix' => 'test'], function () {
        
        Route::group(['prefix' => 'file'], function () {
            $routeCode = "file";
            Route::get('{name}', 'WebAppId\Content\Controllers\FileController@index')->name($routeCode);
            Route::get('{name}/{size}', 'WebAppId\Content\Controllers\FileController@show')->name($routeCode . '.resize');
            Route::post('upload/{path}', 'WebAppId\Content\Controllers\FileController@create')->name($routeCode . '.upload');
        });
        Route::group(['prefix' => 'category'], function () {
            $routeCode = 'category';
            Route::get('/', 'WebAppId\Content\Controllers\CategoryController@show')->name($routeCode . '_list');
            Route::get('/create', 'WebAppId\Content\Controllers\CategoryController@create')->name($routeCode . '_create');
            Route::post('/store', 'WebAppId\Content\Controllers\CategoryController@store')->name($routeCode . '_store');
            Route::get('/edit/{id}', 'WebAppId\Content\Controllers\CategoryController@edit')->name($routeCode . '_edit');
            Route::post('/update/{id}', 'WebAppId\Content\Controllers\CategoryController@update')->name($routeCode . '_update');
            Route::get('/delete/{id}', 'WebAppId\Content\Controllers\CategoryController@destroy')->name($routeCode . '_delete');
        });

        Route::group(['prefix' => 'content'], function () {
            $routeCode = 'content';
            Route::get('/', 'WebAppId\Content\Controllers\References\ContentController@show')->name($routeCode . '_list');
            Route::get('/create', 'WebAppId\Content\Controllers\References\ContentController@create')->name($routeCode . '_create');
            Route::post('/store', 'WebAppId\Content\Controllers\References\ContentController@store')->name($routeCode . '_store');
            Route::get('/edit/{id}', 'WebAppId\Content\Controllers\References\ContentController@edit')->name($routeCode . '_edit');
            Route::post('/update/{id}', 'WebAppId\Content\Controllers\References\ContentController@update')->name($routeCode . '_update');
            Route::get('/delete/{id}', 'WebAppId\Content\Controllers\References\ContentController@destroy')->name($routeCode . '_delete');
        });
    });
}