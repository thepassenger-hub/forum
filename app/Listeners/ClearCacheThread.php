<?php

namespace App\Listeners;

use App\Events\ThreadCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

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
     * @param  ThreadCreated  $event
     * @return void
     */
    public function handle(ThreadCreated $event)
    {
        Cache::tags('profile')->forget('profile/' . $event->thread->creator()->first()->username);
        Cache::tags('threadWithReplies')->forget(
            'channels/' . $event->thread->channel()->first()->slug . '/' . $event->thread->slug);   
        Cache::tags('threads')->flush();
    }
}
