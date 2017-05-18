<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \App\Events\UserCreated;
use \App\Events\UserDeleted;
use \App\Thread;
use \App\Reply;
use \App\Profile;
use \App\Status;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token', 'id', 'email'
    ];

     protected $events = [
        'created' => UserCreated::class,
        'deleted' => UserDeleted::class
    ];

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function status()
    {
        return $this->hasOne(Status::class);
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

    public function banForDays($days)
    {
        $this->status()->update([
            'status' => 'banned',
            'until' => \Carbon\Carbon::now()->addDays($days)
        ]);

        cache()->tags('users')->forget('users');
    }

    public function enable()
    {
        $this->status()->update([
            'status' => 'active',
            'until' => null
        ]);

        cache()->tags('users')->forget('users');
        
    }

    public function isActive()
    {
        return $this->status->status === 'active';
    }
}
