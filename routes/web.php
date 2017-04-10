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

Route::get('/home', 'HomeController@index');

Route::get('channels', 'ChannelsController@index');
Route::get('channels/{channel}/threads', 'ThreadsController@index');
Route::get('channels/{channel}/{thread}', 'ThreadsController@show');
Route::get('/sessionStatus', function() {
        return ['isLogged'=>Auth::check()];
    });
Route::post('channels/{channel}/threads', 'ThreadsController@store')->middleware('auth');
Route::post('channels/{channel}/{thread}/replies', 'RepliesController@store');



