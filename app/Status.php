<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Status extends Model
{
    protected $fillable = [
        'user_id', 'status', 'until'
    ];

    protected $hidden = [
        'user_id', 'created_at', 'updated_at', 'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
