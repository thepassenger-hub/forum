<?php

namespace App\Listeners;

use App\Events\ProfileUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearCacheProfile
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
     * @param  ProfileUpdated  $event
     * @return void
     */
    public function handle($event)
    {
        cache()->forget('profile_' . $event->username);
    }
}
