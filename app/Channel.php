<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Thread;

class Channel extends Model
{
    protected $guarded = [];

    /**
    * Get the route key for the model.
    *
    * @return string
    */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * A channel has many threads.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany 
     **/
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * Add a thread belonging to this channel.
     *
     * @param array $thread { 
     *     Associative array containing the thread fields.
     *     @type string $title The thread title.
     *     @type string $slug The thread slug. Must be unique.
     *     @type string $body The thread body message.
     *     @type string|int $user_id The thread creator id.
     *     @type string|int $channel_id The thread channel id.
     *     @type string $last_reply Timestamp not required.
     * }
     * @return void 
     **/
    public function addThread($fields)
    {
        return $this->threads()->create($fields);
    }
}
