<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\User;
use \App\Channel;
use \App\Thread;
use \App\Reply;


class RepliesControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $channel;
    protected $thread;
    protected $reply;

    protected function setUp()
    {
        parent::setUp();
        $this->user = User::has('replies')->inRandomOrder()->first();
        $this->channel = Channel::inRandomOrder()->first();
        $this->thread = Thread::inRandomOrder()->first();
        $this->reply = $this->user->replies()->inRandomOrder()->first();
        
    }

    public function testIndexMethodReturnsAllReplies()
    {
        $response = $this->get('replies');
        $response->assertStatus(200);
        $this->assertEquals(Reply::with('creator')->latest()->get()->toJson(), $response->getContent());

    }
    public function testStoreMethodCreatesReplyModel()
    {
        $this->actingAs($this->user)->post("threads/{$this->thread->slug}/replies",[
            'body' => 'My new reply'
        ])->assertStatus(200);

        $this->assertInstanceOf(Reply::class, $this->thread->replies()->where('body', 'My new reply')->first());
    }

    public function testBannedUserCantStoreThread()
    {
        $user = User::has('threads')->inRandomOrder()->first();
        $thread = $user->threads()->inRandomOrder()->first();
        $user->banFor(10); 
        $response = $this->actingAs($user)->post("threads/{$thread->slug}/replies", [
            'body' => 'My new reply'
        ]) -> assertStatus(403);

        $this->assertEmpty(Reply::where('body', 'My new reply')->get());
    }

    public function testUpdateMethodCorrectlyUpdatesModel()
    {
        $this->actingAs($this->user)->patch("replies/{$this->reply->id}", [
            'body' => 'My new body.'
        ])->assertStatus(200);

        $this->assertEquals(Reply::find($this->reply->id)->body, 'My new body.');
    }

    public function testBannedUserCantUpdateReplies()
    {
        $user = User::has('replies')->inRandomOrder()->first();
        $reply = $user->replies()->inRandomOrder()->first();
        $user->banFor(10); 
        $response = $this->actingAs($user)->patch("replies/{$reply->id}", [
            'body' => 'My new body.'
        ])->assertStatus(403);

        $this->assertNotEquals(Reply::find($reply->id)->body, 'My new body.');
    }   

    public function testDestroyMethodCorrectlyDeletsModel()
    {
        $this->actingAs($this->user)->delete("replies/{$this->reply->id}")->assertStatus(200);

        $this->assertNull(Reply::find($this->reply->id));

        //Cannot delete other user's replies

        $reply_id = Reply::where('user_id', '!=', $this->user->id)->first()->id;
        $this->actingAs($this->user)->delete("replies/{$reply_id}")->assertStatus(403);
        $this->assertInstanceOf(Reply::class,Reply::find($reply_id));
    }
}
