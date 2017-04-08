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

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function addThread($fields)
    {
        $this->threads()->create($fields);
    }
}
