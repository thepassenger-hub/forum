<?php

namespace App\Listeners;

use App\Events\ReplyCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class ClearCacheReply
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
     * @param  ReplyCreated  $event
     * @return void
     */
    public function handle(ReplyCreated $event)
    {
        Cache::tags('profile')->forget('profile/' . $event->reply->creator->username);
        Cache::tags('threadWithReplies')->forget(
            'channels/' . $event->reply->thread->channel->slug . '/' . $event->reply->thread->slug);
        Cache::tags('threads')->flush();
        Cache::tags('replies')->flush();
        
        
    }
}
