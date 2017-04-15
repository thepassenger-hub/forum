<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \App\Events\UserCreated;
use \App\Thread;
use \App\Reply;
use \App\Profile;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token', 'isAdmin', 'id'
    ];

     protected $events = [
        'created' => UserCreated::class,
    ];

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function createProfile($user_id)
    {
        Profile::create(['user_id' => $user_id]);
    }
}
