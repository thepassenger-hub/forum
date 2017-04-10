<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Channel;
use \App\User;
use \App\Reply;

class Thread extends Model
{
    protected $guarded = [];
    
    protected $hidden = ['user_id', 'channel_id'];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function addReply($body)
    {
        $this->replies()->create($body);
    }
}
