<?php

Auth::routes();

Route::group(['prefix' => 'api/v1'], function () {

    Route::get('/', 'HomeController@start')
        ->name('api.start');

});

Route::get('/{subs?}', 'HomeController@index')
    ->name('home')
    ->middleware(['auth'])
    ->where(['subs' => '.*']);
