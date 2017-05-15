<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \App\Profile;
use \App\Status;

class UserCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        Profile::create([
            'user_id' => $event->user_id,
            'location' => 'The Internet',
            'bio' => 'A new User who didn\'t change his bio yet!']);
        
        Status::create([
            'user_id' => $event->user_id,
            'status' => 'active'
        ]);
    }
}
