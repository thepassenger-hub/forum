<?php

namespace App\Listeners;

use App\Events\ThreadDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Cache;

class ThreadDeletedListener
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
     * @param  ThreadDeleted  $event
     * @return void
     */
    public function handle(ThreadDeleted $event)
    {
        Cache::tags('profile')->forget('profile/' . $event->thread->creator()->first()->username);
        Cache::tags('threadWithReplies')->forget(
            'channels/' . $event->thread->channel()->first()->slug . '/' . $event->thread->slug);   
        Cache::tags('threads')->flush();

        $event->thread->replies()->delete();
        
    }
}
