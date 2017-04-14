<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
}
