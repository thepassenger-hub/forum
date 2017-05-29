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

// Misc
Route::get('/', function () {
    return view('layouts.master');
})->name('home');

Route::get('/sessionStatus', function() {
    return ['user' => Auth::user() ? Auth::user()->load('profile') : null];
});

// Users
Auth::routes();
Route::patch('user/password', 'UsersController@update')->middleware('auth');
Route::get('users', 'UsersController@index')->middleware('cache:users');

// Channels
Route::get('channels', 'ChannelsController@index')->middleware('cache:channels');

// Threads
Route::get('threads', 'ThreadsController@index')->middleware('cache:threads');
Route::get('channels/{channel}/threads', 'ThreadsController@index')->middleware('cache:threads');
Route::get('channels/{channel}/{thread}', 'ThreadsController@show')->middleware('cache:threadWithReplies');
Route::post('channels/{channel}/threads', 'ThreadsController@store')->middleware('auth', 'active');
Route::patch('threads/{thread}', 'ThreadsController@update')->middleware('can:update,thread', 'active');
Route::delete('threads/{thread}', 'ThreadsController@destroy')->middleware('can:update,thread');

// Replies
Route::get('replies', 'RepliesController@index')->middleware('cache:replies');
Route::post('threads/{thread}/replies', 'RepliesController@store')->middleware('auth', 'active');
Route::delete('replies/{reply}', 'RepliesController@destroy')->middleware('can:update,reply');
Route::patch('replies/{reply}', 'RepliesController@update')->middleware('can:update,reply', 'active');

// Profiles

Route::get('profile/{user}', 'ProfileController@show')->middleware('cache:profile');
Route::patch('profile', 'ProfileController@update')->middleware('auth');
Route::post('profile/avatar', 'ProfileController@uploadAvatar')->middleware('auth');

// Admin area Routes.
Route::group(['middleware' => 'admin'], function () {
    Route::delete('admin/threads/{thread}', 'AdminController@deleteThread');
    Route::delete('admin/replies/{reply}', 'AdminController@deleteReply');
    Route::patch('admin/users/{user}/ban', 'AdminController@banUser');
    Route::patch('admin/users/{user}/enable', 'AdminController@enableUser');
});





