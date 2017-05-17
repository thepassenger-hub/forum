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
Route::get('channels', 'ChannelsController@index')->middleware('cache:channels');
Route::get('threads', 'ThreadsController@index')->middleware('cache:threads');
Route::get('channels/{channel}/threads', 'ThreadsController@index')->middleware('cache:threads');
Route::get('channels/{channel}/{thread}', 'ThreadsController@show')->middleware('cache:threadWithReplies');
Route::get('profile/{user}', 'ProfileController@show')->middleware('cache:profile');
Route::get('replies', 'RepliesController@index');
Route::get('test', 'ThreadsController@test');
Route::patch('profile', 'ProfileController@update')->middleware('auth');
Route::post('profile/avatar', 'ProfileController@uploadAvatar')->middleware('auth');

Route::post('threads/{thread}/replies', 'RepliesController@store')->middleware('auth');
Route::post('channels/{channel}/threads', 'ThreadsController@store')->middleware('auth');
Route::patch('user/password', 'UsersController@update')->middleware('auth');
Route::get('users', 'UsersController@index');
Route::delete('replies/{reply}', 'RepliesController@destroy')->middleware('can:update,reply');
Route::patch('replies/{reply}', 'RepliesController@update')->middleware('can:update,reply');
Route::patch('threads/{thread}', 'ThreadsController@update')->middleware('can:update,thread');
Route::delete('threads/{thread}', 'ThreadsController@destroy')->middleware('can:update,thread');

Route::delete('admin/threads/{thread}', 'AdminController@deleteThread')->middleware('admin');
Route::delete('admin/replies/{reply}', 'AdminController@deleteReply')->middleware('admin');
Route::patch('admin/users/{user}/ban', 'AdminController@banUser');
Route::patch('admin/users/{user}/enable', 'AdminController@enableUser');





