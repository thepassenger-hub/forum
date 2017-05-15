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
use \App\Events\ThreadDeleted;
use \App\Events\UserCreated;
use \App\Events\UserDeleted;


use \App\Listeners\ClearCacheProfile;
use \App\Listeners\ClearCacheReply;
use \App\Listeners\ClearCacheThread;
use \App\Listeners\UserCreatedListener;
use \App\Listeners\DeleteProfile;
use \App\Listeners\ThreadDeletedListener;



class ListenersTest extends TestCase
{
    use DatabaseTransactions;
    
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
        $listener = \Mockery::mock('UserCreatedListener');
        $listener->shouldReceive('handle')->once();
        
        $this->app->instance(UserCreatedListener::class, $listener);
        
        event(new UserCreated(\App\User::first()));
    }

    public function testUserCreatedEventCreatesTheProfile()
    {
        $user = \Mockery::mock(\App\User::class);
        $user->shouldReceive('getAttribute')->with('id')->once()->andReturn(1000);
        $user->shouldReceive('getAttribute')->with('profile')->once()->andReturn(\Mockery::mock(\App\Profile::class));
        
        event(new UserCreated($user));
        $this->assertNotNull($user->profile);
        
    }

    public function testUserCreatedEventCreatesTheStatus()
    {
        $user = \Mockery::mock(\App\User::class);
        $user->shouldReceive('getAttribute')->with('id')->once()->andReturn(1000);
        $user->shouldReceive('getAttribute')->with('status')->once()->andReturn(\Mockery::mock(\App\Status::class));
        
        event(new UserCreated($user));
        $this->assertNotNull($user->status);
    }
    public function testUserDeletedEventTriggersCorrectListener()
    {
        $listener = \Mockery::mock('DeleteProfile');
        $listener->shouldReceive('handle')->once();
        
        $this->app->instance(DeleteProfile::class, $listener);
        
        event(new UserDeleted(\App\User::inRandomOrder()->first()));
    }

    public function testUserDeletedAlsoDeletesProfile()
    {
        $user = \App\User::inRandomOrder()->first();

        event(new UserDeleted($user));
        $this->assertNull($user->profile);
    }

    public function testThreadDeletedTriggersCorrectListener()
    {
        $listener = \Mockery::mock('ThreadDeletedListener');
        $listener->shouldReceive('handle')->once();
        
        $this->app->instance(ThreadDeletedListener::class, $listener);
        
        event(new ThreadDeleted(\App\Thread::inRandomOrder()->first()));
    }

    public function testThreadDeletedAlsoDeletesItsReplies()
    {
        $thread = \App\Thread::inRandomOrder()->first();

        event(new ThreadDeleted($thread));
        $this->assertEmpty($thread->replies);
    }
}
