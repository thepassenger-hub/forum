<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use App\User;

use \Carbon\Carbon;

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

    public function banUser(Request $request, User $user)
    {

        $this->validate($request, [
            'days' => 'required|numeric|min:1|max:10000'
        ]);

        $user->status()->update(['status' => 'banned', 'until' => Carbon::now()->addDays(request('days'))]);

        return response("User {$user->username} has been banned for {request('days')}.", 200);

    }
}
