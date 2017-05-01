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
})->name('home');

Auth::routes();
Route::get('/sessionStatus', function() {
        return ['user' => Auth::user() ? Auth::user()->load('profile') : null];
    });

Route::get('/home', 'HomeController@index');
// \Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
//     // var_dump($query->sql);
//     // var_dump($query->bindings);
//     var_dump('TIME: ' . $query->time);
// });
Route::get('channels', 'ChannelsController@index');
Route::get('threads', 'ThreadsController@index')->middleware('cache:threads');
Route::get('channels/{channel}/threads', 'ThreadsController@index')->middleware('cache:threads');
Route::get('channels/{channel}/{thread}', 'ThreadsController@show')->middleware('cache:threadWithReplies');
Route::get('profile/{user}', 'ProfileController@show')->middleware('cache:profile');
// Route::get('replies/{user}', 'RepliesController@index');
Route::get('test', 'ThreadsController@test');
Route::post('profile', 'ProfileController@store')->middleware('auth');
Route::post('profile/avatar', 'ProfileController@uploadAvatar')->middleware('auth');

Route::post('threads/{thread}/replies', 'RepliesController@store')->middleware('auth');
Route::post('channels/{channel}/threads', 'ThreadsController@store')->middleware('auth');
// Route::post('channels/{channel}/{thread}/replies', 'RepliesController@store')->middleware('auth');
Route::post('user/password', 'UsersController@update')->middleware('auth');



