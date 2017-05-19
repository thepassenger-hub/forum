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

    /** @var array $events Contains even to fire on specific actions.*/
    protected $events = [
        'created' => UserCreated::class,
        'deleted' => UserDeleted::class
    ];
    
    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
     * Every user has an account status.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne 
     **/
    public function status()
    {
        return $this->hasOne(Status::class);
    }
    
    /**
     * A user has many threads.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * A user has many replies.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function replies()
    {
        return $this->hasMany(Reply::class)->limit(50)->latest();
    }

    /**
     * Every user has a profile.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasOne 
     **/
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * A user can change his password.
     *
     * @param  string  $oldPassword  The previous user password.      
     * @param  string  $password     The new user password.
     * @return bool
     **/
    public function updatePassword($oldPassword, $password)
    {
        if (\Hash::check($oldPassword, $this->password)) {
            $this->password = bcrypt($password);
            return $this->save();
        }

        return false;
    }

    /**
     * Ban the user for X days. Then bust the cache for admin area.
     *
     * @param  int|string  $days  
     * @return void
     **/
    public function banForDays($days)
    {
        $this->status()->update([
            'status' => 'banned',
            'until' => \Carbon\Carbon::now()->addDays($days)
        ]);

        cache()->tags('users')->forget('users');
    }

    /**
     * Enable the user account. Then bust the cache for admin area.
     *
     * @return void
     **/
    public function enable()
    {
        $this->status()->update([
            'status' => 'active',
            'until' => null
        ]);

        cache()->tags('users')->forget('users');
        
    }

    /**
     * Check if the user account is active (not banned).
     *
     * @return bool
     **/
    public function isActive()
    {
        return $this->status->status === 'active';
    }
}
