<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Events\ProfileUpdated;
use \App\User;

class Profile extends Model
{
    protected $fillable = ['name', 'bio', 'location', 'avatar'];
    
    protected $events = [
        'updated' => ProfileUpdated::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
