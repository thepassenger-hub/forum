<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Filters\ThreadFilters;
// use \App\Channel;
// use \App\User;
// use \App\Reply;

class Thread extends Model
{
    protected $guarded = [];
    
    protected $hidden = ['user_id', 'channel_id'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
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
        try {
            $this->replies()->create($body);
            $this->update(['last_reply' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')]);
        }
        catch (Exception $e) {

        }
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
