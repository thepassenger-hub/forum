<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Thread;

class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channel, Thread $thread)
    {
        $this->validate(request(), ['body' => 'required|min:1']);

        $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id(),
                'thread_id' => $thread->id
            ]);
    }
}
