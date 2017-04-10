<?php

use Illuminate\Database\Seeder;
use \App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'forumAdmin',
            'email' => 'forumAdmin@example.com',
            'password' => bcrypt('password'),
            'isAdmin' => true
        ]);
    }
}
