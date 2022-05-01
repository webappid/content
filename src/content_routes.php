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

Route::name('api.')->prefix('api')->group(function () {
    Route::group(['middleware' => ['auth:sanctum', 'auth.role']], function () {
        Route::name('content.')->prefix('content')->group(function () {
            Route::post('/', \WebAppId\Content\Controllers\Content\StoreController::class)->name('store');
            Route::put('/{slug}', \WebAppId\Content\Controllers\Content\UpdateController::class)->name('update');
        });
    });
    Route::name('content.')->prefix('content')->group(function () {
        Route::get('/list', \WebAppId\Content\Controllers\Content\ListController::class)->name('list');
        Route::get('/{slug}', \WebAppId\Content\Controllers\Content\DetailController::class)->name('detail');
    });
});
