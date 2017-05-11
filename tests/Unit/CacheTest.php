<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \Illuminate\Cache\TaggedCache;

use \App\Events\ReplyCreated;
use \App\Events\ThreadCreated;
use \App\Events\ProfileUpdated;


class CacheTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseTransactions;

    public function testClearCacheReplyTriggersCacheMethods()
    {
        $obj = \Mockery::mock('\Illuminate\Cache\TaggedCache');
        $obj->shouldReceive('forget')->twice();
        $obj->shouldReceive('flush')->once();
        
        \Cache::shouldReceive('tags')
            ->times(3)
            ->andReturn($obj);
        event(new ReplyCreated(factory(\App\Reply::class)->make()));
    }

    public function testClearCacheThreadTriggersCacheMethods()
    {
        
        $obj = \Mockery::mock('\Illuminate\Cache\TaggedCache');
        $obj -> shouldReceive('forget')
            ->twice();
        $obj -> shouldReceive('flush')
            ->once();

        \Cache::shouldReceive('tags')
            ->times(3)
            ->andReturn($obj);
        event(new ThreadCreated(factory(\App\Thread::class)->make()));
    }

    public function testClearCacheProfileTriggersCacheMethods()
    {
        $profile = \Mockery::mock('\App\Profile');
        $profile -> shouldReceive('user') -> once() -> andReturn(factory(\App\User::class)->make());
        \Cache::shouldReceive('forget')
            -> once();
        event(new ProfileUpdated($profile));
    }
}
