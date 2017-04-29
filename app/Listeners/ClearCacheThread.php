<?php

namespace App\Listeners;

use App\Events\ReplyCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearCacheThread
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
     * @param  =ReplyCreated  $event
     * @return void
     */
    public function handle(ReplyCreated $event)
    {
        cache()->forget('thread_' . $event->reply->thread()->first()->id);
        cache()->forget('profile_' .$event->reply->creator()->first()->username);
    }
}
