<?php

namespace App\Http\Controllers;

use \App\Channel;
use \App\Thread;
use \App\Filters\ThreadFilters;

class ThreadsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  Channel      $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        return $this->getThreads($channel, $filters);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer     $channel
     * @param  Thread $thread
     * @return mixed
     */
    public function show($channel, Thread $thread )
    {   
        return $thread->load('replies.creator.profile', 'creator.profile');
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  Channel $channel
     * @return mixed
     */
    public function store(Channel $channel)
    {
        $this -> validate(request(), [
                'title' => 'required|max:50|min:2',
                'body' => 'required|min:10'
            ]);

        $thread = $channel->addThread([
            'title' => request('title'),
            'slug' => makeSlugFromTitle(request('title')),
            'body' => request('body'),
            'user_id' => auth()->id(),
            'channel_id' => $channel->id,
            'last_reply' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);

        return response($thread->slug, 200);
    }

    /**
     * Fetch all relevant threads.
     *
     * @param  Channel  $channel
     * @param  ThreadFilters  $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::with('creator.profile', 'channel')->withCount('replies')
                         ->orderBy('last_reply', 'desc')->filter($filters);
        
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        
        return $threads->get();
    }

    /**
     * Update the given thread.
     *
     * @param Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Thread $thread)
    {
        $this->validate(request(), ['body' => 'required|min:10']);
        $thread->update(request(['body']));

        return response('Your thread has been updated', 200);
    }

    /**
     * Delete the given thread.
     *
     * @param Thread $thread
     * @return mixed
     */
    public function destroy(Thread $thread)
    {
        $thread->delete();
        return response('Your thread has been deleted', 200);
    }
}
