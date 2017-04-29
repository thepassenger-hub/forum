<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use \App\Channel;

class ChannelsController extends Controller
{
    public function index()
    {
        return Cache::rememberForever('channels', function() {
            return Channel::all();
        });
    }
}
