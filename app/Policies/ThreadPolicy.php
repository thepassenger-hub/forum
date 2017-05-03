<?php

namespace App\Policies;

use App\User;
use App\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
      * Determine if the authenticated user has permission to update a thread.
      *
      * @param  User  $user
      * @param  Thread $thread
      * @return bool
      */
     public function update(User $user, Thread $thread)
     {
         return $thread->creator()->first()->id == $user->id;
     }
}
