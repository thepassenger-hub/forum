<?php

namespace App\Http\Controllers;

use \App\Channel;

class ChannelsController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        return Channel::all();
    }
}
