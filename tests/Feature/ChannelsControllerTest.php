<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Channel;

class ChannelsControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexMethodReturnsAllChannels()
    {
        $this->withoutMiddleware();
        $response = $this->get('/channels');
        $channels = Channel::all();
        $this->assertEquals(response($channels,200)->getContent(), $response->getContent());
    }
}
