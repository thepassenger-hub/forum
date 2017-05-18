<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\Thread;
use \App\Channel;
use \App\User;


class ThreadsControllerTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    protected $user;
    protected $channel;

    protected function setUp()
    {
        parent::setUp();
        $this->user = User::has('threads')->inRandomOrder()->first();
        $this->channel = Channel::inRandomOrder()->first();
        
    }
    public function testIndexMethodReturnsAllThreads()
    {   

        $response = $this->get('/threads');
        $threads = Thread::with('creator.profile', 'channel')->withCount('replies')
                         ->orderBy('last_reply', 'desc')->get();
        $this->assertEquals($threads->toJson(), $response->getContent());
    }

    public function testIndexMethodReturnsOnlyThreadsFromCorrectChannel()
    {
        $channel = Channel::inRandomOrder()->first();
        $response = $this->get("/channels/" . $channel->slug . "/threads");
        $threads = Thread::with('creator.profile', 'channel')->withCount('replies')
                         ->orderBy('last_reply', 'desc')->where('channel_id', $channel->id)->get();
        
        $this->assertEquals($threads->toJson(), $response->getContent());
    }

    public function testIndexMethodCanFilterByUser()
    {

        $user = User::inRandomOrder()->first();
        $response = $this->get("/threads?by={$user->username}");
        $threads = Thread::with('creator.profile', 'channel')->withCount('replies')
                         ->orderBy('last_reply', 'desc')->where('user_id', $user->id)->get();
        
        $this->assertEquals($threads->toJson(), $response->getContent());

    }

    public function testIndexMethodCanFilterByPopularAllTime()
    {
        $response = $this->get("/threads?popular=1");
        $threads = Thread::with('creator.profile', 'channel')->withCount('replies')
                         ->orderBy('replies_count', 'desc')->get();
        
        $this->assertEquals($threads->toJson(), $response->getContent());
    }

    public function testIndexMethodCanFilterByPopularLastMonth()
    {
        $response = $this->get("/threads?trending=1");
        $threads = Thread::with('creator.profile', 'channel')->withCount('replies')
                         ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                         ->orderBy('replies_count', 'desc')->get();
        
        $this->assertEquals($threads->toJson(), $response->getContent());
    }

    public function testIndexMethodCanFilterThreadsWhereIReplied()
    {
        $user = User::inRandomOrder()->first();
        $response = $this->actingAs($user)->get("/threads?contributed_to=1");
        $threads = Thread::with('creator.profile', 'channel')->withCount('replies')
                         ->whereHas('replies', function($query) use ($user){
                                $query->where('user_id', $user->id);
                            })
                         ->orderBy('last_reply', 'desc')->get();
        
        $this->assertEquals($threads->toJson(), $response->getContent());
    }

    public function testIndexMethodCanFilterByUserInputInSearchBar()
    {
        $response = $this->get("/threads?search=tempus+fugit");
        $terms = explode(' ', 'tempus fugit');
        $threads = Thread::with('creator.profile', 'channel')->withCount('replies')
                        ->whereHas('replies', function($query) use($terms){
                            foreach ($terms as $term) {
                                if($term) $query -> where('body', 'like', '%' . $term . '%');
                            }
                        })
                        ->orWhere(function($query) use($terms){
                            foreach ($terms as $term) {
                                if($term) $query -> where('body', 'like', "%{$term}%");
                            }
                        })
                        ->orWhere(function($query) use($terms){
                            foreach ($terms as $term) {
                                if($term) $query -> where('title', 'like', "%{$term}%");
                            }
                        })
                        ->orderBy('last_reply', 'desc')->get();
        
        $this->assertEquals($threads->toJson(), $response->getContent());
        
    }

    public function testShowMethodReturnsCorrectThread()
    {
        $channel = Channel::inRandomOrder()->first();
        $thread = Thread::where('channel_id', $channel->id)
            ->inRandomOrder()
            ->first()
            ->load('replies.creator.profile', 'creator.profile');
        $response = $this->get("/channels/{$channel->slug}/{$thread->slug}");

        $this->assertEquals($thread->toJson(), $response->getContent());
        
    }

    public function testStoreMethodCreatesThreadInDatabase()
    {
        $user = $this->user;
        $channel = $this->channel;
        // Validation failing returns a redirect
        $response = $this->actingAs($user)->post("channels/{$channel->slug}/threads", [
            'title' => null,
            'body' => null
        ])->assertStatus(302);

        // Successful validation creates new thread model and stores it in db.
        $response = $this->actingAs($user)->post("channels/{$channel->slug}/threads", [
            'title' => 'My new title',
            'body' => 'My body at least 10 chars long'
        ]) -> assertStatus(200);

        $this->assertInstanceOf(Thread::class, Thread::where('slug', str_slug('My new title'))->first());

        // If duplicate title slug is modified to {title}-N
        $response = $this->actingAs($user)->post("channels/{$channel->slug}/threads", [
            'title' => 'My new title',
            'body' => 'My body at least 10 chars long'
        ]) -> assertStatus(200);

        $this->assertInstanceOf(Thread::class, Thread::where('slug', str_slug('My new title 1'))->first());
    }

    public function testBannedUserCantStoreThread()
    {
       $user = User::inRandomOrder()->first();
       $user->banForDays(10); 
       $response = $this->actingAs($user)->post("channels/{$this->channel->slug}/threads", [
            'title' => 'My new title',
            'body' => 'My body at least 10 chars long'
        ]) -> assertStatus(403);

        $this->assertEmpty(Thread::where('title', 'My new title')->get());
    }

    public function testUpdateMethodDoesUpdateTheModel()
    {
        // User who isn't owner of thread can't update it.
        $thread = Thread::inRandomOrder()->where('user_id', '!=', $this->user->id)->first();
        $response = $this->actingAs($this->user)->patch("threads/{$thread->slug}", [
            'body' => 'My new body'
        ]);
        $response->assertStatus(403);

        //Validation failed returns redirect.
        $thread = Thread::inRandomOrder()->where('user_id', '=', $this->user->id)->first();
        $response = $this->actingAs($this->user)->patch("threads/{$thread->slug}", [
            'body' => 'My body'
        ]);
        $response->assertStatus(302);

        //Validation success leads to model update.
        $response = $this->actingAs($this->user)->patch("threads/{$thread->slug}", [
            'body' => 'My new body long enough'
        ])->assertStatus(200);

        $this->assertEquals(Thread::find($thread->id)->body, 'My new body long enough');

    }

    public function testBannedUserCantUpdateThread()
    {
        $user = User::has('threads')->inRandomOrder()->first();
        $user->banForDays(10); 
        $thread = Thread::inRandomOrder()->where('user_id', '=', $user->id)->first();
        $response = $this->actingAs($user)->patch("threads/{$thread->slug}", [
                'body' => 'My new body long enough'
            ])->assertStatus(403);

        $this->assertNotEquals(Thread::find($thread->id)->body, 'My new body long enough');
    }   

    public function testDestroyMethodDeletesModel()
    {
        //Random user cannot delete thread
        $thread = Thread::where('user_id', '!=', $this->user->id)->inRandomOrder()->first();
        $this->actingAs($this->user)->delete("threads/{$thread->slug}")
            ->assertStatus(403);

        //Owner can send delete request and thread is deleted.        
        $thread = Thread::inRandomOrder()->where('user_id', '=', $this->user->id)->first();
        $this->actingAs($this->user)->delete("threads/{$thread->slug}")
            ->assertStatus(200);

        $this->assertNull(Thread::find($thread->id));
        
    }
}
