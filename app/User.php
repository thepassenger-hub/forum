<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \App\Events\UserCreated;
use \App\Events\UserDeleted;
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
        'password', 'remember_token', 'id'
    ];

     protected $events = [
        'created' => UserCreated::class,
        'deleted' => UserDeleted::class
    ];

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->limit(50)->latest();
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function createProfile($user_id)
    {
        Profile::create(['user_id' => $user_id]);
    }

    public function updatePassword($password)
    {
        $this->password = bcrypt($password);
        return $this->save();
    }
}
