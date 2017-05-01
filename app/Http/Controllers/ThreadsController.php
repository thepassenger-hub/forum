<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Channel;
use \App\Thread;
use \App\Filters\ThreadFilters;

class ThreadsController extends Controller
{

    public function index(Request $request, Channel $channel, ThreadFilters $filters)
    {

        return $this->getThreads($request, $channel, $filters);

    }

    public function show($channel, Thread $thread )
    {   
        return $thread->load('replies.creator.profile', 'creator.profile');
    }

    public function store(Channel $channel)
    {
        $this -> validate(request(), [
                'title' => 'required|max:50|min:2',
                'body' => 'required|min:10'
            ]);
        try {
            $thread = $channel->addThread([
                'title' => request('title'),
                'slug' => makeSlugFromTitle(request('title')),
                'body' => request('body'),
                'user_id' => auth()->id(),
                'channel_id' => $channel->id,
                'last_reply' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
            return response($thread->slug, 200);
        } catch (Exception $e) {
            return response( $e, 500);
        }
    }

    /**
     * Fetch all relevant threads.
     *
     * @param Channel       $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads(Request $request, Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::with('creator.profile', 'channel')->withCount('replies')
                         ->orderBy('last_reply', 'desc')->filter($filters);
            
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        return $threads->get();
    }

    public function test(Channel $channel, ThreadFilters $filters)
    {
        return $this->getThreads($channel, $filters);
    }
}
