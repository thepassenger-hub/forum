<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ReplyCreated;
use \App\Thread;
use \App\User;
use \App\Reply;

class RepliesController extends Controller
{

    public function index()
    {
        return Reply::with('creator')->latest()->get();
    }

    public function store(Thread $thread)
    {
        $this->validate(request(), ['body' => 'required|min:1']);

        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);
    }

    public function destroy(Reply $reply)
    {

        $reply->delete();
        return response('Your reply has been deleted', 200);
    }

    public function update(Reply $reply)
    {

        $this->validate(request(), ['body' => 'required|min:1']);
        $reply->update(request(['body']));

        return response('Your reply has been updated', 200);
    }
}
