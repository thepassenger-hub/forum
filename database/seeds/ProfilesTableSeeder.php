<?php

use Illuminate\Database\Seeder;
use \App\Profile;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Profile::where('user_id', 1) -> update([
            'name' => 'Admin',
            'user_id' => 1,
            'location' => 'Treviso',
            'gender' => 'm',
            'bio' => 'Dummy admin account to test settings',
        ]);
    }
}
