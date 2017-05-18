<?php

use Illuminate\Database\Seeder;
use \App\Reply;

class RepliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Thread::first()->addReply([
            'body' => 'Testing replies',
            'user_id' => 1
        ]);

        factory(Reply::class, 2000)->create();

        \App\Thread::first()->update(
            ['last_reply' => \Carbon\Carbon::now()->subMinutes(10)->format('Y-m-d H:i:s')]
        );
    }
}
