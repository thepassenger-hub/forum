<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Filters\ThreadFilters;
use App\Events\ThreadCreated;

class Thread extends Model
{
    protected $guarded = [];
    
    protected $hidden = ['user_id', 'channel_id'];

    protected $events = [
        'created' => ThreadCreated::class,
        'deleted' => ThreadCreated::class
    ];
    
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
