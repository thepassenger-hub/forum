<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserCreated' => [
            'App\Listeners\UserCreatedListener',
        ],
        'App\Events\UserDeleted' => [
            'App\Listeners\UserDeletedListener',
        ],
        'App\Events\ReplyCreated' => [
            'App\Listeners\ClearCacheReply',
        ],
        'App\Events\ProfileUpdated' => [
            'App\Listeners\ClearCacheProfile',
        ],
        'App\Events\ThreadCreated' => [
            'App\Listeners\ClearCacheThread',
        ],
        'App\Events\ThreadDeleted' => [
            'App\Listeners\ThreadDeletedListener',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
