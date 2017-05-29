<?php

namespace App\Http\Controllers;

use \App\Profile;
use \App\User;

class ProfileController extends Controller
{

    /**
     * Update the user's profile.
     **/
    public function update()
    {
        $this->validate(request(), [
            'name' => 'max: 30',
            'bio' => 'max: 300',
            'location' => 'max:30'
        ]);
        $user = auth()->user();
        $profile = $user->profile;
        $profile->update(request(['name', 'bio', 'location']));
    }

    /**
     * Show the user's profile.
     *
     * @param User $user
     * @return mixed
     **/
    public function show(User $user)
    {
        return $user->profile()->with([
            'user' => function($query){
                        $query->withCount(['replies', 'threads']);
                        $query->with('replies.thread.channel');
                    }
            ])->first();                 
    }
    
    /**
     * Store the user uploaded avatarr in the public storage folder.
     **/
    public function uploadAvatar()
    {
        $this->validate(request(), [
            'avatar' => 'nullable|image'
        ]);
        $path = request()->file('avatar')->store('public/avatars');
        $path = str_replace('public/', 'storage/', $path);
        $user = auth()->user();
        $profile = $user->profile;
        $profile->update(['avatar' => $path]);
    }
}
