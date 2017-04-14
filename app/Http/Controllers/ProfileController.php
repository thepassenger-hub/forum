<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $out = auth()->user()->profile()->with([
                'user' => function($query){
                            $query->withCount(['replies', 'threads']);
                        }
                ])->first();
        return $out;
    }

    public function store()
    {
        // $this->validate(request(), [

        // ])
    }
}
