<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use \App\Events\ProfileUpdated;

use \App\Profile;
use \App\User;

class ProfileController extends Controller
{

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

    public function show(Request $request, User $user)
    {
        return $user->profile()->with([
            'user' => function($query){
                        $query->withCount(['replies', 'threads']);
                        $query->with('replies.thread.channel');
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
        $user = auth()->user();
        $profile = $user->profile;
        $profile->update(['avatar' => $path]);
    }
}
