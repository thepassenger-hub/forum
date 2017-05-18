<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \Illuminate\Cache\TaggedCache;

use \App\Events\ReplyCreated;
use \App\Events\ThreadCreated;
use \App\Events\ProfileUpdated;

use \App\User;

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
        $obj->shouldReceive('flush')->twice();
        
        \Cache::shouldReceive('tags')
            ->times(4)
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
        $profile -> shouldReceive('user') -> once() -> andReturn(factory(User::class)->make());
        \Cache::shouldReceive('forget')
            -> once();
        event(new ProfileUpdated($profile));
    }

    public function testUserCreatedFlushesUsersCache()
    {
        $obj = \Mockery::mock('\Illuminate\Cache\TaggedCache');
        $obj -> shouldReceive('forget')
            ->once()
            ->with('users')
            ->andReturn(true);

        \Cache::shouldReceive('tags')
            ->once()
            ->with('users')
            ->andReturn($obj);

        $user = factory(User::class)->create();
    }

    public function testBanningAndEnablingUserBustesCache()
    {
        $obj = \Mockery::mock('\Illuminate\Cache\TaggedCache');
        $obj -> shouldReceive('forget')
            ->twice()
            ->with('users')
            ->andReturn(true);

        \Cache::shouldReceive('tags')
            ->twice()
            ->with('users')
            ->andReturn($obj);

        $user = User::inRandomOrder()->first();
        $user->banForDays(10);
        $user->enable();
    }
}
