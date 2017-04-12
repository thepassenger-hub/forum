<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.master');
});

Auth::routes();
Route::get('/sessionStatus', function() {
        return ['isLogged'=>Auth::check()];
    });

Route::get('/test', function() {
    session()->flush();
    session()->regenerate();
    return response(session()->all(), 200);
});

Route::get('/home', 'HomeController@index');

Route::get('channels', 'ChannelsController@index');
Route::get('channels/{channel}/threads', 'ThreadsController@index');
Route::get('channels/{channel}/{thread}', 'ThreadsController@show');
Route::post('threads/{thread}/replies', 'RepliesController@store')->middleware('auth');
Route::post('channels/{channel}/threads', 'ThreadsController@store')->middleware('auth');
Route::post('channels/{channel}/{thread}/replies', 'RepliesController@store');



