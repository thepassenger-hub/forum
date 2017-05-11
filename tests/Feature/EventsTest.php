<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Http\UploadedFile;
use  Illuminate\Support\Facades\Event;

use \App\Events\UserCreated;
use \App\Events\UserDeleted;
use \App\Events\ThreadCreated;
use \App\Events\ReplyCreated;
use \App\Events\ProfileUpdated;

use \App\User;
use \App\Thread;
use \App\Reply;



class EventsTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp()
    {
        parent::setUp();
        $this->user = User::has('threads')->inRandomOrder()->first();
        
    }
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserCreatedFiresEvent()
    {
        Event::fake();

        $user = factory(User::class)->create();
        Event::assertDispatched(UserCreated::class, function ($e) use ($user) {
            return $e->user_id === $user->id;
        });

    }

    public function testUserDeletedFiresEvent()
    {
        Event::fake();

        $user = User::inRandomOrder()->first();
        $user->delete();

        Event::assertDispatched(UserDeleted::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    public function testThreadCreatedFiresEvent()
    {
        Event::fake();

        $thread = factory(Thread::class)->create();
        Event::assertDispatched(ThreadCreated::class, function ($e) use ($thread) {
            return $e->thread === $thread;
        });
    }

    public function testThreadDeletedFiresEvent()
    {
        Event::fake();

        $thread = Thread::inRandomOrder()->first();
        $thread->delete();

        //The event is threadcreated because the actions performed are the same.
        // Name may be misleading, should refactor it.
        Event::assertDispatched(ThreadCreated::class, function ($e) use ($thread) {
            return $e->thread === $thread;
        });
    }

    public function testThreadUpdatedFiresEvent()
    {
        Event::fake();

        $thread = Thread::inRandomOrder()->first();
        $thread->update(['body' => 'The new updated thread body.']);

        //The event is threadcreated because the actions performed are the same.
        // Name may be misleading, should refactor it.
        Event::assertDispatched(ThreadCreated::class, function ($e) use ($thread) {
            return $e->thread === $thread;
        });
    }

    public function testReplyCreatedFiresEvent()
    {
        Event::fake();

        $reply = factory(Reply::class)->create();
        Event::assertDispatched(ReplyCreated::class, function ($e) use ($reply) {
            return $e->reply === $reply;
        });
    }

    public function testReplyDeletedFiresEvent()
    {
        Event::fake();

        $reply = Reply::inRandomOrder()->first();
        $reply->delete();

        //The event is ReplyCreated because the actions performed are the same.
        // Name may be misleading, should refactor it.
        Event::assertDispatched(ReplyCreated::class, function ($e) use ($reply) {
            return $e->reply === $reply;
        });
    }

    public function testReplyUpdatedFiresEvent()
    {
        Event::fake();

        $reply = Reply::inRandomOrder()->first();
        $reply->update(['body' => 'My new reply body updated']);

        //The event is ReplyCreated because the actions performed are the same.
        // Name may be misleading, should refactor it.
        Event::assertDispatched(ReplyCreated::class, function ($e) use ($reply) {
            return $e->reply === $reply;
        });
    }

    public function testProfileUpdatedFiresEvent()
    {
        Event:: fake();
        
        $profile = $this->user->profile;
        $profile->update(['name' => 'My new name changed']);

        Event::assertDispatched(ProfileUpdated::class, function ($e){
            return $e->username === $this->user->username;
        });
    }

    public function testProfileControllerUpdateMethodFiresUpdateEvent()
    {
        Event:: fake();

        $this->actingAs($this->user)->patch('/profile', [
            'name' => 'John',
            'bio' => 'John doe from test class.',
            'location' => 'Unknown'
        ]);

        Event::assertDispatched(ProfileUpdated::class, function ($e) {
            return $e->username === $this->user->username;
        });
    }

    public function testProfileControllerUploadAvatarFiresUpdateEvent()
    {
        Event:: fake();
        \Storage::fake('local');
        
        $this->actingAs($this->user)->json('POST', 'profile/avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        Event::assertDispatched(ProfileUpdated::class, function ($e) {
            return $e->username === $this->user->username;
        });
    }

    public function testThreadControllerUpdateMethodFiresUpdateEvent()
    {
        Event:: fake();
        $thread = $this->user->threads()->inRandomOrder()->first();
        $this->actingAs($this->user)->patch("threads/{$thread->slug}", [
            'body' => 'The new random body.',
        ]);

        Event::assertDispatched(ThreadCreated::class, function ($e) use ($thread) {
            return $e->thread->id === $thread->id;
        });
    }

    public function testThreadControllerDestroyMethodFiresDeleteEvent()
    {
        Event:: fake();
        $thread = $this->user->threads()->inRandomOrder()->first();
        
        $this->actingAs($this->user)->delete("threads/{$thread->slug}");

        Event::assertDispatched(ThreadCreated::class, function ($e) use ($thread) {
            return $e->thread->id === $thread->id;
        });
    }

    public function testThreadsControllerStoreMethodFiresCreateEvent()
    {
        Event:: fake();
        $channel = \App\Channel::inRandomOrder()->first();
        
        $this->actingAs($this->user)->post("channels/{$channel->slug}/threads", [
            'title' => 'New thread title',
            'body' => 'New thread body'
        ]);

        $thread = $channel->threads()->where('title', 'New thread title')->first();
        Event::assertDispatched(ThreadCreated::class, function ($e) use ($thread) {
            return $e->thread->id === $thread->id;
        });
    }

    public function testRepliesControllerUpdateMethodFiresUpdateEvent()
    {
        Event::fake();
        $reply = Reply::inRandomOrder()->first();
        $user = $reply->creator()->first();
        $this->actingAs($user)->patch("replies/{$reply->id}", [
            'body' => 'The new reply body updated.'
        ]);

        Event::assertDispatched(ReplyCreated::class, function ($e) use ($reply) {
            return $e->reply->id === $reply->id;
        });
    }

    public function testReplyControllerDestroyMethodFiresDeleteEvent()
    {
        Event:: fake();
        $reply = $this->user->replies()->inRandomOrder()->first();
        
        $this->actingAs($this->user)->delete("replies/{$reply->id}");

        Event::assertDispatched(ReplyCreated::class, function ($e) use ($reply) {
            return $e->reply->id === $reply->id;
        });
    }

    public function testReplyControllerStoreMethodFiresCreateEvent()
    {
        Event:: fake();
        $thread = $this->user->threads()->inRandomOrder()->first();
        
        $this->actingAs($this->user)->post("threads/{$thread->slug}/replies",[
            'body' => 'My new reply'
        ]);

        $reply = $thread->replies()->where('body', 'My new reply')->first();
        Event::assertDispatched(ReplyCreated::class, function ($e) use ($reply) {
            return $e->reply->id === $reply->id;
        });
    }
}
