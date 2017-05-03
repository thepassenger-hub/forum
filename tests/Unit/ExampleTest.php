<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\User;
use \App\Channel;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $user = User::first();
        $channel = Channel::first();
        $response = $this->actingAs($user)
                         ->post("/channels/{$channel->slug}/threads", 
            
                         ['title' => 'My title', 'body' => 'My body longer than 10 chars'])->assertStatus(200);
        $this->assertEquals(str_slug('My title', '-'), $response->getContent());
    }
}
