<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $this->validate(request(), [
            'oldPassword' => 'required|min:6',
            'password' => 'required|min:6|confirmed'
        ]);

        if (Hash::check(request()->oldPassword, auth()->user()->password)) {
            auth()->user()->updatePassword(request()->password);
            return response('The password change was successful', 200);
        }

        return response(['error' => 'Current password doesn\'t match. Try again'], 403);
    }

    public function destroy($id)
    {
        //
    }
}
