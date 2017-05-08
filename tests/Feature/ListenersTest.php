<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\CacheManager;

use \App\Events\ProfileUpdated;
use \App\Events\ReplyCreated;
use \App\Events\ThreadCreated;
use \App\Events\UserCreated;

use \App\Listeners\ClearCacheProfile;
use \App\Listeners\ClearCacheReply;
use \App\Listeners\ClearCacheThread;
use \App\Listeners\CreateProfile;


class ListenersTest extends TestCase
{

    public function testProfileUpdatedEventTriggersCorrectListener()
    {
        $listener = \Mockery::mock(ClearCacheProfile::class);
        $listener->shouldReceive('handle')->once();
        
        $this->app->instance(ClearCacheProfile::class, $listener);
        

        event(new ProfileUpdated(\App\Profile::first()));
    }

    public function testReplyCreatedEventTriggersCorrectListener()
    {


        $listener = \Mockery::mock('ClearCacheReply');
        $listener->shouldReceive('handle')->once();
        
        $this->app->instance(ClearCacheReply::class, $listener);
        
        event(new ReplyCreated(\App\Reply::first()));
    }

    public function testThreadCreatedEventTriggersCorrectListener()
    {
        $listener = \Mockery::mock('ClearCacheThread');
        $listener->shouldReceive('handle')->once();
        
        $this->app->instance(ClearCacheThread::class, $listener);
        
        event(new ThreadCreated(\App\Thread::first()));
    }

    public function testUserCreatedEventTriggersCorrectListener()
    {
        $listener = \Mockery::mock('CreateProfile');
        $listener->shouldReceive('handle')->once();
        
        $this->app->instance(CreateProfile::class, $listener);
        
        event(new UserCreated(\App\User::first()));
    }
}
