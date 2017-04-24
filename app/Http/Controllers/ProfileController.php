<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Profile;
use \App\User;

class ProfileController extends Controller
{

    public function store()
    {
        $this->validate(request(), [
            'name' => 'max: 30',
            'gender' => 'required|max: 1',
            'bio' => 'max: 300',
            'location' => 'max:30'
        ]);
        
        auth()->user()->profile()->update(request()->all());
    }

    public function show(User $user)
    {
        return $user->profile()->with([
                'user' => function($query){
                            $query->withCount(['replies', 'threads']);
                        }
                ])->first();
    }
    
    public function uploadAvatar()
    {
        $this->validate(request(), [
            'avatar' => 'nullable|image'
        ]);
        $path = request()->file('avatar')->store('public/avatars');
        $path = str_replace('public/', 'storage/', $path);
        auth()->user()->profile()->update(['avatar' => $path]);

    }
}
