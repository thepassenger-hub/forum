<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Thread;
use \App\User;

class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */

    public function index(User $user)
    {
        return $user->replies()->with('thread')->latest()->get();
    }

    public function store(Thread $thread)
    {
        $this->validate(request(), ['body' => 'required|min:1']);

        try {
            $thread->addReply([
                    'body' => request('body'),
                    'user_id' => auth()->id(),
                    'thread_id' => $thread->id
                ]);
        } catch (Exception $e) {
            return response( $e, 500);
        }
    }
}
