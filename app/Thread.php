<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Filters\ThreadFilters;
use App\Events\ThreadCreated;
use App\Events\ThreadDeleted;

class Thread extends Model
{
    protected $guarded = [];
    
    protected $hidden = ['user_id', 'channel_id'];

    protected $events = [
        'created' => ThreadCreated::class,
        'deleted' => ThreadDeleted::class,
        'updated' => ThreadCreated::class
    ];
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    /**
     * A thread belongs to a channel.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo 
     **/
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * A thread belongs to a user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo 
     **/
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A thread has many replies.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany 
     **/
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Add a reply belonging to this thread.
     *
     * @param array $reply { 
     *     Associative array containing the reply message and creator's id.
     *     @type string $body The reply body message.
     *     @type string|int $user_id The reply creator id.
     * }
     * @return void 
     **/
    public function addReply($reply)
    {
        $this->replies()->create($reply);
        $this->update(['last_reply' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')]);
    }

    /**
     * Apply all relevant thread filters.
     *
     * @param  Builder       $query
     * @param  ThreadFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }
}
