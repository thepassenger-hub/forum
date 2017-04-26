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
        return ['user' => Auth::user() ? Auth::user()->load('profile') : null];
    });

Route::get('/home', 'HomeController@index');
// \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
//     var_dump($query->sql);
//     // var_dump($query->bindings);
//     // var_dump($query->time);
// });
Route::get('channels', 'ChannelsController@index');
Route::get('threads', 'ThreadsController@index');
Route::get('channels/{channel}/threads', 'ThreadsController@index');
Route::get('channels/{channel}/{thread}', 'ThreadsController@show');
Route::get('profile/{user}', 'ProfileController@show');
Route::get('replies/{user}', 'RepliesController@index');
Route::get('test', 'ThreadsController@test');
Route::post('profile', 'ProfileController@store')->middleware('auth');
Route::post('profile/avatar', 'ProfileController@uploadAvatar')->middleware('auth');

Route::post('threads/{thread}/replies', 'RepliesController@store')->middleware('auth');
Route::post('channels/{channel}/threads', 'ThreadsController@store')->middleware('auth');
Route::post('channels/{channel}/{thread}/replies', 'RepliesController@store')->middleware('auth');
Route::post('user/password', 'UsersController@update')->middleware('auth');



