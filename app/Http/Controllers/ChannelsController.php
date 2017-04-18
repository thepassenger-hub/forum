<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Channel;

class ChannelsController extends Controller
{
    public function index()
    {
        return Channel::with('threads')->get();
    }

    public function show(Channel $channel)
    {
        return $channel;
    }


}
