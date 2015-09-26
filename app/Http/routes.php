<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['uses' => 'Channel@index']);

Route::any('/channel/create', ['uses' => 'Channel@create_channel']);
Route::post('/channel/store', ['uses' => 'Channel@store','as' => 'store']);
Route::post('/channel/update', ['uses' => 'Channel@update','as' => 'update']);
Route::get('/channel/watch/{channel_slug}', ['uses' => 'Channel@view_channel']);
Route::get('/channel/update/{channel_slug}', ['uses' => 'Channel@update_channel']);
Route::get('/channel/status', ['uses' => 'Channel@getStatusesAjax']);
