<?php

use Illuminate\Database\Seeder;
use \App\Channel;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Channel::create([
            'name' => 'PHP',
            'description' => 'Qui si discute del linguaggio di programmazione PHP',
            'slug' => 'php'
        ]);

        factory(Channel::class, 10)->create();
    }
}
