<?php

namespace Tests\Unit;

use Tests\TestCase;
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
        $user = User::inRandomOrder()->first();
        $channel = Channel::inRandomOrder()->first();
        $response = $this->actingAs($user)->post("channels/{$channel->slug}/threads", [
            'title' => null,
            'body' => null
        ])->assertStatus(302);

        $response = $this->actingAs($user)->post("channels/{$channel->slug}/threads", [
            'title' => 'My new title',
            'body' => 'My body at least 10 chars long'
        ]) -> assertStatus(200);

        $this->assertInstanceOf(Thread::class, Thread::where('slug', str_slug('My new title'))->first());

        $response = $this->actingAs($user)->post("channels/{$channel->slug}/threads", [
            'title' => 'My new title',
            'body' => 'My body at least 10 chars long'
        ]) -> assertStatus(200);

        $this->assertInstanceOf(Thread::class, Thread::where('slug', str_slug('My new title 1'))->first());

    }
}
