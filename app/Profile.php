<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Events\ProfileUpdated;
use \App\User;

class Profile extends Model
{
    protected $fillable = ['name', 'bio', 'location', 'avatar', 'user_id'];
    
    protected $events = [
        'updated' => ProfileUpdated::class
    ];

    /**
     * A profile belongs to a user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo 
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
