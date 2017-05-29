<?php

namespace App\Http\Controllers;

use \App\Thread;
use \App\Reply;

class RepliesController extends Controller
{

    /**
     * Display a listing of replies
     *
     * @return mixed
     **/
    public function index()
    {
        return Reply::with('creator')->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Thread $thread
     **/
    public function store(Thread $thread)
    {
        $this->validate(request(), ['body' => 'required|min:1']);

        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Delete the given reply.
     *
     * @param \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $reply->delete();

        return response('Your reply has been deleted', 200);
    }

    /**
     * Update the given reply.
     *
     * @param \App\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Reply $reply)
    {
        $this->validate(request(), ['body' => 'required|min:1']);

        $reply->update(request(['body']));

        return response('Your reply has been updated', 200);
    }
}
