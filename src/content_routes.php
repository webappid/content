<?php

/*
    Route for file
*/

if($_SERVER["PHP_SELF"]=="vendor/phpunit/phpunit/phpunit"){
    session(["content_test" => "true", "user_id" => "1"]);
    Route::group(['prefix' => 'test'], function () {
        
        Route::group(['prefix' => 'file'], function () {
            $routeCode = "file";
            Route::get('{name}', 'WebAppId\Content\Controllers\FileController@index')->name($routeCode);
            Route::get('{name}/{size}', 'WebAppId\Content\Controllers\FileController@show')->name($routeCode . '.resize');
            Route::post('upload/{path}', 'WebAppId\Content\Controllers\FileController@create')->name($routeCode . '.upload');
        });
        Route::group(['prefix' => 'category'], function () {
            $routeCode = 'category';
            Route::get('/', 'WebAppId\Content\Controllers\CategoryTest@show')->name($routeCode . '_list');
            Route::get('/create', 'WebAppId\Content\Controllers\CategoryTest@create')->name($routeCode . '_create');
            Route::post('/store', 'WebAppId\Content\Controllers\CategoryTest@store')->name($routeCode . '_store');
            Route::get('/edit/{id}', 'WebAppId\Content\Controllers\CategoryTest@edit')->name($routeCode . '_edit');
            Route::post('/update/{id}', 'WebAppId\Content\Controllers\CategoryTest@update')->name($routeCode . '_update');
            Route::get('/delete/{id}', 'WebAppId\Content\Controllers\CategoryTest@destroy')->name($routeCode . '_delete');
        });

        Route::group(['prefix' => 'content'], function () {
            $routeCode = 'content';
            Route::get('/', 'WebAppId\Content\Controllers\ContentTest@show')->name($routeCode . '_list');
            Route::get('/create', 'WebAppId\Content\Controllers\ContentTest@create')->name($routeCode . '_create');
            Route::post('/store', 'WebAppId\Content\Controllers\ContentTest@store')->name($routeCode . '_store');
            Route::get('/edit/{code}', 'WebAppId\Content\Controllers\ContentTest@edit')->name($routeCode . '_edit');
            Route::post('/update/{code}', 'WebAppId\Content\Controllers\ContentTest@update')->name($routeCode . '_update');
            Route::get('/delete/{code}', 'WebAppId\Content\Controllers\ContentTest@destroy')->name($routeCode . '_delete');
            Route::get('/detail/{code}', 'WebAppId\Content\Controllers\ContentTest@detail')->name($routeCode . '_detail');
        });
    });
}