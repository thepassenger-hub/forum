<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;

class UsersController extends Controller
{

    public function index()
    {
        return User::where('isAdmin', 0)->with('status')->get();
    }

    public function update()
    {
        $this->validate(request(), [
            'oldPassword' => 'required|min:6',
            'password' => 'required|min:6|confirmed'
        ]);

        if (auth()->user()->updatePassword(request()->oldPassword, request()->password)) {
            return response('The password change was successful', 200);
        }

        return response(['error' => 'Current password doesn\'t match. Try again'], 403);
    }
}
