<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use App\User;

class AdminController extends Controller
{
    /**
     * Delete a thread.
     *
     * @param Thread $thread
     * @return \Illuminate\Http\Response
     **/
    public function deleteThread(Thread $thread)
    {
        $thread->delete();
        return response('The thread has been deleted', 200);
    }

    /**
     * Delete a reply.
     *
     * @param Reply $reply
     * @return \Illuminate\Http\Response
     **/
    public function deleteReply(Reply $reply)
    {
        $reply->delete();
        return response('The reply has been deleted', 200);        
    }

    /**
     * Ban a user.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     **/
    public function banUser(User $user)
    {
        $this->validate(request(), [
            'days' => 'required|numeric|min:1|max:6000'
        ]);

        $user->banForDays(request('days'));
        return response("User {$user->username} has been banned.", 200);
    }

    /**
     * Enable a user.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     **/
    public function enableUser(User $user)
    {
        $user->enable();
        return response("{$user->username}'s account has been enabled.", 200);
    }
}
