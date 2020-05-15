<?php

/*
    Route for file
*/

if (isset($_SERVER["PHP_SELF"])) {
    if (strpos($_SERVER["PHP_SELF"], 'vendor/phpunit/phpunit/phpunit') != false || $_SERVER["PHP_SELF"] == 'vendor/phpunit/phpunit/phpunit') {
        session(["content_test" => "true", "user_id" => "1"]);
        Route::group(['prefix' => 'test'], function () {

            Route::group(['prefix' => 'content'], function () {
                $routeCode = 'content';
                Route::get('/', 'WebAppId\Content\Controllers\ContentTest@show')->name($routeCode . '_list');
                Route::get('/create', 'WebAppId\Content\Controllers\ContentTest@create')->name($routeCode . '_create');
                Route::post('/store', 'WebAppId\Content\Controllers\ContentTest@store')->name($routeCode . '_store');
                Route::get('/edit/{code}', 'WebAppId\Content\Controllers\ContentTest@edit')->name($routeCode . '_edit');
                Route::post('/update/{code}', 'WebAppId\Content\Controllers\ContentTest@update')->name($routeCode . '_update');
                Route::get('/delete/{code}', 'WebAppId\Content\Controllers\ContentTest@destroy')->name($routeCode . '_delete');
                Route::get('/detail/{code}', 'WebAppId\Content\Controllers\ContentTest@detail')->name($routeCode . '_detail');
                Route::post('/gallery/{path}', 'WebAppId\Content\Controllers\ContentGalleryTest@store')->name($routeCode . '_list');
            });
        });
    }
}

Route::group(['prefix' => 'file'], function () {
    $routeCode = "file";
    Route::get('{name}', WebAppId\Content\Controllers\FileOriController::class)->name($routeCode . '.ori');
    Route::get('{name}/{size}', WebAppId\Content\Controllers\FileShowController::class)->name($routeCode . '.resize');
    Route::post('upload/{path}', WebAppId\Content\Controllers\FileStoreController::class)->name($routeCode . '.store');
});