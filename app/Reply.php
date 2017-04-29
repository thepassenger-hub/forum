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
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
