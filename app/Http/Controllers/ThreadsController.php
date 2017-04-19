<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Channel;
use \App\Thread;
use \App\Filters\ThreadFilters;

class ThreadsController extends Controller
{

    public function index(Channel $channel, ThreadFilters $filters)
    {
       
        return $this->getThreads($channel, $filters);

    }
    // public function index(Channel $channel)
    // {
    //     return $channel->threads()->with('creator')->get();
    // }

    public function show($channel, Thread $thread )
    {   
        return $thread->load('replies.creator', 'creator');
    }

    public function store(Channel $channel)
    {
        $this -> validate(request(), [
                'title' => 'required|max:50|min:2',
                'description' => 'required|min:2|max:50',
                'body' => 'required|min:10'
            ]);
        try {
            $channel->addThread([
                'title' => request('title'),
                'slug' => makeSlugFromTitle(request('title')),
                'body' => request('body'),
                'description' => request('description'),
                'user_id' => auth()->id(),
                'channel_id' => $channel->id
            ]);
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
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        // select * from threads join replies on replies.thread_id=threads.id group by threads.id  order by replies.created_at 
        $threads = Thread::with('creator', 'channel')->withCount('replies')
                        ->join('replies', 'replies.thread_id', '=', 'threads.id')
                        ->groupBy('threads.id')
                        ->orderByRaw('Max(replies.id) desc');
        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }
        return $threads->get();
    }
}
