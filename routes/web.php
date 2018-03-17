<?php

Auth::routes();

Route::group(['prefix' => 'api/v1'], function () {

    Route::get('/', 'HomeController@start')
        ->name('api.start');

    Route::get('/search/{query}', 'SearchController@search')
        ->name('api.search');


});

Route::get('/{subs?}', 'HomeController@index')
    ->name('home')
    ->middleware(['auth'])
    ->where(['subs' => '.*']);
