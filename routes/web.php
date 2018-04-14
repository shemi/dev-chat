<?php

Auth::routes();

Route::group(['prefix' => 'api/v1'], function () {

    Route::get('/', 'HomeController@start')
        ->name('api.start');

    Route::get('/search/{query}', 'SearchController@search')
        ->name('api.search');


    Route::post('/conversations', 'ConversationController@store')
        ->name('api.conversation.create');

    Route::get('/conversations/{conversationId}', 'ConversationController@show')
        ->name('api.conversation.show');

    Route::put('/conversations/{conversationId}', 'ConversationController@update')
        ->name('api.conversation.update');


    Route::post('/conversations/{conversationId}/message', 'MessageController@store')
        ->name('api.conversation.message.send');

    Route::post('/conversations/{conversationId}/message/update-status', 'MessageController@updateStatuses')
        ->name('api.conversation.message.update-statuses');

});

Route::get('/{subs?}', 'HomeController@index')
    ->name('home')
    ->middleware(['auth'])
    ->where(['subs' => '.*']);
