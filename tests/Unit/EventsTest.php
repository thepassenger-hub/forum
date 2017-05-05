<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use  \Illuminate\Support\Facades\Event;
use \App\Events\UserCreated;
use \App\User;

class EventsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserCreatedFiresEvent()
    {
        $this->expectsEvents([UserCreated::class]);
        
        $user = User::create(['email' => 'lorem@ipsum.com', 'password' => 'asdf']);
        
        // dd($user);


    }
}
