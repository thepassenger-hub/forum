<?php

use Illuminate\Database\Seeder;
use \App\User;
use \App\Channel;
use \App\Thread;

class ThreadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Thread::create([
            'title' => 'Learn PHP in 3 steps.',
            'description' => '3 Steps to learn the ins and outs of PHP the programming language.',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'user_id' => User::first()->id,
            'channel_id' => Channel::first()->id
        ]);

        factory(Thread::class, 50)->create();

    }
}
