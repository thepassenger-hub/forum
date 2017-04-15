<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Channel;
use \App\Thread;

class ThreadsController extends Controller
{
    public function index(Channel $channel)
    {
        return $channel->threads()->with('creator')->get();
    }

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
                'body' => request('body'),
                'description' => request('description'),
                'user_id' => auth()->id(),
                'channel_id' => $channel->id
            ]);
        } catch (Exception $e) {
            return response( $e, 500);
        }
    }
}
