<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;

class AdminController extends Controller
{
    public function deleteThread(Thread $thread)
    {
        $thread->delete();
        return response('The thread has been deleted', 200);
    }

    public function deleteReply(Reply $reply)
    {
        $reply->delete();
        return response('The thread has been deleted', 200);        
    }
}
