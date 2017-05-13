<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\User;
use \App\Thread;
use \App\Reply;


class AdminControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;

    protected function setUp()
    {
        parent::setUp();
        $this->admin = User::first();
    }

    public function testAdminCanDeleteThreads()
    {
        $thread = Thread::inRandomOrder()->first();
        $this->actingAs($this->admin)
            ->delete("/admin/threads/{$thread->slug}")
            ->assertStatus(200);
        $this->assertNull(Thread::find($thread->id));
    }

    public function testAdminCanDeleteReplies()
    {
        $reply = Reply::inRandomOrder()->first();
        $this->actingAs($this->admin)
            ->delete("/admin/replies/{$reply->id}")
            ->assertStatus(200);
        $this->assertNull(Reply::find($reply->id));
    }

}
