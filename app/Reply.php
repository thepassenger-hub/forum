<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Events\ReplyCreated;
use \App\Thread;
use \App\User;

class Reply extends Model
{

    protected $guarded = [];

    protected $events = [
        'created' => ReplyCreated::class,
        'deleted' => ReplyCreated::class,
        'updated' => ReplyCreated::class
        
    ];

    /**
     * A reply belongs to a thread.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo 
     **/
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * A reply belongs to a user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo 
     **/
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
