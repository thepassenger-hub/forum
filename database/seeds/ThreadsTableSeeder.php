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
        $title = 'Learn PHP in 3 steps.';
        $thread = Thread::create([
            'title' => $title,
            'slug' => str_slug($title, '-'),
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'user_id' => User::first()->id,
            'channel_id' => Channel::first()->id
        ]);
        
        factory(Thread::class, 100)->create();

    }
}
